<?php
$this->load->helper('form');
$page_title = get_phrase('calendar_timetable');
$breadcrumb = array(
    array('name' => get_phrase('dashboard'), 'url' => 'admin/dashboard'),
    array('name' => $page_title, 'url' => 'admin/calendar_timetable')
);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $page_title; ?></title>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap CSS and JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <!-- Toastr for notifications -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    
    <!-- FullCalendar -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
    
    <style>
        /* Include your existing CSS here */
        #calendar {
            background: white;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.12);
            margin-top: 20px;
        }
        
        .mb-3 {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="panel-title">
                        <h4>
                            <i class="fa fa-calendar"></i> <?php echo get_phrase('class_timetable_calendar'); ?>
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
                                <div class="col-sm-3">
                                    <select name="class_id" id="class_id" class="form-control select2" required>
                                        <option value=""><?php echo get_phrase('select_class'); ?></option>
                                        <?php foreach($classes as $class): ?>
                                            <option value="<?php echo $class['class_id']; ?>"><?php echo $class['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <select name="section_id" id="section_id" class="form-control select2" required>
                                        <option value=""><?php echo get_phrase('select_section'); ?></option>
                            </select>
                        </div>
                                <div class="col-sm-3">
                                    <select name="teacher_id" id="teacher_id" class="form-control select2">
                                <option value=""><?php echo get_phrase('all_teachers'); ?></option>
                                        <?php foreach($teachers as $teacher): ?>
                                            <option value="<?php echo $teacher['teacher_id']; ?>"><?php echo $teacher['name']; ?></option>
                                        <?php endforeach; ?>
                            </select>
                        </div>
                                <div class="col-sm-3">
                                    <button class="btn btn-info" id="load-timetable">
                                        <i class="fa fa-search"></i> <?php echo get_phrase('load_timetable'); ?>
                            </button>
                                    <button class="btn btn-success" id="btn-view-mode" data-mode="admin">
                                        <i class="fa fa-eye"></i> <span id="view-mode-text"><?php echo get_phrase('admin_view'); ?></span>
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
                            <button class="btn btn-primary" id="add-time-slot">
                                <i class="fa fa-plus"></i> <?php echo get_phrase('add_time_slot'); ?>
                            </button>
                            <button class="btn btn-success" id="print-timetable">
                                <i class="fa fa-print"></i> <?php echo get_phrase('print'); ?>
                            </button>
                            <button class="btn btn-info" id="export-pdf">
                                <i class="fa fa-file-pdf-o"></i> <?php echo get_phrase('export_pdf'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Time Slot Add Modal -->
    <div class="modal fade" id="time-slot-modal" tabindex="-1" role="dialog" aria-labelledby="timeSlotModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="timeSlotModalLabel"><?php echo get_phrase('add_time_slot'); ?></h4>
                </div>
                <div class="modal-body">
                    <form id="time-slot-form">
                        <div class="form-group">
                            <label for="time-start"><?php echo get_phrase('start_time'); ?></label>
                            <input type="time" class="form-control" id="time-start" name="time_start" required>
                        </div>
                        <div class="form-group">
                            <label for="time-end"><?php echo get_phrase('end_time'); ?></label>
                            <input type="time" class="form-control" id="time-end" name="time_end" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo get_phrase('close'); ?></button>
                    <button type="button" class="btn btn-primary" id="save-time-slot"><?php echo get_phrase('save'); ?></button>
                </div>
            </div>
        </div>
    </div>

    <!-- Subject Teacher Add Modal -->
    <div class="modal fade" id="subject-teacher-modal" tabindex="-1" role="dialog" aria-labelledby="subjectTeacherModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="subjectTeacherModalLabel"><?php echo get_phrase('add_subject_teacher'); ?></h4>
                </div>
                <div class="modal-body">
                    <form id="subject-teacher-form">
                        <input type="hidden" id="day-of-week" name="day_of_week">
                        <input type="hidden" id="time-slot-id" name="time_slot_id">
                        <input type="hidden" id="timetable-id" name="timetable_id">
                        <input type="hidden" id="edit-mode" name="edit_mode" value="false">
                        
                                <div class="form-group">
                            <label for="modal-subject-id"><?php echo get_phrase('subject'); ?></label>
                            <select class="form-control select2" id="modal-subject-id" name="subject_id" required>
                                <option value=""><?php echo get_phrase('select_subject'); ?></option>
                                <?php foreach($subjects as $subject): ?>
                                    <option value="<?php echo $subject['subject_id']; ?>"><?php echo $subject['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                                <div class="form-group">
                            <label for="modal-teacher-id"><?php echo get_phrase('teacher'); ?></label>
                            <select class="form-control select2" id="modal-teacher-id" name="teacher_id">
                                <option value=""><?php echo get_phrase('select_teacher'); ?></option>
                                <?php foreach($teachers as $teacher): ?>
                                    <option value="<?php echo $teacher['teacher_id']; ?>"><?php echo $teacher['name']; ?></option>
                                <?php endforeach; ?>
                                    </select>
                        </div>

                                <div class="form-group">
                            <label for="room-number"><?php echo get_phrase('room_number'); ?></label>
                            <input type="text" class="form-control" id="room-number" name="room_number">
                        </div>

                                <div class="form-group">
                            <label for="notes"><?php echo get_phrase('notes'); ?></label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo get_phrase('close'); ?></button>
                    <button type="button" class="btn btn-danger" id="delete-subject-teacher" style="display:none;"><?php echo get_phrase('delete'); ?></button>
                    <button type="button" class="btn btn-primary" id="save-subject-teacher"><?php echo get_phrase('save'); ?></button>
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
            transition: all 0.2s ease;
        }
        
        .timetable-cell:hover {
            background-color: #e9f4fe;
            cursor: pointer;
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

            let currentMonth = <?php echo $current_month; ?>;
            let currentYear = <?php echo $current_year; ?>;
            let selectedClassId = '';
            let selectedSectionId = '';
            let selectedTeacherId = '';
            let timeSlots = [];
            let timetableData = [];
            let viewMode = 'admin'; // 'admin' or 'teacher'
            
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
                if (selectedClassId && selectedSectionId) {
                    loadTimetable();
                }
            });
            
            // Next month button
            $('#next-month').click(function() {
                currentMonth++;
                if (currentMonth > 12) {
                    currentMonth = 1;
                    currentYear++;
                }
                updateMonthDisplay();
                if (selectedClassId && selectedSectionId) {
                    loadTimetable();
                }
            });
            
            // Toggle view mode (Admin/Teacher)
            $('#btn-view-mode').click(function() {
                if (viewMode === 'admin') {
                    viewMode = 'teacher';
                    $(this).data('mode', 'teacher');
                    $('#view-mode-text').text('<?php echo get_phrase('teacher_view'); ?>');
                    $(this).removeClass('btn-success').addClass('btn-warning');
                } else {
                    viewMode = 'admin';
                    $(this).data('mode', 'admin');
                    $('#view-mode-text').text('<?php echo get_phrase('admin_view'); ?>');
                    $(this).removeClass('btn-warning').addClass('btn-success');
                }
                if (selectedClassId && selectedSectionId) {
                    loadTimetable();
                }
            });
            
            // Class change event - load sections
            $('#class_id').change(function() {
                const classId = $(this).val();
                if (classId) {
                    $.ajax({
                        url: '<?php echo base_url(); ?>admin/get_sections/' + classId,
                        type: 'GET',
                        success: function(response) {
                            const sections = JSON.parse(response);
                            let options = '<option value=""><?php echo get_phrase('select_section'); ?></option>';
                            sections.forEach(function(section) {
                                options += `<option value="${section.section_id}">${section.name}</option>`;
                            });
                            $('#section_id').html(options);
                        }
                    });
                } else {
                    $('#section_id').html('<option value=""><?php echo get_phrase('select_section'); ?></option>');
                }
            });
            
            // Load timetable button
            $('#load-timetable').click(function() {
                selectedClassId = $('#class_id').val();
                selectedSectionId = $('#section_id').val();
                selectedTeacherId = $('#teacher_id').val();
                
                if (selectedClassId && selectedSectionId) {
                    loadTimetable();
                } else {
                    alert('<?php echo get_phrase('please_select_class_and_section'); ?>');
                }
            });
            
            // Function to load timetable data
            function loadTimetable() {
                $.ajax({
                    url: '<?php echo base_url(); ?>admin/get_calendar_timetable_data',
                    type: 'POST',
                    data: {
                        class_id: selectedClassId,
                        section_id: selectedSectionId,
                        teacher_id: selectedTeacherId,
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
                        const cellData = timetableData.find(entry => 
                            entry.day_of_week === day && 
                            entry.time_slot_start === timeSlot.start && 
                            entry.time_slot_end === timeSlot.end
                        );
                        
                        const cellClass = cellData ? 'timetable-cell active' : 'timetable-cell';
                        
                        html += `<td class="${cellClass}" data-day="${day}" data-time-start="${timeSlot.start}" data-time-end="${timeSlot.end}">`;
                        html += `<div class="editable-cell">`;
                        
                        if (cellData) {
                            html += `<div class="subject-name">${cellData.subject_name || ''}</div>`;
                            html += `<div class="teacher-name">${cellData.teacher_name || ''}</div>`;
                            if (cellData.room_number) {
                                html += `<div class="room-number">Room: ${cellData.room_number}</div>`;
                            }
                            html += `<input type="hidden" class="timetable-id" value="${cellData.id}">`;
                        } else {
                            html += `<div class="text-center" style="padding-top: 30px;">
                                        <i class="fa fa-plus-circle text-muted"></i>
                                    </div>`;
                        }
                        
                        html += `</div>`;
                        html += `</td>`;
                    });
                    
                    html += `</tr>`;
                });
                
                $('#timetable-body').html(html);
                
                // Add cell click event
                $('.timetable-cell').click(function() {
                    const day = $(this).data('day');
                    const timeStart = $(this).data('time-start');
                    const timeEnd = $(this).data('time-end');
                    const timetableId = $(this).find('.timetable-id').val();
                    
                    // Reset form
                    $('#subject-teacher-form')[0].reset();
                    $('#day-of-week').val(day);
                    $('#time-slot-id').val(`${timeStart}-${timeEnd}`);
                    
                    if (timetableId) {
                        // Edit mode
                        $('#timetable-id').val(timetableId);
                        $('#edit-mode').val('true');
                        $('#subjectTeacherModalLabel').text('<?php echo get_phrase('edit_subject_teacher'); ?>');
                        $('#delete-subject-teacher').show();
                        
                        // Find entry data
                        const entry = timetableData.find(item => item.id == timetableId);
                        if (entry) {
                            $('#modal-subject-id').val(entry.subject_id).trigger('change');
                            $('#modal-teacher-id').val(entry.teacher_id).trigger('change');
                            $('#room-number').val(entry.room_number);
                            $('#notes').val(entry.notes);
                        }
                    } else {
                        // Add mode
                        $('#timetable-id').val('');
                        $('#edit-mode').val('false');
                        $('#subjectTeacherModalLabel').text('<?php echo get_phrase('add_subject_teacher'); ?>');
                        $('#delete-subject-teacher').hide();
                    }
                    
                    $('#subject-teacher-modal').modal('show');
                });
            }
            
            // Add time slot button
            $('#add-time-slot').click(function() {
                $('#time-slot-form')[0].reset();
                $('#time-slot-modal').modal('show');
            });
            
            // Save time slot
            $('#save-time-slot').click(function() {
                const timeStart = $('#time-start').val();
                const timeEnd = $('#time-end').val();
                
                if (!timeStart || !timeEnd) {
                    alert('<?php echo get_phrase('please_enter_both_start_and_end_times'); ?>');
                    return;
                }
                
                if (timeStart >= timeEnd) {
                    alert('<?php echo get_phrase('end_time_must_be_after_start_time'); ?>');
                    return;
                }
                
                // Check for overlap
                const overlap = timeSlots.some(slot => {
                    return (timeStart < slot.end && timeEnd > slot.start);
                });
                
                if (overlap) {
                    alert('<?php echo get_phrase('time_slot_overlaps_with_existing_slot'); ?>');
                    return;
                }
                
                // Add new time slot
                timeSlots.push({ start: timeStart, end: timeEnd });
                
                // Sort time slots by start time
                timeSlots.sort((a, b) => {
                    return a.start.localeCompare(b.start);
                });
                
                // Re-render timetable
                renderTimetable();
                
                // Close modal
                $('#time-slot-modal').modal('hide');
            });
            
            // Save subject teacher
            $('#save-subject-teacher').click(function() {
                const subjectId = $('#modal-subject-id').val();
                const teacherId = $('#modal-teacher-id').val();
                const dayOfWeek = $('#day-of-week').val();
                const timeSlotId = $('#time-slot-id').val();
                const timetableId = $('#timetable-id').val();
                const editMode = $('#edit-mode').val() === 'true';
                const roomNumber = $('#room-number').val();
                const notes = $('#notes').val();
                
                if (!subjectId) {
                    toastr.error('<?php echo get_phrase('please_select_a_subject'); ?>');
                    return;
                }
                
                const [timeStart, timeEnd] = timeSlotId.split('-');
                
                const data = {
                    id: timetableId,
                    class_id: selectedClassId,
                    section_id: selectedSectionId,
                    subject_id: subjectId,
                    teacher_id: teacherId,
                    day_of_week: dayOfWeek,
                    time_slot_start: timeStart,
                    time_slot_end: timeEnd,
                    month: currentMonth,
                    year: currentYear,
                    room_number: roomNumber,
                    notes: notes
                };
                
            $.ajax({
                    url: '<?php echo base_url(); ?>admin/save_calendar_timetable_entry',
                type: 'POST',
                    data: data,
                    dataType: 'json',
                    beforeSend: function() {
                        $('#save-subject-teacher').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> <?php echo get_phrase('saving'); ?>...');
                    },
                success: function(response) {
                        $('#save-subject-teacher').prop('disabled', false).html('<?php echo get_phrase('save'); ?>');
                        
                        if (response.status === 'success') {
                            toastr.success(response.message);
                            // Reload timetable
                            loadTimetable();
                            // Close modal
                            $('#subject-teacher-modal').modal('hide');
                        } else {
                            toastr.error(response.message || '<?php echo get_phrase('an_error_occurred'); ?>');
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#save-subject-teacher').prop('disabled', false).html('<?php echo get_phrase('save'); ?>');
                        console.error('Error saving timetable entry:', error);
                        toastr.error('<?php echo get_phrase('an_error_occurred'); ?>');
                    }
                });
            });
            
            // Delete subject teacher
            $('#delete-subject-teacher').click(function() {
                const timetableId = $('#timetable-id').val();
                
                if (!timetableId) {
                    return;
                }
                
                if (!confirm('<?php echo get_phrase('are_you_sure_you_want_to_delete_this_entry'); ?>?')) {
                    return;
                }
                
                $.ajax({
                    url: '<?php echo base_url(); ?>admin/delete_calendar_timetable_entry',
                    type: 'POST',
                    data: { id: timetableId },
                    dataType: 'json',
                    beforeSend: function() {
                        $('#delete-subject-teacher').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> <?php echo get_phrase('deleting'); ?>...');
                    },
                    success: function(response) {
                        $('#delete-subject-teacher').prop('disabled', false).html('<?php echo get_phrase('delete'); ?>');
                        
                        if (response.status === 'success') {
                            toastr.success(response.message);
                            // Reload timetable
                            loadTimetable();
                            // Close modal
                            $('#subject-teacher-modal').modal('hide');
                        } else {
                            toastr.error(response.message || '<?php echo get_phrase('an_error_occurred'); ?>');
                    }
                },
                error: function(xhr, status, error) {
                        $('#delete-subject-teacher').prop('disabled', false).html('<?php echo get_phrase('delete'); ?>');
                        console.error('Error deleting timetable entry:', error);
                        toastr.error('<?php echo get_phrase('an_error_occurred'); ?>');
                    }
                });
            });
            
            // Print timetable
            $('#print-timetable').click(function() {
                window.print();
            });
            
            // Export as PDF
            $('#export-pdf').click(function() {
                alert('<?php echo get_phrase('pdf_export_functionality_coming_soon'); ?>');
                // TODO: Implement PDF export
            });
            
            // Initialize with default time slots
            renderTimetable();
    });
    </script> 
</body>
</html> 