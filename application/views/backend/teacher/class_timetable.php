<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo get_phrase('my_class_timetables'); ?></h4>
                </div>
            </div>
            <div class="panel-body">
                <?php
                // Get teacher ID with validation
                $teacher_id = $this->session->userdata('teacher_id');
                if (!$teacher_id) {
                    echo '<div class="alert alert-danger">';
                    echo get_phrase('teacher_id_not_found') . '. ' . get_phrase('please_login_again');
                    echo '</div>';
                    return;
                }
                
                // Check if teacher has any assigned classes
                $this->db->where('teacher_id', $teacher_id);
                $timetable_exists = $this->db->get('timetable')->num_rows() > 0;
                
                if (!$timetable_exists) {
                    echo '<div class="alert alert-info">';
                    echo get_phrase('no_classes_assigned_to_you_in_timetable');
                    echo '</div>';
                    return;
                }
                ?>
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="col-sm-4">
                                <select name="class_filter" id="class_filter" class="form-control">
                                    <option value=""><?php echo get_phrase('filter_by_class'); ?></option>
                                    <?php
                                    // Get unique classes assigned to this teacher
                                    $this->db->distinct();
                                    $this->db->select('class_id');
                                    $this->db->where('teacher_id', $teacher_id);
                                    $classes_query = $this->db->get('timetable');
                                    
                                    if ($classes_query->num_rows() > 0) {
                                        foreach ($classes_query->result_array() as $row) {
                                            $class = $this->db->get_where('class', array('class_id' => $row['class_id']))->row();
                                            if ($class) {
                                                echo '<option value="' . $class->name . '">' . $class->name . '</option>';
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <select name="day_filter" id="day_filter" class="form-control">
                                    <option value=""><?php echo get_phrase('filter_by_day'); ?></option>
                                    <option value="monday"><?php echo get_phrase('monday'); ?></option>
                                    <option value="tuesday"><?php echo get_phrase('tuesday'); ?></option>
                                    <option value="wednesday"><?php echo get_phrase('wednesday'); ?></option>
                                    <option value="thursday"><?php echo get_phrase('thursday'); ?></option>
                                    <option value="friday"><?php echo get_phrase('friday'); ?></option>
                                    <option value="saturday"><?php echo get_phrase('saturday'); ?></option>
                                    <option value="sunday"><?php echo get_phrase('sunday'); ?></option>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" id="search_timetable" placeholder="<?php echo get_phrase('search_timetable'); ?>">
                            </div>
                            <div class="col-sm-2">
                                <button class="btn btn-primary" id="reset_filters" style="width: 100%;">
                                    <i class="fa fa-refresh"></i> <?php echo get_phrase('reset'); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <br><br>
                
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="timetable_table">
                        <thead>
                            <tr>
                                <th><?php echo get_phrase('class'); ?></th>
                                <th><?php echo get_phrase('section'); ?></th>
                                <th><?php echo get_phrase('subject'); ?></th>
                                <th><?php echo get_phrase('day'); ?></th>
                                <th><?php echo get_phrase('time'); ?></th>
                                <th><?php echo get_phrase('room'); ?></th>
                                <th><?php echo get_phrase('actions'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $this->db->where('teacher_id', $teacher_id);
                            $this->db->order_by('day', 'ASC');
                            $this->db->order_by('starting_time', 'ASC');
                            $timetables = $this->db->get('timetable')->result_array();
                            
                            foreach ($timetables as $row):
                                // Get class name with error handling
                                $class = $this->db->get_where('class', array('class_id' => $row['class_id']))->row();
                                $class_name = $class ? $class->name : get_phrase('unknown_class');
                                
                                // Get section name with error handling
                                $section = $this->db->get_where('section', array('section_id' => $row['section_id']))->row();
                                $section_name = $section ? $section->name : get_phrase('unknown_section');
                                
                                // Get subject name with error handling
                                $subject = $this->db->get_where('subject', array('subject_id' => $row['subject_id']))->row();
                                $subject_name = $subject ? $subject->name : get_phrase('unknown_subject');
                            ?>
                            <tr>
                                <td><?php echo $class_name; ?></td>
                                <td><?php echo $section_name; ?></td>
                                <td><?php echo $subject_name; ?></td>
                                <td><?php echo ucfirst($row['day']); ?></td>
                                <td><?php echo $row['starting_time'] . ' - ' . $row['ending_time']; ?></td>
                                <td><?php echo $row['room_number']; ?></td>
                                <td>
                                    <a href="<?php echo base_url(); ?>teacher/class_timetable/view/<?php echo $row['class_id']; ?>" class="btn btn-info btn-xs">
                                        <i class="fa fa-eye"></i> <?php echo get_phrase('view_full_timetable'); ?>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        // Initialize DataTable with sorting and pagination
        var table = $('#timetable_table').DataTable({
            "ordering": true,
            "paging": true,
            "info": true,
            "lengthChange": true,
            "searching": true,
            "dom": '<"top"f>rt<"bottom"lip><"clear">',
            "language": {
                "search": "<?php echo get_phrase('search'); ?>:",
                "paginate": {
                    "previous": "<?php echo get_phrase('previous'); ?>",
                    "next": "<?php echo get_phrase('next'); ?>"
                },
                "info": "<?php echo get_phrase('showing'); ?> _START_ <?php echo get_phrase('to'); ?> _END_ <?php echo get_phrase('of'); ?> _TOTAL_ <?php echo get_phrase('entries'); ?>",
                "lengthMenu": "<?php echo get_phrase('show'); ?> _MENU_ <?php echo get_phrase('entries'); ?>"
            }
        });
        
        // Custom filtering for class
        $('#class_filter').on('change', function() {
            table.column(0).search($(this).val()).draw();
        });
        
        // Custom filtering for day
        $('#day_filter').on('change', function() {
            table.column(3).search($(this).val()).draw();
        });
        
        // Custom search box
        $('#search_timetable').on('keyup', function() {
            table.search($(this).val()).draw();
        });
        
        // Reset filters
        $('#reset_filters').on('click', function() {
            $('#class_filter').val('');
            $('#day_filter').val('');
            $('#search_timetable').val('');
            table.search('').columns().search('').draw();
        });
        
        // Add color-coding for days
        $('#timetable_table tbody tr').each(function() {
            var day = $(this).find('td:nth-child(4)').text().toLowerCase();
            switch(day) {
                case 'monday':
                    $(this).addClass('bg-light-blue');
                    break;
                case 'tuesday':
                    $(this).addClass('bg-light-green');
                    break;
                case 'wednesday':
                    $(this).addClass('bg-light-purple');
                    break;
                case 'thursday':
                    $(this).addClass('bg-light-yellow');
                    break;
                case 'friday':
                    $(this).addClass('bg-light-orange');
                    break;
                case 'saturday':
                    $(this).addClass('bg-light-pink');
                    break;
                case 'sunday':
                    $(this).addClass('bg-light-gray');
                    break;
            }
        });
    });
</script>

<style>
/* Light background colors for each day */
.bg-light-blue { background-color: rgba(135, 206, 250, 0.2); }
.bg-light-green { background-color: rgba(144, 238, 144, 0.2); }
.bg-light-purple { background-color: rgba(221, 160, 221, 0.2); }
.bg-light-yellow { background-color: rgba(255, 255, 224, 0.2); }
.bg-light-orange { background-color: rgba(255, 228, 196, 0.2); }
.bg-light-pink { background-color: rgba(255, 182, 193, 0.2); }
.bg-light-gray { background-color: rgba(211, 211, 211, 0.2); }

/* Improve the filter section spacing */
.form-group {
    margin-bottom: 0;
}

/* Custom style for DataTable controls */
.dataTables_wrapper .dataTables_length, 
.dataTables_wrapper .dataTables_filter, 
.dataTables_wrapper .dataTables_info, 
.dataTables_wrapper .dataTables_paginate {
    padding: 10px 0;
}

.dataTables_wrapper .dataTables_paginate .paginate_button {
    padding: 0.3em 0.8em;
}
</style> 