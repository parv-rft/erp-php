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
        
        .timetable-table { 
            margin-top: 20px;
            border-collapse: collapse;
            width: 100%;
        }
        
        .timetable-table thead th {
            background: linear-gradient(135deg, #2196F3, #1976D2);
            color: #fff !important;
            font-weight: 600;
            text-align: center;
            padding: 15px 8px;
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
            color: #fff !important;
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
        
        .time-display { 
            text-align: center;
            font-weight: bold;
            padding: 8px;
            color: #333 !important;
            background: #f1f3f4;
            border-radius: 4px;
            margin-bottom: 5px;
        }

        .class-slot { 
            height: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #E3F2FD, #BBDEFB);
            border-radius: 6px;
            transition: all 0.3s ease;
            position: relative;
            border: 1px solid #90CAF9;
        }

        .class-slot:hover {
            background: linear-gradient(135deg, #BBDEFB, #90CAF9);
            box-shadow: 0 3px 8px rgba(33, 150, 243, 0.2);
            transform: translateY(-2px);
        }

        .class-slot .actions {
            position: absolute;
            top: 5px;
            right: 5px;
            display: none;
        }

        .class-slot:hover .actions {
            display: block;
        }

        .class-slot .actions button {
            padding: 2px 5px;
            margin-left: 2px;
            font-size: 12px;
        }
        
        .subject-name { 
            font-weight: bold;
            color: #2196F3;
            margin-bottom: 5px;
        }
        
        .class-info { 
            color: #666;
            font-size: 13px;
            margin-bottom: 3px;
        }
        
        .room-info { 
            color: #999;
            font-size: 12px;
        }

        .empty-slot { 
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ccc;
            font-style: italic;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .empty-slot:hover {
            background: #e3f2fd;
            color: #2196F3;
        }
        
        .add-slot {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #bbb;
            font-size: 24px;
        }
        
        .empty-slot:hover .add-slot {
            color: #2196F3;
        }
        
        .btn-xs {
            padding: 1px 5px;
            font-size: 12px;
            line-height: 1.5;
            border-radius: 3px;
            margin-left: 3px;
        }
        
        /* Improved Modal Styles */
        .modal-confirm {
            color: #636363;
        }
        
        .modal-confirm .modal-content {
            padding: 20px;
            border-radius: 5px;
            border: none;
            text-align: center;
        }
        
        .modal-confirm .modal-header {
            border-bottom: none;   
            position: relative;
            text-align: center;
            margin: -20px -20px 0;
            border-radius: 5px 5px 0 0;
            padding: 35px;
        }
        
        .modal-confirm h4 {
            text-align: center;
            font-size: 26px;
            margin: 30px 0 -15px;
        }
        
        .modal-confirm .modal-body {
            color: #999;
        }
        
        .modal-confirm .modal-footer {
            border: none;
            text-align: center;
            border-radius: 5px;
            font-size: 13px;
            padding: 10px 15px 25px;
        }
        
        .modal-confirm .icon-box {
            width: 80px;
            height: 80px;
            margin: 0 auto;
            border-radius: 50%;
            z-index: 9;
            text-align: center;
            border: 3px solid #f15e5e;
        }
        
        .modal-confirm .icon-box i {
            color: #f15e5e;
            font-size: 46px;
            display: inline-block;
            margin-top: 13px;
        }
        
        .modal-confirm .btn-danger {
            background: #f15e5e;
            border-color: #f15e5e;
        }
        
        .modal-confirm .btn-danger:hover, 
        .modal-confirm .btn-danger:focus {
            background: #ee3535;
            border-color: #ee3535;
        }
        
        /* Center the modal */
        .modal {
            text-align: center;
            padding: 0!important;
        }
        
        .modal:before {
            content: '';
            display: inline-block;
            height: 100%;
            vertical-align: middle;
            margin-right: -4px;
        }
        
        .modal-dialog {
            display: inline-block;
            text-align: left;
            vertical-align: middle;
        }

        /* Add gradient styles for buttons and panel */
        .btn-gradient {
            background: linear-gradient(135deg, #2196F3, #1976D2);
            border: none;
            color: white;
            transition: all 0.3s ease;
        }
        
        .btn-gradient:hover {
            background: linear-gradient(135deg, #1976D2, #1565C0);
            color: white;
        }

        .panel-gradient {
            border-color: #1976D2;
        }

        .panel-gradient > .panel-heading {
            background: linear-gradient(135deg, #2196F3, #1976D2);
            border-color: #1976D2;
            color: white;
        }
    </style>
</head>
<body>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-body">
                    <div class="row" style="display: flex; align-items: center;">
                        <div class="col-md-3">
                            <select name="class_id" id="class_id" class="form-control select2" onchange="loadSections(this.value)" style="height: 40px;">
                                <option value=""><?php echo get_phrase('select_class'); ?></option>
                                <?php foreach($classes as $row): ?>
                                <option value="<?php echo $row['class_id']; ?>"><?php echo $row['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="section_id" id="section_id" class="form-control select2" style="height: 40px;">
                                <option value=""><?php echo get_phrase('select_section'); ?></option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-gradient" onclick="loadTimetable()" style="height: 40px; border: none;">
                                <?php echo get_phrase('load_timetable'); ?>
                            </button>
                        </div>
                        <div class="col-md-4" style="display: flex; justify-content: flex-end;">
                            <button type="button" class="btn btn-success" onclick="printTimetable()" style="margin-right: 10px; height: 40px; border: none;">
                                <i class="fa fa-print"></i> <?php echo get_phrase('print_timetable'); ?>
                            </button>
                            <button type="button" class="btn btn-gradient" onclick="showAddModal()" style="height: 40px; border: none;">
                                <i class="fa fa-plus"></i> <?php echo get_phrase('add_time_slot'); ?>
                            </button>
                        </div>
                    </div>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table-bordered timetable-table">
                            <thead>
                                <tr>
                                    <th class="time-col">Time Slot</th>
                                    <th>Monday</th>
                                    <th>Tuesday</th>
                                    <th>Wednesday</th>
                                    <th>Thursday</th>
                                    <th>Friday</th>
                                    <th>Saturday</th>
                                    <th>Sunday</th>
                                </tr>
                            </thead>
                            <tbody id="timetable-body">
                                <tr>
                                    <td colspan="8" class="text-center"><?php echo get_phrase('please_select_class_and_section'); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div class="modal fade" id="timetable-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><?php echo get_phrase('add_time_slot'); ?></h4>
                </div>
                <div class="modal-body">
                    <form id="timetable-form" class="form-horizontal">
                        <input type="hidden" id="timetable_id" name="timetable_id">
                        
                        <div class="form-group">
                            <label class="col-sm-3 control-label"><?php echo get_phrase('day'); ?></label>
                            <div class="col-sm-9">
                                <select name="day" id="day" class="form-control" required>
                                    <option value="monday">Monday</option>
                                    <option value="tuesday">Tuesday</option>
                                    <option value="wednesday">Wednesday</option>
                                    <option value="thursday">Thursday</option>
                                    <option value="friday">Friday</option>
                                    <option value="saturday">Saturday</option>
                                    <option value="sunday">Sunday</option>
                            </select>
                        </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label"><?php echo get_phrase('start_time'); ?></label>
                            <div class="col-sm-9">
                                <input type="time" name="start_time" id="start_time" class="form-control" required>
                        </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label"><?php echo get_phrase('end_time'); ?></label>
                            <div class="col-sm-9">
                                <input type="time" name="end_time" id="end_time" class="form-control" required>
                            </div>
                        </div>
                        
                                <div class="form-group">
                            <label class="col-sm-3 control-label"><?php echo get_phrase('subject'); ?></label>
                            <div class="col-sm-9">
                                <select name="subject_id" id="subject_id" class="form-control" required>
                                    <option value=""><?php echo get_phrase('select_subject'); ?></option>
                                    </select>
                            </div>
                        </div>

                                <div class="form-group">
                            <label class="col-sm-3 control-label"><?php echo get_phrase('teacher'); ?></label>
                            <div class="col-sm-9">
                                <select name="teacher_id" id="teacher_id" class="form-control" required>
                                    <option value=""><?php echo get_phrase('select_teacher'); ?></option>
                                    <?php foreach($teachers as $teacher): ?>
                                    <option value="<?php echo $teacher['teacher_id']; ?>"><?php echo $teacher['name']; ?></option>
                                    <?php endforeach; ?>
                                    </select>
                            </div>
                        </div>

                                <div class="form-group">
                            <label class="col-sm-3 control-label"><?php echo get_phrase('room_number'); ?></label>
                            <div class="col-sm-9">
                                <input type="text" name="room_number" id="room_number" class="form-control">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo get_phrase('close'); ?></button>
                    <button type="button" class="btn btn-primary" onclick="saveTimetable()"><?php echo get_phrase('save'); ?></button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add delete confirmation modal -->
    <div id="deleteModal" class="modal fade modal-confirm">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header flex-column">
                    <div class="icon-box">
                        <i class="fa fa-times"></i>
                    </div>
                    <h4 class="modal-title w-100">Are you sure?</h4>
                </div>
                <div class="modal-body">
                    <p>Do you really want to delete this timetable entry? This process cannot be undone.</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
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
            .panel-heading button, 
            .row:first-child,
            .modal,
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

            .editable-cell {
                box-shadow: none !important;
            }

            .actions {
                display: none !important;
            }

            .add-slot {
                display: none !important;
            }

            .empty-cell {
                border: 1px solid #ddd !important;
            }
        }
    </style>

    <script type="text/javascript">
    let selectedClassId = '';
    let selectedSectionId = '';
    let timetableData = [];

    $(document).ready(function() {
        // Initialize select2
        $('.select2').select2();
        
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
    });

    function showAddModal(day = null, start_time = null, end_time = null) {
        if (!selectedClassId) {
            toastr.error('<?php echo get_phrase('please_select_class_first'); ?>');
            return;
        }
        
        // Reset form
        $('#timetable-form')[0].reset();
        $('#timetable_id').val('');
        
        // Set values if provided
        if (day) $('#day').val(day);
        if (start_time) $('#start_time').val(start_time);
        if (end_time) $('#end_time').val(end_time);
        
        // Load subjects for selected class
        loadSubjects(selectedClassId);
        
        // Show modal
        $('#timetable-modal').modal('show');
    }

    function loadSections(classId) {
        if (!classId) return;
        
        selectedClassId = classId;
        
        $.ajax({
            url: '<?php echo base_url(); ?>admin/get_sections/' + classId,
            type: 'GET',
            success: function(response) {
                $('#section_id').html(response);
            },
            error: function(xhr, status, error) {
                console.error('Error loading sections:', error);
                toastr.error('<?php echo get_phrase('error_loading_sections'); ?>');
            }
        });
    }

    function loadSubjects(classId) {
        if (!classId) return;
        
        $.ajax({
            url: '<?php echo base_url(); ?>admin/get_subjects/' + classId,
            type: 'GET',
            success: function(response) {
                $('#subject_id').html(response);
            },
            error: function(xhr, status, error) {
                console.error('Error loading subjects:', error);
                toastr.error('<?php echo get_phrase('error_loading_subjects'); ?>');
            }
        });
    }

    function loadTimetable() {
        selectedClassId = $('#class_id').val();
        selectedSectionId = $('#section_id').val();
        
        if (!selectedClassId || !selectedSectionId) {
            toastr.error('<?php echo get_phrase('please_select_class_and_section'); ?>');
            return;
        }
        
        $.ajax({
            url: '<?php echo base_url(); ?>admin/get_calendar_timetable_data',
            type: 'POST',
            data: {
                class_id: selectedClassId,
                section_id: selectedSectionId
            },
            dataType: 'json',
            beforeSend: function() {
                $('#timetable-body').html('<tr><td colspan="8" class="text-center"><i class="fa fa-spinner fa-spin"></i> <?php echo get_phrase('loading'); ?>...</td></tr>');
            },
            success: function(response) {
                if (response.status === 'error') {
                    toastr.error(response.message);
                    $('#timetable-body').html('<tr><td colspan="8" class="text-center text-danger">' + response.message + '</td></tr>');
                    return;
                }
                
                timetableData = response;
                renderTimetable();
            },
            error: function(xhr, status, error) {
                console.error('Error loading timetable:', error);
                toastr.error('<?php echo get_phrase('error_loading_timetable'); ?>');
                $('#timetable-body').html('<tr><td colspan="8" class="text-center text-danger"><?php echo get_phrase('error_loading_timetable'); ?></td></tr>');
            }
        });
    }

    function printTimetable() {
        selectedClassId = $('#class_id').val();
        selectedSectionId = $('#section_id').val();
        
        if (!selectedClassId || !selectedSectionId) {
            toastr.error('<?php echo get_phrase('please_select_class_and_section'); ?>');
            return;
        }
        
        // Open print view in a new window
        var printUrl = '<?php echo base_url(); ?>admin/print_timetable/' + selectedClassId + '?section_id=' + selectedSectionId;
        window.open(printUrl, '_blank', 'width=1000,height=700');
    }

    function renderTimetable() {
        if (!timetableData.length) {
            $('#timetable-body').html(`
                <tr>
                    <td colspan="8" class="text-center">
                        <div style="padding: 40px;">
                            <i class="fa fa-calendar-o fa-3x text-muted"></i>
                            <p class="mt-3">No classes scheduled yet. Click the "Add New Class" button or any empty slot to add a class.</p>
                        </div>
                    </td>
                </tr>
            `);
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
                            ${formatTime(slot.start)}<br>to<br>${formatTime(slot.end)}
                        </div>
                    </td>`;
            
            ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'].forEach(function(day) {
                const entry = slot.days[day];
                if (entry) {
                    html += `
                        <td class="timetable-cell">
                            <div class="class-slot">
                                <div class="actions">
                                    <button class="btn btn-xs btn-info" onclick="editEntry(${entry.id})">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button class="btn btn-xs btn-danger" onclick="deleteEntry(${entry.id})">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                                <div class="subject-name">${entry.subject_name}</div>
                                <div class="class-info">Class ${entry.class_name} - ${entry.section_name}</div>
                                <div class="room-info">
                                    <i class="fa fa-map-marker"></i> Room: ${entry.room_number || 'N/A'}
                                </div>
                            </div>
                        </td>`;
                } else {
                    html += `
                        <td class="timetable-cell">
                            <div class="empty-slot" onclick="showAddModal('${day}', '${slot.start}', '${slot.end}')">
                                <div class="add-slot">
                                    <i class="fa fa-plus"></i>
                                </div>
                            </div>
                        </td>`;
                }
            });
            html += '</tr>';
        });
        
        $('#timetable-body').html(html);
    }

    function formatTime(time) {
        const [hours, minutes] = time.split(':');
        const hour = parseInt(hours);
        const ampm = hour >= 12 ? 'PM' : 'AM';
        const formattedHour = hour % 12 || 12;
        return `${formattedHour}:${minutes} ${ampm}`;
    }

    function saveTimetable() {
        const formData = {
            id: $('#timetable_id').val(),
            class_id: selectedClassId,
            section_id: selectedSectionId,
            day_of_week: $('#day').val(),
            time_slot_start: $('#start_time').val(),
            time_slot_end: $('#end_time').val(),
            subject_id: $('#subject_id').val(),
            teacher_id: $('#teacher_id').val(),
            room_number: $('#room_number').val()
        };
        
        // Validate required fields
        if (!formData.day_of_week || !formData.time_slot_start || !formData.time_slot_end || 
            !formData.subject_id || !formData.teacher_id) {
            toastr.error('<?php echo get_phrase('please_fill_all_required_fields'); ?>');
            return;
        }
        
                $.ajax({
            url: '<?php echo base_url(); ?>admin/save_calendar_timetable_entry',
                    type: 'POST',
            data: formData,
            dataType: 'json',
            beforeSend: function() {
                $('.modal-footer .btn-primary').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> <?php echo get_phrase('saving'); ?>...');
            },
                    success: function(response) {
                $('.modal-footer .btn-primary').prop('disabled', false).html('<?php echo get_phrase('save'); ?>');
                
                if (response.status === 'success') {
                    toastr.success(response.message);
                    $('#timetable-modal').modal('hide');
                    loadTimetable();
                            } else {
                    toastr.error(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                console.error('Error saving timetable:', error);
                toastr.error('<?php echo get_phrase('error_saving_timetable'); ?>');
                $('.modal-footer .btn-primary').prop('disabled', false).html('<?php echo get_phrase('save'); ?>');
            }
        });
    }

    function editEntry(id) {
        if (!timetableData || !timetableData.length) {
            toastr.error('No timetable data available');
            return;
        }

        const entry = timetableData.find(e => e.id == id);
        if (!entry) {
            toastr.error('Entry not found');
            return;
        }
        
        // Reset form and clear previous data
        $('#timetable-form')[0].reset();
        $('#timetable_id').val('');
        
        // Set form values
        $('#timetable_id').val(id);
        $('#day').val(entry.day_of_week.toLowerCase());
        $('#start_time').val(entry.time_slot_start);
        $('#end_time').val(entry.time_slot_end);
        $('#room_number').val(entry.room_number);
        
        // Load subjects for selected class
        loadSubjects(selectedClassId);
        
        // Set subject and teacher after a short delay
        setTimeout(() => {
            if ($('#subject_id').find(`option[value='${entry.subject_id}']`).length) {
                $('#subject_id').val(entry.subject_id).trigger('change');
            }
            if ($('#teacher_id').find(`option[value='${entry.teacher_id}']`).length) {
                $('#teacher_id').val(entry.teacher_id).trigger('change');
            }
        }, 500);
        
        // Update modal title and show
        $('#timetable-modal .modal-title').text('Edit Timetable Entry');
        $('#timetable-modal').modal('show');
    }

    let deleteEntryId = null;

    function deleteEntry(id) {
        if (!timetableData || !timetableData.length) {
            toastr.error('No timetable data available');
            return;
        }

        const entry = timetableData.find(e => e.id == id);
        if (!entry) {
            toastr.error('Entry not found');
            return;
        }

        deleteEntryId = id;
        $('#deleteModal').modal('show');
    }

    $('#confirmDelete').click(function() {
        if (!deleteEntryId) {
            $('#deleteModal').modal('hide');
            return;
        }
        
            $.ajax({
            url: '<?php echo base_url(); ?>admin/delete_calendar_timetable_entry',
                type: 'POST',
            data: { id: deleteEntryId },
            dataType: 'json',
            beforeSend: function() {
                $('#confirmDelete').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Deleting...');
            },
                success: function(response) {
                $('#deleteModal').modal('hide');
                $('#confirmDelete').prop('disabled', false).html('Delete');
                
                if (response.status === 'success') {
                    toastr.success(response.message);
                    loadTimetable();
                        } else {
                    toastr.error(response.message || 'Failed to delete entry');
                        }
                deleteEntryId = null;
                },
                error: function(xhr, status, error) {
                $('#deleteModal').modal('hide');
                $('#confirmDelete').prop('disabled', false).html('Delete');
                console.error('Error deleting entry:', error);
                toastr.error('Failed to delete entry. Please try again.');
                deleteEntryId = null;
            }
        });
    });
    </script> 
</body>
</html> 