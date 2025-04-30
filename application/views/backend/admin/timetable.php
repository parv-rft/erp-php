<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo get_phrase('class_timetable'); ?></h4>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <a href="javascript:void(0);" onclick="showTimetableAddModal();" class="btn btn-primary">
                            <i class="fa fa-plus"></i> <?php echo get_phrase('add_timetable'); ?>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <?php echo form_open(base_url() . 'admin/timetable_view', array('class' => 'form-inline pull-right')); ?>
                            <div class="form-group">
                                <select name="class_id" class="form-control" required>
                                    <option value=""><?php echo get_phrase('select_class'); ?></option>
                                    <?php
                                    $classes = $this->db->get('class')->result_array();
                                    foreach ($classes as $row) {
                                    ?>
                                    <option value="<?php echo $row['class_id']; ?>"><?php echo $row['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <select name="section_id" class="form-control" required>
                                    <option value=""><?php echo get_phrase('select_section'); ?></option>
                                    <?php
                                    $sections = $this->db->get('section')->result_array();
                                    foreach ($sections as $row) {
                                    ?>
                                    <option value="<?php echo $row['section_id']; ?>"><?php echo $row['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-info"><?php echo get_phrase('view_timetable'); ?></button>
                        <?php echo form_close(); ?>
                    </div>
                </div>
                <br>
                
                <!-- Filter and search section -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="col-sm-3">
                                <select name="class_filter" id="class_filter" class="form-control">
                                    <option value=""><?php echo get_phrase('filter_by_class'); ?></option>
                                    <?php foreach ($classes as $row) { ?>
                                    <option value="<?php echo $row['name']; ?>"><?php echo $row['name']; ?></option>
                                    <?php } ?>
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
                
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered table-striped" id="timetable_table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?php echo get_phrase('class'); ?></th>
                                    <th><?php echo get_phrase('section'); ?></th>
                                    <th><?php echo get_phrase('subject'); ?></th>
                                    <th><?php echo get_phrase('teacher'); ?></th>
                                    <th><?php echo get_phrase('day'); ?></th>
                                    <th><?php echo get_phrase('time'); ?></th>
                                    <th><?php echo get_phrase('room'); ?></th>
                                    <th><?php echo get_phrase('options'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $count = 1;
                                $this->db->order_by('class_id', 'ASC');
                                $timetables = $this->db->get('timetable')->result_array();
                                foreach ($timetables as $row) {
                                    // Get class name
                                    $class = $this->db->get_where('class', array('class_id' => $row['class_id']))->row();
                                    $class_name = $class ? $class->name : 'Unknown';
                                    
                                    // Get section name
                                    $section = $this->db->get_where('section', array('section_id' => $row['section_id']))->row();
                                    $section_name = $section ? $section->name : 'Unknown';
                                    
                                    // Get subject name
                                    $subject = $this->db->get_where('subject', array('subject_id' => $row['subject_id']))->row();
                                    $subject_name = $subject ? $subject->name : 'Unknown';
                                    
                                    // Get teacher name
                                    $teacher = $this->db->get_where('teacher', array('teacher_id' => $row['teacher_id']))->row();
                                    $teacher_name = $teacher ? $teacher->name : 'Unknown';
                                ?>
                                <tr>
                                    <td><?php echo $count++; ?></td>
                                    <td><?php echo $class_name; ?></td>
                                    <td><?php echo $section_name; ?></td>
                                    <td><?php echo $subject_name; ?></td>
                                    <td><?php echo $teacher_name; ?></td>
                                    <td><?php echo ucfirst($row['day']); ?></td>
                                    <td><?php echo $row['starting_time'] . ' - ' . $row['ending_time']; ?></td>
                                    <td><?php echo $row['room_number']; ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">
                                                <?php echo get_phrase('action'); ?> <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                                <li>
                                                    <a href="javascript:void(0);" onclick="showAjaxModal('<?php echo base_url(); ?>modal/popup/timetable_edit/<?php echo $row['timetable_id']; ?>');">
                                                        <i class="fa fa-pencil"></i> <?php echo get_phrase('edit'); ?>
                                                    </a>
                                                </li>
                                                <li class="divider"></li>
                                                <li>
                                                    <a href="javascript:void(0);" onclick="confirm_modal('<?php echo base_url(); ?>admin/timetable/delete/<?php echo $row['timetable_id']; ?>');">
                                                        <i class="fa fa-trash-o"></i> <?php echo get_phrase('delete'); ?>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        // Console logging for debugging
        console.log('Timetable page loaded');
        
        try {
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
                },
                "initComplete": function(settings, json) {
                    console.log('DataTable initialization complete');
                    // Hide any loaders that might be active
                    $('.loading-overlay').hide();
                    $('#loading-message').hide();
                }
            });
            
            // Custom filtering for class
            $('#class_filter').on('change', function() {
                table.column(1).search($(this).val()).draw();
            });
            
            // Custom filtering for day
            $('#day_filter').on('change', function() {
                table.column(5).search($(this).val()).draw();
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
                var day = $(this).find('td:nth-child(6)').text().toLowerCase();
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
            
            // Class dropdown affecting section dropdown with improved error handling
            $("select[name='class_id']").change(function() {
                var class_id = $(this).val();
                console.log('Class changed to: ' + class_id);
                
                if (!class_id) {
                    console.log('No class_id selected');
                    return;
                }
                
                // Show loading indicator in the section dropdown
                $("select[name='section_id']").html('<option value=""><?php echo get_phrase("loading..."); ?></option>');
                
                $.ajax({
                    url: '<?php echo base_url(); ?>admin/get_class_section/' + class_id,
                    type: 'GET',
                    dataType: 'html',
                    success: function(response) {
                        console.log('Sections loaded successfully');
                        $("select[name='section_id']").html(response);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading sections: ' + error);
                        // Show error and reset dropdown
                        $("select[name='section_id']").html('<option value=""><?php echo get_phrase("error_loading_sections"); ?></option>');
                    },
                    complete: function() {
                        // Ensure any loading indicators are removed
                        $('.ajax-loader').hide();
                    }
                });
            });
        } catch (e) {
            console.error('Error in timetable initialization: ' + e.message);
            // Hide any loaders and show error message
            $('.loading-overlay').hide();
            $('#loading-message').hide();
            alert('<?php echo get_phrase("an_error_occurred_while_loading_the_timetable"); ?>');
        }
    });
    
    // Function to safely show timetable add modal with error handling
    function showTimetableAddModal() {
        // Show loading overlay
        $('body').append('<div id="modal-loading-overlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; display: flex; justify-content: center; align-items: center;"><div style="background: white; padding: 20px; border-radius: 5px;"><i class="fa fa-spinner fa-spin fa-2x"></i> Loading...</div></div>');
        
        // Load modal content with timeout and error handling
        $.ajax({
            url: '<?php echo base_url(); ?>modal/popup/timetable_add/',
            timeout: 10000, // 10 second timeout
            success: function(response) {
                // Remove loading overlay
                $('#modal-loading-overlay').remove();
                
                // Create modal if it doesn't exist
                if ($('#modal_ajax').length === 0) {
                    $('body').append('<div id="modal_ajax" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>');
                }
                
                // Set modal content and show
                $("#modal_ajax").html(response);
                $("#modal_ajax").modal('show');
                
                // Initialize form controls after modal is shown
                $("#modal_ajax").on('shown.bs.modal', function() {
                    $('.timepicker').timepicker({
                        showMeridian: false,
                        defaultTime: false
                    });
                });
            },
            error: function(xhr, status, error) {
                // Remove loading overlay
                $('#modal-loading-overlay').remove();
                
                // Show error message
                var errorMessage = 'Error loading modal';
                if (xhr.status === 500) {
                    errorMessage = 'Internal Server Error. Please try again later.';
                } else if (status === 'timeout') {
                    errorMessage = 'Request timed out. Please try again.';
                } else if (error) {
                    errorMessage = 'Error: ' + error;
                }
                
                // Create and show error modal
                if ($('#error_modal').length === 0) {
                    $('body').append('<div id="error_modal" class="modal fade" tabindex="-1" role="dialog"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Error</h4></div><div class="modal-body"></div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div></div></div></div>');
                }
                
                $('#error_modal .modal-body').html('<div class="alert alert-danger">' + errorMessage + '</div>');
                $('#error_modal').modal('show');
                
                console.error('AJAX Error:', xhr.status, status, error);
            }
        });
    }
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

/* Hide any loading effects that may be causing issues */
.loading-overlay, .ajax-loader, #loading-message {
    display: none !important;
}

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