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
            <div class="panel panel-primary" data-collapsed="0">
                <div class="panel-heading">
                    <div class="panel-title">
                        <h4><?php echo get_phrase('calendar_timetable'); ?></h4>
                    </div>
                </div>
                <div class="panel-body">

                    <!-- Filter Options -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <select name="filter_class_id" class="form-control" id="filter_class_id">
                                <option value=""><?php echo get_phrase('all_classes'); ?></option>
                                <?php
                                $classes = $this->db->get('class')->result_array();
                                foreach ($classes as $row) {
                                    echo '<option value="' . $row['class_id'] . '">' . $row['name'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-8 text-right">
                            <button class="btn btn-primary" id="add_timetable">
                                <i class="fa fa-plus"></i> <?php echo get_phrase('add_timetable'); ?>
                            </button>
                            <button class="btn btn-default" id="print_timetable">
                                <i class="fa fa-print"></i> <?php echo get_phrase('print'); ?>
                            </button>
                        </div>
                    </div>

                    <!-- Calendar Navigation -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="btn-group">
                                <button class="btn btn-default" id="prev-month">
                                    <i class="fa fa-chevron-left"></i>
                                </button>
                                <button class="btn btn-default" id="current-month">
                                    <?php echo date('F Y'); ?>
                                </button>
                                <button class="btn btn-default" id="next-month">
                                    <i class="fa fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                        
                    </div>

                    <!-- Calendar View -->
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Time Slot Modal -->
    <div class="modal fade" id="timeSlotModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><?php echo get_phrase('manage_time_slot'); ?></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="timeSlotForm">
                        <input type="hidden" id="selected_date" name="selected_date">
                        
                        <div class="form-group">
                            <label><?php echo get_phrase('class'); ?> *</label>
                            <select class="form-control" id="modal_class_id" name="class_id" required>
                                <option value=""><?php echo get_phrase('select_class'); ?></option>
                                <?php
                                $classes = $this->db->get('class')->result_array();
                                foreach ($classes as $row) {
                                    echo '<option value="' . $row['class_id'] . '">' . $row['name'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label><?php echo get_phrase('section'); ?> *</label>
                            <select class="form-control" id="modal_section_id" name="section_id" required>
                                <option value=""><?php echo get_phrase('select_section'); ?></option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label><?php echo get_phrase('subject'); ?> *</label>
                            <select class="form-control" id="modal_subject_id" name="subject_id" required>
                                <option value=""><?php echo get_phrase('select_subject'); ?></option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label><?php echo get_phrase('teacher'); ?> *</label>
                            <select class="form-control" id="modal_teacher_id" name="teacher_id" required>
                                <option value=""><?php echo get_phrase('select_teacher'); ?></option>
                                <?php
                                $teachers = $this->db->get('teacher')->result_array();
                                foreach ($teachers as $row) {
                                    echo '<option value="' . $row['teacher_id'] . '">' . $row['name'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label><?php echo get_phrase('time_slot'); ?> *</label>
                            <div class="row">
                                <div class="col-md-5">
                                    <input type="time" class="form-control" id="start_time" name="start_time" required>
                                </div>
                                <div class="col-md-2 text-center">
                                    <span class="mt-2 d-inline-block">to</span>
                                </div>
                                <div class="col-md-5">
                                    <input type="time" class="form-control" id="end_time" name="end_time" required>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="remove_slot"><?php echo get_phrase('remove'); ?></button>
                    <button type="button" class="btn btn-primary" id="save_slot"><?php echo get_phrase('save'); ?></button>
                </div>
            </div>
        </div>
    </div>

    <!-- Timetable Modal -->
    <div class="modal fade" id="timetableModal" tabindex="-1" role="dialog" aria-labelledby="timetableModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="timetableModalLabel">Add/Edit Timetable Entry</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="timetableForm">
                        <input type="hidden" id="timetable_id" name="timetable_id">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="timetable_start_date">Start Date</label>
                                    <input type="date" class="form-control" id="timetable_start_date" name="start_date" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="timetable_end_date">End Date</label>
                                    <input type="date" class="form-control" id="timetable_end_date" name="end_date" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="class_id">Class</label>
                                    <select class="form-control" id="class_id" name="class_id" required>
                                        <option value="">Select Class</option>
                                        <?php
                                        $classes = $this->db->get('class')->result_array();
                                        foreach($classes as $row):
                                        ?>
                                        <option value="<?php echo $row['class_id'];?>"><?php echo $row['name'];?></option>
                                        <?php endforeach;?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="section_id">Section</label>
                                    <select class="form-control" id="section_id" name="section_id" required>
                                        <option value="">Select Section</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="subject_id">Subject</label>
                                    <select class="form-control" id="subject_id" name="subject_id" required>
                                        <option value="">Select Subject</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="teacher_id">Teacher</label>
                                    <select class="form-control" id="teacher_id" name="teacher_id" required>
                                        <option value="">Select Teacher</option>
                                        <?php
                                        $teachers = $this->db->get('teacher')->result_array();
                                        foreach($teachers as $row):
                                        ?>
                                        <option value="<?php echo $row['teacher_id'];?>"><?php echo $row['name'];?></option>
                                        <?php endforeach;?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="timetable_start_time">Start Time</label>
                                    <input type="time" class="form-control" id="timetable_start_time" name="start_time" required>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="timetable_end_time">End Time</label>
                                    <input type="time" class="form-control" id="timetable_end_time" name="end_time" required>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="save_timetable">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Include required CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css">
    <link href="<?php echo base_url('assets/css/toastr.min.css'); ?>" rel="stylesheet">

    <!-- Include required JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="<?php echo base_url('assets/js/toastr.min.js'); ?>"></script>

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

        let currentDate = new Date();
        let selectedTeacher = '';
        let selectedSubject = '';
        let timetableData = {};
        
        // Initialize calendar
        function initCalendar(year, month) {
            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0);
            const startingDay = firstDay.getDay();
            const monthLength = lastDay.getDate();
            const today = new Date();
            
            let calendarHtml = '<div class="calendar-grid">';
            
            // Add day headers
            const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
            days.forEach(day => {
                calendarHtml += `<div class="calendar-day-header">${day}</div>`;
            });
            
            // Add calendar days
            let day = 1;
            let nextMonthDay = 1;
            let prevMonthLastDay = new Date(year, month, 0).getDate();
            let prevMonthStartDay = prevMonthLastDay - startingDay + 1;
            
            for (let i = 0; i < 42; i++) {
                if (i < startingDay) {
                    // Previous month days
                    calendarHtml += `<div class="calendar-day other-month">
                        <div class="calendar-day-header">${prevMonthStartDay + i}</div>
                        <div class="time-slots"></div>
                    </div>`;
                } else if (day > monthLength) {
                    // Next month days
                    calendarHtml += `<div class="calendar-day other-month">
                        <div class="calendar-day-header">${nextMonthDay}</div>
                        <div class="time-slots"></div>
                    </div>`;
                    nextMonthDay++;
                } else {
                    // Current month days
                    const isToday = year === today.getFullYear() && 
                                  month === today.getMonth() && 
                                  day === today.getDate();
                    const isWeekend = (i % 7 === 0 || i % 7 === 6);
                    const classes = ['calendar-day'];
                    if (isToday) classes.push('today');
                    if (isWeekend) classes.push('weekend');
                    
                    const currentDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                    const slots = timetableData[currentDate] || [];
                    
                    calendarHtml += `
                        <div class="${classes.join(' ')}" data-date="${currentDate}">
                            <div class="calendar-day-header">${day}</div>
                            <div class="time-slots">
                                ${renderTimeSlots(slots, currentDate)}
                            </div>
                        </div>`;
                    day++;
                }
            }
            
            calendarHtml += '</div>';
            $('#calendar').html(calendarHtml);
            
            // Add click handlers for time slots
            $('.time-slot').click(function() {
                const date = $(this).closest('.calendar-day').data('date');
                const timeSlot = $(this).data('time');
                openTimeSlotModal(date, timeSlot);
            });
        }
        
        function renderTimeSlots(slots, date) {
            const timeSlots = [
                '08:00-08:45', '08:45-09:30', '09:30-10:15',
                '10:15-11:00', '11:00-11:45', '11:45-12:30',
                '13:30-14:15', '14:15-15:00'
            ];
            
            return timeSlots.map(slot => {
                const existingSlot = slots.find(s => s.time_slot === slot);
                if (existingSlot) {
                    return `
                        <div class="time-slot has-class" data-time="${slot}">
                            ${slot}<br>
                            <div class="teacher-subject-info">
                                ${existingSlot.subject_name}<br>
                                ${existingSlot.teacher_name}
                            </div>
                        </div>`;
                } else {
                    return `<div class="time-slot empty" data-time="${slot}">${slot}</div>`;
                }
            }).join('');
        }
        
        function loadTimetableData() {
            const classId = $('#filter_class_id').val();
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth() + 1;
            
            $.ajax({
                url: '<?php echo base_url();?>admin/get_timetable_data_ajax',
                type: 'POST',
                data: {
                    class_id: classId,
                    month: month,
                    year: year
                },
                success: function(response) {
                    const data = JSON.parse(response);
                    displayTimetableData(data);
                }
            });
        }
        
        function openTimeSlotModal(date, timeSlot) {
            $('#selected_date').val(date);
            $('#modal_class_id').val(classId);
            $('#modal_section_id').html(sectionId);
            $('#modal_subject_id').html(subjectId);
            $('#modal_teacher_id').val(teacherId);
            $('#start_time').val(timeSlot.split('-')[0]);
            $('#end_time').val(timeSlot.split('-')[1]);
            $('#timeSlotModal').modal('show');
        }
        
        // Event handlers
        $('#class_selector, #section_selector').change(function() {
            const classId = $('#class_selector').val();
            if ($(this).attr('id') === 'class_selector') {
                // Update sections dropdown
                $.ajax({
                    url: '<?php echo base_url(); ?>admin/get_class_section/' + classId,
                    success: function(response) {
                        $('#section_selector').html(response);
                    }
                });
                
                // Update subjects dropdown
                $.ajax({
                    url: '<?php echo base_url(); ?>admin/get_class_subject/' + classId,
                    success: function(response) {
                        $('#subject_selector').html(response);
                    }
                });
            }
            loadTimetableData();
        });
        
        $('#teacher_selector, #subject_selector').change(loadTimetableData);
        
        $('#prev-month').click(function() {
            currentDate.setMonth(currentDate.getMonth() - 1);
            $('#current-month').text(currentDate.toLocaleString('default', { month: 'long', year: 'numeric' }));
            loadTimetableData();
        });
        
        $('#next-month').click(function() {
            currentDate.setMonth(currentDate.getMonth() + 1);
            $('#current-month').text(currentDate.toLocaleString('default', { month: 'long', year: 'numeric' }));
            loadTimetableData();
        });
        
        $('#save_slot').click(function() {
            const classId = $('#modal_class_id').val();
            const sectionId = $('#modal_section_id').val();
            const teacherId = $('#modal_teacher_id').val();
            const subjectId = $('#modal_subject_id').val();
            const date = $('#selected_date').val();
            const startTime = $('#start_time').val();
            const endTime = $('#end_time').val();
            
            if (!classId || !sectionId || !teacherId || !subjectId || !startTime || !endTime) {
                alert('Please fill all required fields');
                return;
            }
            
            const timeSlot = `${startTime}-${endTime}`;
            
            $.ajax({
                url: '<?php echo base_url(); ?>admin/save_timetable_slot_ajax',
                type: 'POST',
                data: {
                    class_id: classId,
                    section_id: sectionId,
                    teacher_id: teacherId,
                    subject_id: subjectId,
                    date: date,
                    time_slot: timeSlot
                },
                success: function(response) {
                    $('#timeSlotModal').modal('hide');
                    loadTimetableData();
                }
            });
        });
        
        $('#remove_slot').click(function() {
            if (confirm('Are you sure you want to remove this time slot?')) {
                const classId = $('#modal_class_id').val();
                const sectionId = $('#modal_section_id').val();
                const date = $('#selected_date').val();
                const startTime = $('#start_time').val();
                const endTime = $('#end_time').val();
                
                const timeSlot = `${startTime}-${endTime}`;
                
                $.ajax({
                    url: '<?php echo base_url(); ?>admin/delete_timetable_slot_ajax',
                    type: 'POST',
                    data: {
                        class_id: classId,
                        section_id: sectionId,
                        date: date,
                        time_slot: timeSlot
                    },
                    success: function(response) {
                        $('#timeSlotModal').modal('hide');
                        loadTimetableData();
                    }
                });
            }
        });
        
        $('#print_timetable').click(function() {
            window.print();
        });
        
        // Update sections when class is selected in modal
        $('#modal_class_id').change(function() {
            var class_id = $(this).val();
            $.ajax({
                url: '<?php echo base_url();?>admin/get_sections_by_class/' + class_id,
                success: function(response) {
                    $('#modal_section_id').html(response);
                }
            });

            // Update subjects for the selected class
            $.ajax({
                url: '<?php echo base_url();?>admin/get_subjects_by_class/' + class_id,
                success: function(response) {
                    $('#modal_subject_id').html(response);
                }
            });
        });
        
        // Filter class change handler
        $('#filter_class_id').change(function() {
            loadTimetableData();
        });

        // Add Timetable button click
        $('#add_timetable').click(function() {
            try {
                openTimetableModal(new Date());
            } catch(e) {
                toastr.error('Error opening timetable form: ' + e.message);
            }
        });

        // Class change handler in modal
        $('#class_id').change(function() {
            var class_id = $(this).val();
            if(class_id) {
                $.ajax({
                    url: '<?php echo base_url();?>admin/get_sections_by_class/' + class_id,
                    success: function(response) {
                        $('#section_id').html(response);
                    }
                });
                
                $.ajax({
                    url: '<?php echo base_url();?>admin/get_subjects_by_class/' + class_id,
                    success: function(response) {
                        $('#subject_id').html(response);
                    }
                });
            }
        });

        // Direct button click handler instead of form submit
        $('#save_timetable').click(function(e) {
            e.preventDefault();
            
            console.log('Save timetable button clicked');
            
            // Get form data
            var formData = {
                class_id: $('#class_id').val(),
                section_id: $('#section_id').val(),
                subject_id: $('#subject_id').val(),
                teacher_id: $('#teacher_id').val(),
                start_date: $('#timetable_start_date').val(),
                end_date: $('#timetable_end_date').val(),
                start_time: $('#timetable_start_time').val(),
                end_time: $('#timetable_end_time').val()
            };

            // Add timetable_id if editing
            var timetableId = $('#timetable_id').val();
            if (timetableId) {
                formData.timetable_id = timetableId;
            }

            // Validate form
            var errors = [];
            Object.keys(formData).forEach(function(key) {
                if (!formData[key]) {
                    errors.push(key.replace('_', ' ').toUpperCase() + ' is required');
                }
            });

            if (errors.length > 0) {
                errors.forEach(function(error) {
                    toastr.error(error);
                });
                return false;
            }

            // Show loading indicator
            $('#save_timetable').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');

            console.log('Submitting data to: ' + '<?php echo base_url("admin/save_timetable_ajax"); ?>');
            console.log('Form data:', formData);
            
            // Submit form via direct AJAX
            $.ajax({
                url: '<?php echo base_url("admin/save_timetable_ajax"); ?>',
                type: 'POST',
                data: formData,
                success: function(response) {
                    console.log('Raw response:', response);
                    
                    try {
                        // Try to parse JSON if not already parsed
                        var data = typeof response === 'string' ? JSON.parse(response) : response;
                        
                        if (data.status === 'success') {
                            // Show success message
                            toastr.success(data.message || 'Timetable saved successfully');
                            
                            // Close modal and reset form
                            $('#timetableModal').modal('hide');
                            $('#timetableForm')[0].reset();
                            
                            // Refresh calendar
                            if (typeof calendar !== 'undefined') {
                                calendar.fullCalendar('refetchEvents');
                            }
                        } else {
                            // Show error message
                            toastr.error(data.message || 'Failed to save timetable');
                        }
                    } catch (e) {
                        console.error('Error parsing response:', e);
                        toastr.error('Error processing response: ' + e.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', status, error);
                    console.error('Response text:', xhr.responseText);
                    toastr.error('Server error: ' + error);
                },
                complete: function() {
                    // Re-enable save button
                    $('#save_timetable').prop('disabled', false).html('Save');
                }
            });
        });

        function openTimetableModal(date, event = null) {
            $('#timetableForm')[0].reset();
            
            // Set default start and end dates
            if (event) {
                $('#timetable_start_date').val(moment(event.start).format('YYYY-MM-DD'));
                $('#timetable_end_date').val(moment(event.end || event.start).format('YYYY-MM-DD'));
                $('#class_id').val(event.class_id).trigger('change');
                setTimeout(function() {
                    $('#section_id').val(event.section_id);
                    $('#subject_id').val(event.subject_id);
                }, 500);
                $('#teacher_id').val(event.teacher_id);
                $('#timetable_start_time').val(moment(event.start).format('HH:mm'));
                $('#timetable_end_time').val(moment(event.end || moment(event.start).add(1, 'hour')).format('HH:mm'));
                $('#timetable_id').val(event.id);
            } else {
                $('#timetable_start_date').val(moment(date).format('YYYY-MM-DD'));
                $('#timetable_end_date').val(moment(date).format('YYYY-MM-DD'));
                $('#timetable_id').val('');
            }
            
            $('#timetableModal').modal('show');
        }

        // Initialize fullCalendar
        var calendar = $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            events: {
                url: '<?php echo base_url();?>admin/get_timetable_data_ajax',
                type: 'POST',
                data: function() {
                    return {
                        class_id: $('#filter_class_id').val()
                    };
                }
            },
            selectable: true,
            select: function(start, end) {
                openTimetableModal(start);
            },
            eventClick: function(event) {
                openTimetableModal(event.start, event);
            }
        });

        // Filter class change handler
        $('#filter_class_id').change(function() {
            calendar.fullCalendar('refetchEvents');
        });

        // Print button click
        $('#print_timetable').click(function() {
            window.print();
        });

        // Class change handler in modal
        $('#class_id').change(function() {
            var class_id = $(this).val();
            if(class_id) {
                $.ajax({
                    url: '<?php echo base_url();?>admin/get_sections_by_class/' + class_id,
                    success: function(response) {
                        $('#section_id').html(response);
                    }
                });
                
                $.ajax({
                    url: '<?php echo base_url();?>admin/get_subjects_by_class/' + class_id,
                    success: function(response) {
                        $('#subject_id').html(response);
                    }
                });
            }
        });
    });
    </script> 
</body>
</html> 