<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo get_phrase('class_timetable'); ?></h4>
                </div>
            </div>
            <div class="panel-body">
                <?php
                // Validate student information
                $student_id = $this->session->userdata('student_id');
                if (!$student_id) {
                    echo '<div class="alert alert-danger">';
                    echo get_phrase('student_id_not_found') . '. ' . get_phrase('please_login_again');
                    echo '</div>';
                    return;
                }
                
                // Get student's class and section with proper error handling
                $student = $this->db->get_where('student', array('student_id' => $student_id))->row();
                if (!$student) {
                    echo '<div class="alert alert-danger">';
                    echo get_phrase('student_profile_not_found');
                    echo '</div>';
                    return;
                }
                
                $class_id = $student->class_id;
                $section_id = $student->section_id;
                
                if (empty($class_id) || empty($section_id)) {
                    echo '<div class="alert alert-danger">';
                    echo get_phrase('class_or_section_not_assigned');
                    echo '</div>';
                    return;
                }
                
                // Check if timetable entries exist
                $this->db->where('class_id', $class_id);
                $this->db->where('section_id', $section_id);
                $timetable_exists = $this->db->get('timetable')->num_rows() > 0;
                
                if (!$timetable_exists) {
                    echo '<div class="alert alert-info">';
                    echo get_phrase('no_timetable_found_for_your_class');
                    echo '</div>';
                    return;
                }
                ?>
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
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
                                <select name="subject_filter" id="subject_filter" class="form-control">
                                    <option value=""><?php echo get_phrase('filter_by_subject'); ?></option>
                                    <?php
                                    $subjects = $this->db->get_where('subject', array('class_id' => $class_id))->result_array();
                                    foreach ($subjects as $row) {
                                    ?>
                                    <option value="<?php echo $row['name']; ?>"><?php echo $row['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-sm-4">
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
                                <th><?php echo get_phrase('subject'); ?></th>
                                <th><?php echo get_phrase('teacher'); ?></th>
                                <th><?php echo get_phrase('day'); ?></th>
                                <th><?php echo get_phrase('time'); ?></th>
                                <th><?php echo get_phrase('room'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $this->db->where('class_id', $class_id);
                            $this->db->where('section_id', $section_id);
                            $this->db->order_by('day', 'ASC');
                            $this->db->order_by('starting_time', 'ASC');
                            $timetables = $this->db->get('timetable')->result_array();
                            
                            foreach ($timetables as $row):
                                // Get subject name with error handling
                                $subject = $this->db->get_where('subject', array('subject_id' => $row['subject_id']))->row();
                                $subject_name = $subject ? $subject->name : get_phrase('unknown_subject');
                                
                                // Get teacher name with error handling
                                $teacher = $this->db->get_where('teacher', array('teacher_id' => $row['teacher_id']))->row();
                                $teacher_name = $teacher ? $teacher->name : get_phrase('unknown_teacher');
                            ?>
                            <tr>
                                <td><?php echo $subject_name; ?></td>
                                <td><?php echo $teacher_name; ?></td>
                                <td><?php echo ucfirst($row['day']); ?></td>
                                <td><?php echo $row['starting_time'] . ' - ' . $row['ending_time']; ?></td>
                                <td><?php echo $row['room_number']; ?></td>
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
        
        // Custom filtering for subject
        $('#subject_filter').on('change', function() {
            table.column(0).search($(this).val()).draw();
        });
        
        // Custom filtering for day
        $('#day_filter').on('change', function() {
            table.column(2).search($(this).val()).draw();
        });
        
        // Custom search box
        $('#search_timetable').on('keyup', function() {
            table.search($(this).val()).draw();
        });
        
        // Reset filters
        $('#reset_filters').on('click', function() {
            $('#subject_filter').val('');
            $('#day_filter').val('');
            $('#search_timetable').val('');
            table.search('').columns().search('').draw();
        });
        
        // Add color-coding for days
        $('#timetable_table tbody tr').each(function() {
            var day = $(this).find('td:nth-child(3)').text().toLowerCase();
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