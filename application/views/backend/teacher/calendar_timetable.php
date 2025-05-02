<?php
$teacher_id = $this->session->userdata('teacher_id');
?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4>
                        <i class="fa fa-calendar"></i> <?php echo get_phrase('my_timetable'); ?>
                    </h4>
                </div>
            </div>
            <div class="panel-body">
                <!-- Month Navigation -->
                <div class="row">
                    <div class="col-md-12 text-center mb-20">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default" id="prev-month">
                                <i class="fa fa-chevron-left"></i> <?php echo get_phrase('previous_month'); ?>
                            </button>
                            <button type="button" class="btn btn-primary" id="current-month-display">
                                <?php echo date('F Y'); ?>
                            </button>
                            <button type="button" class="btn btn-default" id="next-month">
                                <?php echo get_phrase('next_month'); ?> <i class="fa fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Filter Options -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="col-sm-4">
                                <select name="class_id" id="class_id" class="form-control select2">
                                    <option value=""><?php echo get_phrase('all_classes'); ?></option>
                                    <?php foreach($classes as $class): ?>
                                        <option value="<?php echo $class['class_id']; ?>"><?php echo $class['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <select name="section_id" id="section_id" class="form-control select2">
                                    <option value=""><?php echo get_phrase('all_sections'); ?></option>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <button class="btn btn-info" id="load-timetable">
                                    <i class="fa fa-search"></i> <?php echo get_phrase('load_timetable'); ?>
                                </button>
                                <button class="btn btn-success" id="print-timetable">
                            <i class="fa fa-print"></i> <?php echo get_phrase('print'); ?>
                        </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row" style="margin-top: 20px;">
                    <div class="col-md-12">
                        <!-- Weekly Timetable Calendar -->
                        <div id="weekly-timetable-container" class="timetable-container">
                            <table class="table table-bordered timetable-table">
                                <thead>
                                    <tr class="bg-primary">
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
                                <tbody id="timetable-body">
                                    <!-- Time slots will be dynamically generated -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Actions Footer -->
                <div class="row" style="margin-top: 20px;">
                    <div class="col-md-12 text-right">
                        <button class="btn btn-info" id="export-pdf">
                            <i class="fa fa-file-pdf-o"></i> <?php echo get_phrase('export_pdf'); ?>
                        </button>
                    </div>
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
    }
    
    .time-col {
        width: 150px;
        background-color: #337ab7 !important;
        color: white;
    }
    
    .timetable-cell {
        height: 100px;
        vertical-align: top;
        padding: 5px !important;
        background-color: #f9f9f9;
        border: 1px solid #ddd;
    }
    
    .timetable-cell.active {
        background-color: #d9edf7;
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
    
    .class-section {
        font-size: 11px;
        color: #888;
        margin-top: 5px;
    }
    
    .editable-cell {
        min-height: 90px;
        display: flex;
        flex-direction: column;
    }
    
    .mb-20 {
        margin-bottom: 20px;
}

@media print {
        .panel-heading, .form-group, .modal, .btn {
        display: none !important;
    }
    
        .panel {
            border: none !important;
            box-shadow: none !important;
        }
        
        .panel-body {
            padding: 0 !important;
    }
}
</style>

<script type="text/javascript">
$(document).ready(function() {
    // Initialize toastr
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

        let currentMonth = <?php echo date('n'); ?>;
        let currentYear = <?php echo date('Y'); ?>;
        let selectedClassId = '';
        let selectedSectionId = '';
        let teacherId = <?php echo $this->session->userdata('teacher_id'); ?>;
        let timeSlots = [];
        let timetableData = [];
        
        // Initialize select2 dropdowns
        $('.select2').select2();
        
        // Update month display
        function updateMonthDisplay() {
            const monthNames = [
                "January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"
            ];
            $('#current-month-display').text(monthNames[currentMonth - 1] + ' ' + currentYear);
        }
        
        // Initial month display update
        updateMonthDisplay();
        
        // Previous month button
        $('#prev-month').click(function() {
            currentMonth--;
            if (currentMonth < 1) {
                currentMonth = 12;
                currentYear--;
            }
            updateMonthDisplay();
            loadTimetable();
        });
        
        // Next month button
        $('#next-month').click(function() {
            currentMonth++;
            if (currentMonth > 12) {
                currentMonth = 1;
                currentYear++;
            }
            updateMonthDisplay();
            loadTimetable();
        });
        
        // Class change event - load sections
        $('#class_id').change(function() {
            const classId = $(this).val();
            if (classId) {
                $.ajax({
                    url: '<?php echo base_url(); ?>teacher/get_sections_for_calendar/' + classId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(sections) {
                        let options = '<option value=""><?php echo get_phrase('all_sections'); ?></option>';
                        sections.forEach(function(section) {
                            options += `<option value="${section.section_id}">${section.name}</option>`;
                        });
                        $('#section_id').html(options);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading sections:', error);
                        toastr.error('Failed to load sections. Please try again.');
                        $('#section_id').html('<option value=""><?php echo get_phrase('all_sections'); ?></option>');
                    }
                });
            } else {
                $('#section_id').html('<option value=""><?php echo get_phrase('all_sections'); ?></option>');
            }
        });
        
        // Load timetable button
        $('#load-timetable').click(function() {
            selectedClassId = $('#class_id').val();
            selectedSectionId = $('#section_id').val();
            loadTimetable();
        });
        
        // Function to load timetable data
        function loadTimetable() {
            $.ajax({
                url: '<?php echo base_url(); ?>teacher/get_teacher_timetable_data',
                type: 'POST',
                data: {
                    class_id: selectedClassId,
                    section_id: selectedSectionId,
                    month: currentMonth,
                    year: currentYear
                },
                dataType: 'json',
                beforeSend: function() {
                    $('#timetable-body').html('<tr><td colspan="8" class="text-center"><i class="fa fa-spinner fa-spin"></i> <?php echo get_phrase('loading'); ?>...</td></tr>');
                },
                success: function(response) {
                    if (response.status && response.status === 'error') {
                        // Show error message
                        toastr.error(response.message);
                        $('#timetable-body').html('<tr><td colspan="8" class="text-center text-danger">' + response.message + '</td></tr>');
                    } else {
                        // Successful response
                        timetableData = response;
                        renderTimetable();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading timetable:', error);
                    toastr.error('Failed to load timetable data. Please try again.');
                    $('#timetable-body').html('<tr><td colspan="8" class="text-center text-danger"><?php echo get_phrase('error_loading_timetable'); ?></td></tr>');
                }
            });
        }
        
        // Generate default time slots
        function generateDefaultTimeSlots() {
            return [
                { start: '07:00', end: '08:30' },
                { start: '08:30', end: '09:45' },
                { start: '09:45', end: '11:00' },
                { start: '11:00', end: '12:15' },
                { start: '12:15', end: '13:30' },
                { start: '13:30', end: '14:45' },
                { start: '14:45', end: '16:00' }
            ];
        }
        
        // Function to format time in 12-hour format
        function formatTime(time) {
            const [hours, minutes] = time.split(':');
            const hour = parseInt(hours);
            const ampm = hour >= 12 ? 'PM' : 'AM';
            const formattedHour = hour % 12 || 12;
            return `${formattedHour}:${minutes} ${ampm}`;
        }
        
        // Render timetable with data
        function renderTimetable() {
            // First, determine time slots
            if (timetableData.length > 0) {
                // Extract unique time slots from data
                const uniqueTimeSlots = [];
                const timeSlotMap = {};
                
                timetableData.forEach(entry => {
                    const key = `${entry.time_slot_start}-${entry.time_slot_end}`;
                    if (!timeSlotMap[key]) {
                        timeSlotMap[key] = true;
                        uniqueTimeSlots.push({
                            start: entry.time_slot_start,
                            end: entry.time_slot_end
                        });
                    }
                });
                
                // Sort time slots by start time
                uniqueTimeSlots.sort((a, b) => {
                    return a.start.localeCompare(b.start);
                });
                
                if (uniqueTimeSlots.length > 0) {
                    timeSlots = uniqueTimeSlots;
                } else {
                    timeSlots = generateDefaultTimeSlots();
                }
            } else {
                timeSlots = generateDefaultTimeSlots();
            }
            
            // Now render the timetable
            let html = '';
            const days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
            
            timeSlots.forEach((timeSlot, index) => {
                html += `<tr class="time-slot-row" data-index="${index}">`;
                html += `<td class="time-col time-display">${formatTime(timeSlot.start)} - ${formatTime(timeSlot.end)}</td>`;
                
                days.forEach(day => {
                    const entries = timetableData.filter(entry => 
                        entry.day_of_week === day && 
                        entry.time_slot_start === timeSlot.start && 
                        entry.time_slot_end === timeSlot.end
                    );
                    
                    const cellClass = entries.length > 0 ? 'timetable-cell active' : 'timetable-cell';
                    
                    html += `<td class="${cellClass}">`;
                    html += `<div class="editable-cell">`;
                    
                    if (entries.length > 0) {
                        entries.forEach(entry => {
                            html += `<div class="subject-name">${entry.subject_name || ''}</div>`;
                            
                            // Get class and section names
                            const classInfo = getClassAndSectionNames(entry.class_id, entry.section_id);
                            html += `<div class="class-section">${classInfo}</div>`;
                            
                            if (entry.room_number) {
                                html += `<div class="room-number">Room: ${entry.room_number}</div>`;
                            }
                            
                            if (entry !== entries[entries.length - 1]) {
                                html += `<hr style="margin: 5px 0;">`;
                            }
                        });
                    } else {
                        html += `<div class="text-center" style="padding-top: 30px;">
                                    <i class="fa fa-minus text-muted"></i>
                                </div>`;
                    }
                    
                    html += `</div>`;
                    html += `</td>`;
                });
                
                html += `</tr>`;
            });
            
            $('#timetable-body').html(html);
        }
        
        // Helper function to get class and section names
        function getClassAndSectionNames(classId, sectionId) {
            const classInfo = [];
            
            <?php foreach($classes as $class): ?>
            if (<?php echo $class['class_id']; ?> == classId) {
                classInfo.push('<?php echo $class['name']; ?>');
            }
            <?php endforeach; ?>
            
            // We'll need to load sections dynamically or include all sections in the page
            if (classInfo.length === 0) {
                return 'Class ' + classId + ', Section ' + sectionId;
            } else {
                return classInfo.join(', ') + ' (Section ' + sectionId + ')';
            }
        }
        
        // Print timetable
        $('#print-timetable').click(function() {
            window.print();
        });
        
        // Export as PDF
        $('#export-pdf').click(function() {
            alert('<?php echo get_phrase('pdf_export_functionality_coming_soon'); ?>');
            // TODO: Implement PDF export
        });
        
        // Initialize with teacher's timetable
        loadTimetable();
});
</script> 