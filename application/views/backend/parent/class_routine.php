<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h4 class="panel-title"><?php echo get_phrase('class_timetable_for_children'); ?></h4>
            </div>
            <div class="panel-body">
                <div class="row mb-3">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="student_id"><?php echo get_phrase('select_child'); ?></label>
                            <select class="form-control" id="student_id" onchange="loadStudentTimetable()">
                                <?php 
                                    $parent_id = $this->session->userdata('parent_id');
                                    $children = $this->db->get_where('student', array('parent_id' => $parent_id))->result_array();
                                    
                                    foreach($children as $child): 
                                        $class_id = $child['class_id'];
                                        $section_id = $child['section_id'];
                                        
                                        // Add error handling for class data
                                        $class_data = $this->db->get_where('class', array('class_id' => $class_id))->row();
                                        $class_name = ($class_data) ? $class_data->name : 'Unknown Class';
                                        
                                        // Add error handling for section data
                                        $section_data = $this->db->get_where('section', array('section_id' => $section_id))->row();
                                        
                                        // If section_id is 0 or section not found, try to get a valid section
                                        if (!$section_data && $class_id) {
                                            // Try to get the first section for this class
                                            $first_section = $this->db->get_where('section', array('class_id' => $class_id))->row();
                                            if ($first_section) {
                                                $section_id = $first_section->section_id;
                                                $section_data = $first_section;
                                                
                                                // Update the student record with the correct section
                                                $this->db->where('student_id', $child['student_id']);
                                                $this->db->update('student', array('section_id' => $section_id));
                                            }
                                        }
                                        
                                        $section_name = ($section_data) ? $section_data->name : 'Unknown Section';
                                        
                                        // Debug data
                                        echo "<!-- Debug data: student_id=" . $child['student_id'] . 
                                             ", class_id=" . $class_id . 
                                             ", section_id=" . $section_id . 
                                             ", class_name=" . $class_name . 
                                             ", section_name=" . $section_name . " -->";
                                ?>
                                    <option value="<?php echo $child['student_id']; ?>" 
                                            data-class-id="<?php echo $class_id; ?>" 
                                            data-section-id="<?php echo $section_id; ?>"
                                            data-class-name="<?php echo $class_name; ?>"
                                            data-section-name="<?php echo $section_name; ?>">
                                        <?php echo $child['name']; ?> - 
                                        <?php echo $class_name . ' (' . $section_name . ')'; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="alert alert-info" style="margin-bottom: 0; margin-top: 24px;">
                            <strong><?php echo get_phrase('class'); ?>:</strong> 
                            <span id="class_display"></span>
                            &nbsp;&nbsp;|&nbsp;&nbsp;
                            <strong><?php echo get_phrase('section'); ?>:</strong> 
                            <span id="section_display"></span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-success btn-block" onclick="printTimetable()" style="margin-top: 24px; border: none; height: 46px;">
                            <i class="fa fa-print"></i> <?php echo get_phrase('print_timetable'); ?>
                        </button>
                    </div>
                </div>
                
                <div id="timetable_container" class="timetable-container">
                    <table class="table table-bordered timetable-table">
                        <thead>
                            <tr>
                                <th class="time-col"><?php echo get_phrase('time_slot'); ?></th>
                                <th><?php echo get_phrase('monday'); ?></th>
                                <th><?php echo get_phrase('tuesday'); ?></th>
                                <th><?php echo get_phrase('wednesday'); ?></th>
                                <th><?php echo get_phrase('thursday'); ?></th>
                                <th><?php echo get_phrase('friday'); ?></th>
                                <th><?php echo get_phrase('saturday'); ?></th>
                                <th><?php echo get_phrase('sunday'); ?></th>
                            </tr>
                        </thead>
                        <tbody id="timetable_body">
                            <tr>
                                <td colspan="8" class="text-center"><i class="fa fa-spinner fa-spin"></i> <?php echo get_phrase('loading_timetable'); ?>...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .timetable-container {
        overflow-x: auto;
    }
    
    .timetable-table {
        min-width: 1000px;
        background: #fff;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .timetable-table thead th {
        text-align: center;
        font-weight: 600;
        padding: 15px 8px;
        color: #fff;
        background: linear-gradient(135deg, #2196F3, #1976D2);
        border: 1px solid #1565C0;
        text-transform: uppercase;
        font-size: 13px;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
    }
    
    .timetable-table thead th:hover {
        background: linear-gradient(135deg, #1976D2, #1565C0);
    }
    
    .time-col {
        width: 120px;
        background: #f8f9fa !important;
        color: #333 !important;
        font-weight: bold;
        border-right: 2px solid #1565C0 !important;
    }
    
    .timetable-cell {
        height: 100px;
        padding: 8px !important;
        vertical-align: top;
        position: relative;
        background: #fff;
        transition: all 0.3s ease;
        border: 1px solid #e0e0e0;
    }
    
    .time-slot-row {
        height: 100px;
    }
    
    .time-display {
        text-align: center;
        font-weight: bold;
        padding: 8px;
        color: #333 !important;
        background: #f1f3f4;
        border-radius: 4px;
        margin-bottom: 5px;
    }
    
    .subject-name {
        font-weight: bold;
        font-size: 13px;
        color: #2196F3;
        margin-bottom: 5px;
    }
    
    .teacher-name {
        font-size: 12px;
        color: #666;
        font-style: italic;
    }
    
    .room-number {
        font-size: 11px;
        color: #888;
        margin-top: 5px;
    }
    
    .editable-cell {
        min-height: 90px;
        display: flex;
        flex-direction: column;
        background: linear-gradient(135deg, #E3F2FD, #BBDEFB);
        border-radius: 6px;
        border: 1px solid #90CAF9;
        padding: 12px;
        transition: all 0.3s ease;
    }
    
    .editable-cell:hover {
        background: linear-gradient(135deg, #BBDEFB, #90CAF9);
        box-shadow: 0 3px 8px rgba(33, 150, 243, 0.2);
        transform: translateY(-2px);
    }
    
    .mb-3 {
        margin-bottom: 15px;
    }
    
    @media print {
        .panel-heading button, 
        .row:first-child,
        .btn {
            display: none !important;
        }
        
        .panel {
            border: none !important;
            box-shadow: none !important;
        }
        
        .panel-body {
            padding: 0 !important;
        }

        .timetable-cell {
            border: 1px solid #ddd !important;
        }
    }
</style>

<script type="text/javascript">
    $(document).ready(function() {
        // Add debugging to check all data attributes
        console.log("Checking data attributes on load:");
        var firstChild = $('#student_id option:first');
        console.log("First child data:", {
            student_id: firstChild.val(),
            class_id: firstChild.attr('data-class-id'),
            section_id: firstChild.attr('data-section-id'),
            class_name: firstChild.attr('data-class-name'),
            section_name: firstChild.attr('data-section-name')
        });
        
        // Load timetable on page load for the first child
        loadStudentTimetable();
    });
    
    // Function to print timetable
    function printTimetable() {
        // Get current student class and section
        var student_id = $('#student_id').val();
        var class_id = $('#student_id option:selected').attr('data-class-id');
        var section_id = $('#student_id option:selected').attr('data-section-id');
        
        console.log("Print timetable for:", {
            student_id: student_id,
            class_id: class_id,
            section_id: section_id
        });
        
        // Open print view in a new window
        var printUrl = '<?php echo site_url('parents/class_routine/print/'); ?>' + class_id + '/' + section_id + '/' + student_id;
        window.open(printUrl, '_blank', 'width=1000,height=700');
    }
    
    // Function to load timetable data for a specific student
    function loadStudentTimetable() {
        var selected = $('#student_id option:selected');
        
        // Direct attribute access for debugging
        var student_id = selected.val();
        var class_id = selected.attr('data-class-id');
        var section_id = selected.attr('data-section-id');
        var class_name = selected.attr('data-class-name');
        var section_name = selected.attr('data-section-name');
        
        // Debug: Inspect data attributes directly
        console.log("Selected option HTML:", selected.prop('outerHTML'));
        console.log("Using attr() to access data:", {
            class_id_attr: class_id,
            section_id_attr: section_id,
            class_name_attr: class_name,
            section_name_attr: section_name
        });
        
        // Validate section_id
        if (!section_id || section_id == "0") {
            console.warn("Invalid section_id detected:", section_id);
            // You could try to get a valid section_id here with another AJAX call
            // For now, just show a warning
            $('#section_display').text('Missing Section - Please contact administrator');
        } else {
            // Update the class and section display
            $('#class_display').text(class_name || 'Unknown Class');
            $('#section_display').text(section_name || 'Unknown Section');
        }
        
        // Load timetable data
        $.ajax({
            url: '<?php echo site_url('parents/get_class_timetable_data'); ?>',
            type: 'POST',
            data: {
                student_id: student_id,
                class_id: class_id,
                section_id: section_id
            },
            dataType: 'json',
            beforeSend: function() {
                $('#timetable_body').html('<tr><td colspan="8" class="text-center"><i class="fa fa-spinner fa-spin"></i> <?php echo get_phrase('loading'); ?>...</td></tr>');
            },
            success: function(data) {
                console.log("Timetable data loaded:", data);
                
                // Check if we got an error message
                if (data && data.status === 'error') {
                    $('#timetable_body').html('<tr><td colspan="8" class="text-center text-danger">' + data.message + '</td></tr>');
                    return;
                }
                
                renderTimetable(data);
            },
            error: function(xhr, status, error) {
                console.error('Error loading timetable:', xhr);
                // Show a more detailed error message
                var errorMessage = 'Error loading timetable';
                if (xhr.responseText) {
                    try {
                        var response = JSON.parse(xhr.responseText);
                        if (response.message) {
                            errorMessage = response.message;
                        }
                    } catch (e) {
                        // If we can't parse the JSON, just show a generic error
                        errorMessage = 'Server error: ' + error;
                    }
                }
                $('#timetable_body').html('<tr><td colspan="8" class="text-center text-danger">' + errorMessage + '</td></tr>');
            }
        });
    }
    
    // Function to render timetable
    function renderTimetable(timetableData) {
        if (!timetableData || !timetableData.length) {
            $('#timetable_body').html('<tr><td colspan="8" class="text-center"><?php echo get_phrase('no_timetable_entries_found'); ?></td></tr>');
            return;
        }
        
        // Group entries by time slot
        const timeSlots = {};
        timetableData.forEach(function(entry) {
            // Make the code more robust by checking for various field names
            const timeStart = entry.time_start || entry.time_slot_start || entry.time_from;
            const timeEnd = entry.time_end || entry.time_slot_end || entry.time_to;
            const day = entry.day || entry.day_of_week || 'monday';
            const subject = entry.subject || entry.subject_name || entry.name || 'Unknown Subject';
            const teacherName = entry.teacher_name || 'Not assigned';
            const roomNumber = entry.classroom_id || entry.room_number || 'N/A';
            
            const timeKey = `${timeStart}-${timeEnd}`;
            if (!timeSlots[timeKey]) {
                timeSlots[timeKey] = {
                    start: timeStart,
                    end: timeEnd,
                    days: {
                        monday: null,
                        tuesday: null,
                        wednesday: null,
                        thursday: null,
                        friday: null,
                        saturday: null,
                        sunday: null
                    }
                };
            }
            timeSlots[timeKey].days[day.toLowerCase()] = {
                subject: subject,
                teacher_name: teacherName,
                room_number: roomNumber
            };
        });
        
        let html = '';
        Object.keys(timeSlots).sort().forEach(function(timeKey) {
            const slot = timeSlots[timeKey];
            html += `
                <tr class="time-slot-row">
                    <td class="time-col">
                        <div class="time-display">
                            ${slot.start}<br>to<br>${slot.end}
                        </div>
                    </td>`;
            
            ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'].forEach(function(day) {
                const entry = slot.days[day];
                if (entry) {
                    html += `
                        <td class="timetable-cell">
                            <div class="editable-cell">
                                <div class="subject-name">${entry.subject}</div>
                                <div class="teacher-name">${entry.teacher_name}</div>
                                <div class="room-number">Room: ${entry.room_number}</div>
                            </div>
                        </td>`;
                } else {
                    html += `<td class="timetable-cell"></td>`;
                }
            });
            
            html += '</tr>';
        });
        
        $('#timetable_body').html(html);
    }
</script> 