<?php
// Ensure class_id is properly set from the URL parameter
$class_id = $this->uri->segment(4);
if (!$class_id || !is_numeric($class_id)) {
    redirect(base_url() . 'teacher/timetable', 'refresh');
}

// Get class name with proper error handling
$class = $this->db->get_where('class', array('class_id' => $class_id))->row();
$class_name = $class ? $class->name : get_phrase('unknown_class');

// Verify if the teacher is assigned to this class
$teacher_id = $this->session->userdata('teacher_id');
$this->db->where('teacher_id', $teacher_id);
$this->db->where('class_id', $class_id);
$is_assigned = $this->db->get('timetable')->num_rows() > 0;

if (!$is_assigned) {
    echo '<div class="alert alert-danger">' . get_phrase('not_authorized_to_view_this_class_timetable') . '</div>';
    echo '<div class="text-center"><a href="' . base_url() . 'teacher/timetable" class="btn btn-primary"><i class="fa fa-arrow-left"></i> ' . get_phrase('back_to_teacher_timetable') . '</a></div>';
    return;
}
?>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    <i class="entypo-calendar"></i>
                    <?php echo get_phrase('Class Timetable'); ?> - <?php echo $class_name; ?>
                </div>
            </div>
            <div class="panel-body">
                <div class="alert alert-info">
                    <a href="<?php echo base_url(); ?>teacher/timetable" class="btn btn-primary pull-right">
                        <i class="fa fa-arrow-left"></i> <?php echo get_phrase('back_to_teacher_timetable'); ?>
                    </a>
                    <strong><?php echo get_phrase('class'); ?>:</strong> <?php echo $class_name; ?>
                    <div class="clearfix"></div>
                </div>
                
                <div class="timetable-responsive">
                    <?php
                    // Get all time slots in ascending order with proper error handling
                    $this->db->group_by('starting_time');
                    $this->db->order_by('starting_time', 'ASC');
                    $time_slots_result = $this->db->get_where('timetable', array('class_id' => $class_id));
                    
                    if ($time_slots_result->num_rows() == 0) {
                        echo '<div class="alert alert-warning">' . get_phrase('no_timetable_found_for_this_class') . '</div>';
                        return;
                    }
                    
                    $time_slots = $time_slots_result->result_array();
                    
                    // Create a unique array of time slots
                    $unique_time_slots = array();
                    foreach ($time_slots as $slot) {
                        $time_key = $slot['starting_time'] . '-' . $slot['ending_time'];
                        $unique_time_slots[$time_key] = array(
                            'start' => $slot['starting_time'],
                            'end' => $slot['ending_time']
                        );
                    }
                    
                    if (empty($unique_time_slots)) {
                        echo '<div class="alert alert-warning">' . get_phrase('no_time_slots_found') . '</div>';
                        return;
                    }
                    ?>
                    
                    <table class="table table-bordered timetable-table">
                        <thead>
                            <tr>
                                <th width="100"><?php echo get_phrase('day'); ?> \ <?php echo get_phrase('time'); ?></th>
                                <?php
                                // Display the time slot headers
                                foreach ($unique_time_slots as $time_key => $time_data) {
                                    echo '<th>';
                                    echo $time_data['start'] . ' - ' . $time_data['end'];
                                    echo '</th>';
                                }
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
                            
                            foreach ($days as $day) {
                                echo '<tr class="day-' . $day . '">';
                                echo '<td class="day-name">' . ucfirst($day) . '</td>';
                                
                                foreach ($unique_time_slots as $time_key => $time_data) {
                                    echo '<td>';
                                    
                                    // Find the class for this day and time slot
                                    $this->db->where('class_id', $class_id);
                                    $this->db->where('day', $day);
                                    $this->db->where('starting_time', $time_data['start']);
                                    $this->db->where('ending_time', $time_data['end']);
                                    $timetable_entry = $this->db->get('timetable')->row_array();
                                    
                                    if (!empty($timetable_entry)) {
                                        // Get subject info with error handling
                                        $subject = $this->db->get_where('subject', array('subject_id' => $timetable_entry['subject_id']))->row_array();
                                        $subject_name = !empty($subject) ? $subject['name'] : get_phrase('unknown_subject');
                                        
                                        // Get teacher info with error handling
                                        $teacher = $this->db->get_where('teacher', array('teacher_id' => $timetable_entry['teacher_id']))->row_array();
                                        $teacher_name = !empty($teacher) ? $teacher['name'] : get_phrase('unknown_teacher');
                                        
                                        echo '<div class="timetable-entry">';
                                        echo '<div class="subject">' . $subject_name . '</div>';
                                        echo '<div class="teacher">' . $teacher_name . '</div>';
                                        if (!empty($timetable_entry['room_number'])) {
                                            echo '<div class="room">Room: ' . $timetable_entry['room_number'] . '</div>';
                                        }
                                        echo '</div>';
                                    } else {
                                        echo '<div class="timetable-empty">-</div>';
                                    }
                                    
                                    echo '</td>';
                                }
                                
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    // Immediately hide any loading overlays when page loads
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Teacher timetable view loaded');
        hideAllLoaders();
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

/* Style the timetable */
.timetable-responsive {
    overflow-x: auto;
}

.timetable-table {
    width: 100%;
    border-collapse: collapse;
}

.timetable-table th, .timetable-table td {
    padding: 10px;
    text-align: center;
    border: 1px solid #ddd;
}

.timetable-table th {
    background-color: #f5f5f5;
    font-weight: bold;
}

.day-name {
    font-weight: bold;
    text-align: left;
}

.timetable-entry {
    background-color: #e6f7ff;
    border-radius: 5px;
    padding: 5px;
}

.timetable-entry .subject {
    font-weight: bold;
    margin-bottom: 3px;
}

.timetable-entry .teacher {
    font-size: 12px;
    font-style: italic;
    margin-bottom: 2px;
}

.timetable-entry .room {
    font-size: 11px;
}

.timetable-empty {
    color: #999;
}

/* Color coding for days */
.day-monday { background-color: rgba(135, 206, 250, 0.1) !important; }
.day-tuesday { background-color: rgba(144, 238, 144, 0.1) !important; }
.day-wednesday { background-color: rgba(221, 160, 221, 0.1) !important; }
.day-thursday { background-color: rgba(255, 255, 224, 0.1) !important; }
.day-friday { background-color: rgba(255, 228, 196, 0.1) !important; }
.day-saturday { background-color: rgba(255, 182, 193, 0.1) !important; }
.day-sunday { background-color: rgba(211, 211, 211, 0.1) !important; }
</style> 