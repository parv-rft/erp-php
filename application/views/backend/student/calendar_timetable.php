<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo get_phrase('class_calendar_timetable'); ?></h4>
                </div>
            </div>
            <div class="panel-body">
                <div class="row mb-3">
                    <div class="col-md-9">
                        <div class="alert alert-info">
                            <strong><?php echo get_phrase('class'); ?>:</strong> 
                            <?php 
                                $class_name = $this->db->get_where('class', array('class_id' => $default_class_id))->row()->name;
                                $section_name = $this->db->get_where('section', array('section_id' => $default_section_id))->row()->name;
                                echo $class_name . ' - ' . $section_name; 
                            ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <button type="button" class="btn btn-success btn-block" onclick="window.print()">
                                <i class="fa fa-print"></i> <?php echo get_phrase('print_timetable'); ?>
                            </button>
                        </div>
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
        padding: 12px 8px;
        color: #fff;
        background-color: #337ab7;
    }
    
    .time-col {
        width: 150px;
        background-color: #337ab7 !important;
        color: white;
    }
    
    .timetable-cell {
        height: 100px;
        padding: 5px !important;
        vertical-align: top;
        position: relative;
        background: #fff;
        border: 1px solid #ddd;
    }
    
    .time-slot-row {
        height: 100px;
    }
    
    .time-display {
        font-weight: bold;
        font-size: 13px;
        text-align: center;
        background-color: #f5f5f5;
        padding: 5px;
        border-radius: 3px;
        color: #000;
    }
    
    .subject-name {
        font-weight: bold;
        font-size: 13px;
        color: #333;
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
        var timetableData = [];
        var class_id = <?php echo $default_class_id; ?>;
        var section_id = <?php echo $default_section_id; ?>;
        
        // Load timetable on page load
        loadTimetable();
        
        // Function to load timetable data
        function loadTimetable() {
            $.ajax({
                url: '<?php echo site_url('student/get_class_timetable_data'); ?>',
                type: 'POST',
                data: {
                    class_id: class_id,
                    section_id: section_id
                },
                dataType: 'json',
                beforeSend: function() {
                    $('#timetable_body').html('<tr><td colspan="8" class="text-center"><i class="fa fa-spinner fa-spin"></i> <?php echo get_phrase('loading'); ?>...</td></tr>');
                },
                success: function(data) {
                    timetableData = data;
                    renderTimetable();
                },
                error: function(xhr) {
                    console.error('Error loading timetable:', xhr);
                    $('#timetable_body').html('<tr><td colspan="8" class="text-center text-danger"><?php echo get_phrase('error_loading_timetable'); ?></td></tr>');
                }
            });
        }
        
        // Function to render timetable
        function renderTimetable() {
            if (!timetableData.length) {
                $('#timetable_body').html('<tr><td colspan="8" class="text-center"><?php echo get_phrase('no_timetable_entries_found'); ?></td></tr>');
                return;
            }
            
            // Group entries by time slot
            const timeSlots = {};
            timetableData.forEach(function(entry) {
                const timeKey = `${entry.time_slot_start}-${entry.time_slot_end}`;
                if (!timeSlots[timeKey]) {
                    timeSlots[timeKey] = {
                        start: entry.time_slot_start,
                        end: entry.time_slot_end,
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
                timeSlots[timeKey].days[entry.day_of_week.toLowerCase()] = entry;
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
                                    <div class="subject-name">${entry.subject_name}</div>
                                    <div class="teacher-name">${entry.teacher_name}</div>
                                    <div class="room-number">Room: ${entry.room_number || 'N/A'}</div>
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
    });
</script> 