<?php
$teacher_id = $this->session->userdata('teacher_id');
$teacher_name = $this->db->get_where('teacher', array('teacher_id' => $teacher_id))->row()->name;
?>

<!-- Include CSS files -->
<link rel="stylesheet" href="<?php echo base_url('assets/js/toastr/toastr.min.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/css/fullcalendar.min.css'); ?>">

<!-- Custom CSS -->
    <style>
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
        
    @media print {
        .panel-heading button, 
        .alert {
            display: none !important;
        }
        
        .panel {
            border: none !important;
            box-shadow: none !important;
        }
        
        .panel-body {
            padding: 0 !important;
        }

        .class-slot {
            box-shadow: none !important;
            border: 1px solid #ddd;
        }

        .actions {
            display: none !important;
        }
    }

    /* Mobile Responsiveness */
    @media (max-width: 768px) {
        .timetable-cell {
            height: auto;
            min-height: 80px;
        }
        
        .time-col {
            width: 100px;
        }
        
        .subject-name {
            font-size: 12px;
        }
        
        .class-info,
        .room-info {
            font-size: 11px;
        }
    }

    /* Modal Styles */
        .modal-confirm {
            color: #636363;
        }
        
        .modal-confirm .modal-content {
            padding: 20px;
            border-radius: 5px;
            border: none;
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
        
    /* Modal Scrolling Styles */
    .modal-body {
        max-height: calc(100vh - 200px);
        overflow-y: auto;
    }

    .modal-dialog {
        margin: 30px auto;
    }

    /* Add loading overlay */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
            height: 100%;
        background: rgba(255, 255, 255, 0.8);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .loading-overlay.active {
        display: flex;
    }

    .loading-spinner {
        text-align: center;
        }
    </style>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="panel-title">
                    <h4>
                        <i class="fa fa-calendar"></i> My Teaching Schedule
                        <div class="pull-right">
                            <button class="btn btn-success btn-sm" onclick="window.print()">
                                <i class="fa fa-print"></i> Print Schedule
                        </button>
                            <button class="btn btn-info btn-sm" onclick="showAddTimetableModal()">
                                <i class="fa fa-plus"></i> Add New Class
                        </button>
                        </div>
                    </h4>
                    </div>
                </div>
                <div class="panel-body">
                <div class="alert alert-info">
                    <strong>Welcome, <?php echo $teacher_name; ?>!</strong> 
                    Below is your teaching schedule for the current academic period.
                        </div>

                <div id="status_message"></div>

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
                                <td colspan="8" class="text-center">
                                    <i class="fa fa-spinner fa-spin"></i> Loading your schedule...
                                </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Add Timetable Modal -->
<div class="modal fade" id="addTimetableModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add New Class</h4>
                </div>
            <form id="addTimetableForm" onsubmit="return saveTimetable(event)">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Class</label>
                        <select name="class_id" class="form-control" required onchange="loadSections(this.value)">
                            <option value="">Select Class</option>
                            <?php
                            $classes = $this->db->get('class')->result_array();
                            foreach($classes as $class):
                            ?>
                            <option value="<?php echo $class['class_id']; ?>"><?php echo $class['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                        
                        <div class="form-group">
                        <label>Section</label>
                        <select name="section_id" class="form-control" required onchange="loadSubjects()">
                            <option value="">Select Section</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Subject</label>
                        <select name="subject_id" class="form-control" required>
                            <option value="">Select Subject</option>
                        </select>
                    </div>
                        
                        <div class="form-group">
                        <label>Day</label>
                        <select name="day_of_week" class="form-control" required>
                            <option value="">Select Day</option>
                                    <option value="monday">Monday</option>
                                    <option value="tuesday">Tuesday</option>
                                    <option value="wednesday">Wednesday</option>
                                    <option value="thursday">Thursday</option>
                                    <option value="friday">Friday</option>
                                    <option value="saturday">Saturday</option>
                                    <option value="sunday">Sunday</option>
                            </select>
                        </div>

                    <div class="form-group">
                        <label>Start Time</label>
                        <input type="time" name="time_slot_start" class="form-control" required>
                        </div>

                        <div class="form-group">
                        <label>End Time</label>
                        <input type="time" name="time_slot_end" class="form-control" required>
                        </div>

                        <div class="form-group">
                        <label>Room Number</label>
                        <input type="text" name="room_number" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
                            </div>
                        </div>
                        
<!-- Edit Timetable Modal -->
<div class="modal fade" id="editTimetableModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Class</h4>
            </div>
            <form id="editTimetableForm" onsubmit="return updateTimetable(event)">
                <input type="hidden" name="timetable_id" id="edit_timetable_id">
                <div class="modal-body">
                                <div class="form-group">
                        <label>Class</label>
                        <select name="class_id" id="edit_class_id" class="form-control" required onchange="loadSections(this.value, 'edit')">
                            <option value="">Select Class</option>
                            <?php foreach($classes as $class): ?>
                            <option value="<?php echo $class['class_id']; ?>"><?php echo $class['name']; ?></option>
                            <?php endforeach; ?>
                                    </select>
                            </div>
                        
                                <div class="form-group">
                        <label>Section</label>
                        <select name="section_id" id="edit_section_id" class="form-control" required onchange="loadSubjects('edit')">
                            <option value="">Select Section</option>
                                    </select>
                        </div>

                                <div class="form-group">
                        <label>Subject</label>
                        <select name="subject_id" id="edit_subject_id" class="form-control" required>
                            <option value="">Select Subject</option>
                                    </select>
                            </div>

                                <div class="form-group">
                        <label>Day</label>
                        <select name="day_of_week" id="edit_day_of_week" class="form-control" required>
                            <option value="">Select Day</option>
                            <option value="monday">Monday</option>
                            <option value="tuesday">Tuesday</option>
                            <option value="wednesday">Wednesday</option>
                            <option value="thursday">Thursday</option>
                            <option value="friday">Friday</option>
                            <option value="saturday">Saturday</option>
                            <option value="sunday">Sunday</option>
                                    </select>
                        </div>

                                <div class="form-group">
                        <label>Start Time</label>
                        <input type="time" name="time_slot_start" id="edit_time_slot_start" class="form-control" required>
                            </div>

                                <div class="form-group">
                        <label>End Time</label>
                        <input type="time" name="time_slot_end" id="edit_time_slot_end" class="form-control" required>
                        </div>

                    <div class="form-group">
                        <label>Room Number</label>
                        <input type="text" name="room_number" id="edit_room_number" class="form-control" required>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
            </div>
        </div>
    </div>

<!-- Delete Confirmation Modal -->
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

<!-- Add Loading Overlay -->
<div class="loading-overlay">
    <div class="loading-spinner">
        <i class="fa fa-spinner fa-spin fa-3x"></i>
        <p>Processing...</p>
    </div>
</div>

<!-- Include JavaScript files -->
<script src="<?php echo base_url('assets/js/jquery-ui/jquery-ui.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/toastr/toastr.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/fullcalendar/fullcalendar.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/moment.min.js'); ?>"></script>

    <script type="text/javascript">
    $(document).ready(function() {
        // Initialize toastr
    if (typeof toastr !== 'undefined') {
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
    }

    loadMyTimetable();
});

// Helper function for showing messages when toastr is not available
function showMessage(type, message) {
    if (typeof toastr !== 'undefined') {
        toastr[type](message);
    } else {
        const alertClass = type === 'success' ? 'alert-success' : 
                          type === 'error' ? 'alert-danger' : 
                          type === 'warning' ? 'alert-warning' : 'alert-info';
        
        $('#status_message').html(`
            <div class="alert ${alertClass} alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                ${message}
            </div>
        `);
    }
}

function loadMyTimetable() {
        $.ajax({
        url: '<?php echo base_url(); ?>teacher/get_my_timetable_data',
            type: 'POST',
            data: {
            teacher_id: '<?php echo $teacher_id; ?>'
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'error') {
                showMessage('error', response.message);
                showNoClassesMessage();
                    return;
                }
            if (!response.length) {
                showNoClassesMessage();
                return;
            }
            renderTimetable(response);
            },
            error: function(xhr, status, error) {
                console.error('Error loading timetable:', error);
            showMessage('error', 'Failed to load your schedule. Please refresh the page or contact support.');
            showNoClassesMessage();
            }
        });
    }

function showNoClassesMessage() {
    $('#timetable-body').html(`
        <tr>
            <td colspan="8" class="text-center">
                <div style="padding: 20px;">
                    <i class="fa fa-info-circle"></i> No classes are currently assigned to you.
                </div>
            </td>
        </tr>
    `);
}

function renderTimetable(timetableData) {
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
        <tr>
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
                    <div class="class-slot" data-entry='${JSON.stringify(entry)}'>
                                <div class="actions">
                            <button class="btn btn-xs btn-primary" onclick="editTimetable(this)">
                                        <i class="fa fa-edit"></i>
                                    </button>
                            <button class="btn btn-xs btn-danger" onclick="deleteTimetable(${entry.id})">
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
                    <div class="empty-slot" onclick="showAddTimetableModal('${day}', '${slot.start}', '${slot.end}')">
                            <div class="add-slot">
                                <i class="fa fa-plus"></i>
                            </div>
                        </div>
                        </td>`;
                }
            });
            html += '</tr>';
        });
        
    $('#timetable-body').html(html || `
        <tr>
            <td colspan="8" class="text-center">
                <div style="padding: 40px;">
                    <i class="fa fa-calendar-o fa-3x text-muted"></i>
                    <p class="mt-3">No classes scheduled yet. Click the "Add New Class" button or any empty slot to add a class.</p>
                </div>
            </td>
        </tr>
    `);
}

function formatTime(time) {
    const [hours, minutes] = time.split(':');
    const hour = parseInt(hours);
    const ampm = hour >= 12 ? 'PM' : 'AM';
    const formattedHour = hour % 12 || 12;
    return `${formattedHour}:${minutes} ${ampm}`;
}

function showAddTimetableModal(day = null, start_time = null, end_time = null) {
    // Reset form
    $('#addTimetableForm')[0].reset();
    
    // Set values if provided
    if (day) $('select[name="day_of_week"]').val(day);
    if (start_time) $('input[name="time_slot_start"]').val(start_time);
    if (end_time) $('input[name="time_slot_end"]').val(end_time);
    
    $('#addTimetableModal').modal('show');
}

function editTimetable(button) {
    const entry = JSON.parse($(button).closest('.class-slot').attr('data-entry'));
    
    $('#edit_timetable_id').val(entry.id);
    $('#edit_class_id').val(entry.class_id);
    
    // Load sections for the selected class
    loadSections(entry.class_id, 'edit');
    
    // Wait for sections to load, then set section and load subjects
    setTimeout(() => {
        $('#edit_section_id').val(entry.section_id);
        
        // Load subjects for the selected class and section
        loadSubjects('edit');
        
        // Wait for subjects to load, then set remaining values
        setTimeout(() => {
            $('#edit_subject_id').val(entry.subject_id);
            $('#edit_day_of_week').val(entry.day_of_week.toLowerCase());
            $('#edit_time_slot_start').val(entry.time_slot_start);
            $('#edit_time_slot_end').val(entry.time_slot_end);
            $('#edit_room_number').val(entry.room_number);
            
            $('#editTimetableModal').modal('show');
        }, 500);
    }, 500);
}

function loadSections(class_id, mode = 'add') {
    if (!class_id) return;
    
    $.ajax({
        url: '<?php echo base_url(); ?>teacher/get_sections',
        type: 'POST',
        data: { class_id: class_id },
        dataType: 'json',
        success: function(response) {
            let html = '<option value="">Select Section</option>';
            response.forEach(function(section) {
                html += `<option value="${section.section_id}">${section.name}</option>`;
            });
            if (mode === 'edit') {
                $('#edit_section_id').html(html);
            } else {
                $('select[name="section_id"]').html(html);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error loading sections:', error);
            showMessage('error', 'Failed to load sections');
        }
    });
}

function loadSubjects(mode = 'add') {
    const prefix = mode === 'edit' ? 'edit_' : '';
    const class_id = $(`#${prefix}class_id`).val();
    const section_id = $(`#${prefix}section_id`).val();
    
    if (!class_id || !section_id) return;
    
    $.ajax({
        url: '<?php echo base_url(); ?>teacher/get_subjects',
        type: 'POST',
        data: { 
            class_id: class_id,
            section_id: section_id
        },
        dataType: 'json',
        success: function(response) {
            let html = '<option value="">Select Subject</option>';
            response.forEach(function(subject) {
                html += `<option value="${subject.subject_id}">${subject.name}</option>`;
            });
            if (mode === 'edit') {
                $('#edit_subject_id').html(html);
            } else {
                $('select[name="subject_id"]').html(html);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error loading subjects:', error);
            showMessage('error', 'Failed to load subjects');
        }
    });
}

function saveTimetable(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData);
    
    // Add current month and year
    data.month = new Date().getMonth() + 1;
    data.year = new Date().getFullYear();
    data.teacher_id = '<?php echo $teacher_id; ?>';
        
        // Validate required fields
    const requiredFields = ['class_id', 'section_id', 'subject_id', 'day_of_week', 'time_slot_start', 'time_slot_end'];
    for (const field of requiredFields) {
        if (!data[field]) {
            showMessage('error', `Please fill in the ${field.replace('_', ' ')}`);
            return false;
        }
    }
    
    // Validate time slots
    const startTime = new Date(`2000-01-01T${data.time_slot_start}`);
    const endTime = new Date(`2000-01-01T${data.time_slot_end}`);
    if (endTime <= startTime) {
        showMessage('error', 'End time must be after start time');
        return false;
    }
    
    const submitBtn = $('#addTimetableModal button[type="submit"]');
    const originalBtnText = submitBtn.html();
        
                $.ajax({
        url: '<?php echo base_url(); ?>teacher/add_timetable_entry',
                    type: 'POST',
        data: data,
            dataType: 'json',
            beforeSend: function() {
            submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
            },
                    success: function(response) {
            submitBtn.prop('disabled', false).html(originalBtnText);
                
                if (response.status === 'success') {
                showMessage('success', response.message || 'Class added successfully');
                $('#addTimetableModal').modal('hide');
                loadMyTimetable();
                            } else {
                showMessage('error', response.message || 'Failed to save timetable entry');
                        }
                    },
                    error: function(xhr, status, error) {
            submitBtn.prop('disabled', false).html(originalBtnText);
                console.error('Error saving timetable:', error);
            showMessage('error', 'Failed to save timetable entry. Please try again.');
        }
    });
    
    return false;
}

function updateTimetable(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData);
    
    // Add current month and year
    data.month = new Date().getMonth() + 1;
    data.year = new Date().getFullYear();
    data.teacher_id = '<?php echo $teacher_id; ?>';
    
    // Validate required fields
    const requiredFields = ['class_id', 'section_id', 'subject_id', 'day_of_week', 'time_slot_start', 'time_slot_end'];
    for (const field of requiredFields) {
        if (!data[field]) {
            showMessage('error', `Please fill in the ${field.replace('_', ' ')}`);
            return false;
        }
    }
    
    // Validate time slots - convert to 24-hour format for comparison
    const startTime = data.time_slot_start;
    const endTime = data.time_slot_end;
    
    if (startTime && endTime) {
        const start = new Date(`2000-01-01T${startTime}`);
        const end = new Date(`2000-01-01T${endTime}`);
        if (end <= start) {
            showMessage('error', 'End time must be after start time');
            return false;
        }
    }
    
    const submitBtn = $('#editTimetableModal button[type="submit"]');
    const originalBtnText = submitBtn.html();
    
    $.ajax({
        url: '<?php echo base_url(); ?>teacher/update_timetable_entry',
        type: 'POST',
        data: data,
        dataType: 'json',
        beforeSend: function() {
            submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Updating...');
            $('.loading-overlay').addClass('active');
        },
        success: function(response) {
            submitBtn.prop('disabled', false).html(originalBtnText);
            $('.loading-overlay').removeClass('active');
            
            if (response.status === 'success') {
                showMessage('success', response.message || 'Class updated successfully');
                $('#editTimetableModal').modal('hide');
                loadMyTimetable();
            } else {
                showMessage('error', response.message || 'Failed to update timetable entry');
            }
        },
        error: function(xhr, status, error) {
            submitBtn.prop('disabled', false).html(originalBtnText);
            $('.loading-overlay').removeClass('active');
            console.error('Error updating timetable:', error);
            showMessage('error', 'Failed to update timetable entry. Please try again.');
        }
    });
    
    return false;
}

let deleteEntryId = null;

function deleteTimetable(id) {
    if (!id) return;

        deleteEntryId = id;
        $('#deleteModal').modal('show');
    }

    $('#confirmDelete').click(function() {
        if (!deleteEntryId) {
            $('#deleteModal').modal('hide');
            return;
        }
    
    const deleteBtn = $(this);
    const originalBtnText = deleteBtn.html();
        
            $.ajax({
        url: '<?php echo base_url(); ?>teacher/delete_timetable_entry',
                type: 'POST',
        data: { 
            id: deleteEntryId,
            teacher_id: '<?php echo $teacher_id; ?>'
        },
            dataType: 'json',
            beforeSend: function() {
            deleteBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Deleting...');
            $('.loading-overlay').addClass('active');
            },
                success: function(response) {
            deleteBtn.prop('disabled', false).html(originalBtnText);
            $('.loading-overlay').removeClass('active');
                $('#deleteModal').modal('hide');
                
                if (response.status === 'success') {
                showMessage('success', response.message || 'Class deleted successfully');
                loadMyTimetable();
                        } else {
                showMessage('error', response.message || 'Failed to delete entry');
                        }
                deleteEntryId = null;
                },
                error: function(xhr, status, error) {
            deleteBtn.prop('disabled', false).html(originalBtnText);
            $('.loading-overlay').removeClass('active');
                $('#deleteModal').modal('hide');
                console.error('Error deleting entry:', error);
            showMessage('error', 'Failed to delete entry. Please try again.');
                deleteEntryId = null;
            }
        });
    });

// Add modal scroll handling
$('.modal').on('shown.bs.modal', function() {
    const modalBody = $(this).find('.modal-body');
    const modalDialog = $(this).find('.modal-dialog');
    const windowHeight = $(window).height();
    const modalHeight = modalDialog.height();
    
    if (modalHeight > windowHeight) {
        modalBody.css('max-height', `${windowHeight - 200}px`);
    }
});

// Reset form and errors when modal is closed
$('.modal').on('hidden.bs.modal', function() {
    $(this).find('form')[0].reset();
    $(this).find('.alert').remove();
});

// Initialize tooltips
$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();
    
    // Add loading overlay to AJAX requests
    $(document).ajaxStart(function() {
        $('.loading-overlay').addClass('active');
    }).ajaxStop(function() {
        $('.loading-overlay').removeClass('active');
    });
});
    </script> 