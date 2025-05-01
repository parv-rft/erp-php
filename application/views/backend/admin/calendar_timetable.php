<?php
$this->load->helper('form');
$page_title = get_phrase('calendar_timetable');
$breadcrumb = array(
    array('name' => get_phrase('dashboard'), 'url' => 'admin/dashboard'),
    array('name' => $page_title, 'url' => 'admin/calendar_timetable')
);
?>
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

<!-- Add/Edit Timetable Modal -->
<div class="modal fade" id="timetableModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php echo get_phrase('add_timetable'); ?></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="timetableForm">
                    <input type="hidden" id="timetable_id" name="timetable_id">
                    
                    <div class="form-group">
                        <label><?php echo get_phrase('start_date'); ?> *</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" required>
                    </div>

                    <div class="form-group">
                        <label><?php echo get_phrase('end_date'); ?> *</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" required>
                    </div>

                    <div class="form-group">
                        <label><?php echo get_phrase('class'); ?> *</label>
                        <select class="form-control" id="class_id" name="class_id" required>
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
                        <select class="form-control" id="section_id" name="section_id" required>
                            <option value=""><?php echo get_phrase('select_section'); ?></option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label><?php echo get_phrase('subject'); ?> *</label>
                        <select class="form-control" id="subject_id" name="subject_id" required>
                            <option value=""><?php echo get_phrase('select_subject'); ?></option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label><?php echo get_phrase('teacher'); ?> *</label>
                        <select class="form-control" id="teacher_id" name="teacher_id" required>
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
                        <label><?php echo get_phrase('time'); ?> *</label>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="time" class="form-control" id="start_time" name="start_time" required>
                                <small class="text-muted"><?php echo get_phrase('start_time'); ?></small>
                            </div>
                            <div class="col-md-6">
                                <input type="time" class="form-control" id="end_time" name="end_time" required>
                                <small class="text-muted"><?php echo get_phrase('end_time'); ?></small>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo get_phrase('close'); ?></button>
                <button type="button" class="btn btn-primary" id="save_timetable"><?php echo get_phrase('save'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- Include Toastr -->
<link href="<?php echo base_url();?>assets/vendors/toastr/toastr.min.css" rel="stylesheet">
<script src="<?php echo base_url();?>assets/vendors/toastr/toastr.min.js"></script>

<style>
#calendar {
    background: white;
    border-radius: 4px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.12);
    margin-top: 20px;
}

.calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 1px;
    background: #dee2e6;
}

.calendar-day {
    background: white;
    min-height: 120px;
    padding: 5px;
    position: relative;
}

.calendar-day.other-month {
    background: #f8f9fa;
}

.calendar-day-header {
    text-align: right;
    padding: 2px 5px;
    font-weight: bold;
    border-bottom: 1px solid #dee2e6;
}

.time-slots {
    margin-top: 5px;
}

.time-slot {
    padding: 3px;
    margin: 2px 0;
    border-radius: 3px;
    font-size: 12px;
    cursor: pointer;
    transition: all 0.2s;
}

.time-slot.has-class {
    background: #e3f2fd;
    border: 1px solid #90caf9;
}

.time-slot.empty {
    background: #f5f5f5;
    border: 1px dashed #ddd;
}

.time-slot:hover {
    background: #bbdefb;
    transform: scale(1.02);
}

.teacher-subject-info {
    font-size: 11px;
    color: #666;
    margin-top: 2px;
}

.mb-3 {
    margin-bottom: 15px;
}

.calendar-day.today {
    background: #fff8e1;
}

.calendar-day.weekend {
    background: #f8f9fa;
}

@media print {
    .btn-group, .form-control, .modal {
        display: none !important;
    }
    
    #calendar {
        box-shadow: none;
    }
    
    .time-slot {
        border: 1px solid #ddd !important;
        background: white !important;
        color: black !important;
    }
}
</style>

<script type="text/javascript">
$(document).ready(function() {
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
        openTimetableModal(new Date());
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

    // Save timetable
    $('#save_timetable').click(function() {
        var formData = $('#timetableForm').serialize();
        
        $.ajax({
            url: '<?php echo base_url();?>admin/save_timetable_slot_ajax',
            type: 'POST',
            data: formData,
            success: function(response) {
                var data = JSON.parse(response);
                if(data.status == 'success') {
                    $('#timetableModal').modal('hide');
                    loadTimetableData();
                    toastr.success(data.message);
                } else {
                    toastr.error(data.message);
                }
            },
            error: function() {
                toastr.error('An error occurred while saving the timetable');
            }
        });
    });

    function openTimetableModal(date, event = null) {
        $('#timetableForm')[0].reset();
        
        // Set default start and end dates
        if (event) {
            $('#start_date').val(moment(event.start).format('YYYY-MM-DD'));
            $('#end_date').val(moment(event.end || event.start).format('YYYY-MM-DD'));
            $('#class_id').val(event.class_id).trigger('change');
            setTimeout(function() {
                $('#section_id').val(event.section_id);
                $('#subject_id').val(event.subject_id);
            }, 500);
            $('#teacher_id').val(event.teacher_id);
            $('#start_time').val(moment(event.start).format('HH:mm'));
            $('#end_time').val(moment(event.end || moment(event.start).add(1, 'hour')).format('HH:mm'));
            $('#timetable_id').val(event.id);
        } else {
            $('#start_date').val(moment(date).format('YYYY-MM-DD'));
            $('#end_date').val(moment(date).format('YYYY-MM-DD'));
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

    // Add Timetable button click
    $('#add_timetable').click(function() {
        openTimetableModal(new Date());
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

    // Save timetable
    $('#save_timetable').click(function() {
        var formData = $('#timetableForm').serialize();
        
        $.ajax({
            url: '<?php echo base_url();?>admin/save_timetable_slot_ajax',
            type: 'POST',
            data: formData,
            success: function(response) {
                var data = JSON.parse(response);
                if(data.status == 'success') {
                    $('#timetableModal').modal('hide');
                    calendar.fullCalendar('refetchEvents');
                    toastr.success(data.message);
                } else {
                    toastr.error(data.message);
                }
            },
            error: function() {
                toastr.error('An error occurred while saving the timetable');
            }
        });
    });

    function openTimetableModal(date, event = null) {
        $('#timetableForm')[0].reset();
        
        // Set default start and end dates
        if (event) {
            $('#start_date').val(moment(event.start).format('YYYY-MM-DD'));
            $('#end_date').val(moment(event.end || event.start).format('YYYY-MM-DD'));
            $('#class_id').val(event.class_id).trigger('change');
            setTimeout(function() {
                $('#section_id').val(event.section_id);
                $('#subject_id').val(event.subject_id);
            }, 500);
            $('#teacher_id').val(event.teacher_id);
            $('#start_time').val(moment(event.start).format('HH:mm'));
            $('#end_time').val(moment(event.end || moment(event.start).add(1, 'hour')).format('HH:mm'));
            $('#timetable_id').val(event.id);
        } else {
            $('#start_date').val(moment(date).format('YYYY-MM-DD'));
            $('#end_date').val(moment(date).format('YYYY-MM-DD'));
            $('#timetable_id').val('');
        }
        
        $('#timetableModal').modal('show');
    }

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
</script> 