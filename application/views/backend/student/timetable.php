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
                // Ensure student profile data is correctly loaded
                $student_id = $this->session->userdata('student_id');
                if (!$student_id) {
                    echo '<div class="alert alert-danger">';
                    echo get_phrase('student_id_not_found') . '. ' . get_phrase('please_login_again');
                    echo '</div>';
                    return;
                }
                
                // Get student's class and section with error handling
                $student = $this->db->get_where('student', array('student_id' => $student_id))->row();
                if (!$student) {
                    echo '<div class="alert alert-danger">';
                    echo get_phrase('student_profile_not_found');
                    echo '</div>';
                    return;
                }
                
                $class_id = $student->class_id;
                $section_id = $student->section_id;
                
                if (empty($class_id) || empty($section_id)): 
                ?>
                <div class="alert alert-danger">
                    <?php echo get_phrase('class_or_section_not_assigned'); ?>
                </div>
                <?php else: 
                    // Check if timetable entries exist for this class/section
                    $this->db->where('class_id', $class_id);
                    $this->db->where('section_id', $section_id);
                    $timetable_exists = $this->db->get('timetable')->num_rows() > 0;
                    
                    if (!$timetable_exists):
                ?>
                <div class="alert alert-info">
                    <?php echo get_phrase('no_timetable_found_for_your_class'); ?>
                </div>
                <?php else: ?>
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
                            
                            if (!empty($timetables)):
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
                            <?php 
                                endforeach;
                            endif;
                            ?>
                        </tbody>
                    </table>
                </div>
                
                <?php 
                    endif; // end of if(!$timetable_exists)
                endif; // end of if(empty($class_id) || empty($section_id))
                ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    // Immediately hide any loading overlays when page loads
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Student timetable loaded');
        hideAllLoaders();
        initializeDataTable();
    });
    
    // Function to hide all possible loaders
    function hideAllLoaders() {
        var loaders = document.querySelectorAll('.loading-overlay, .loader, .ajax-loader, #loading-message, .loading, [class*="loading"], [id*="loading"], [class*="loader"], [id*="loader"]');
        console.log('Hiding ' + loaders.length + ' loaders');
        loaders.forEach(function(loader) {
            loader.style.display = 'none';
            loader.style.visibility = 'hidden';
        });
    }
    
    // Initialize DataTable with error handling
    function initializeDataTable() {
        try {
            console.log('Initializing DataTable');
            
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
                    hideAllLoaders();
                    
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
                }
            });
            
            console.log('DataTable loaded successfully');
        } catch (e) {
            console.error('Error initializing DataTable: ' + e.message);
            hideAllLoaders();
        }
    }
    
    // Call this function periodically to ensure loaders are hidden
    setInterval(hideAllLoaders, 2000);
</script>

<style>
/* Force hide any loading effects that may be causing issues */
.loading-overlay, .loader, .ajax-loader, #loading-message, .loading, 
[class*="loading"], [id*="loading"], [class*="loader"], [id*="loader"] {
    display: none !important;
    visibility: hidden !important;
    opacity: 0 !important;
    z-index: -999 !important;
}

/* Light background colors for each day */
.bg-light-blue { background-color: rgba(135, 206, 250, 0.2) !important; }
.bg-light-green { background-color: rgba(144, 238, 144, 0.2) !important; }
.bg-light-purple { background-color: rgba(221, 160, 221, 0.2) !important; }
.bg-light-yellow { background-color: rgba(255, 255, 224, 0.2) !important; }
.bg-light-orange { background-color: rgba(255, 228, 196, 0.2) !important; }
.bg-light-pink { background-color: rgba(255, 182, 193, 0.2) !important; }
.bg-light-gray { background-color: rgba(211, 211, 211, 0.2) !important; }

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