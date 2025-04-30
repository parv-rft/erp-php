<?php
$this->load->helper('form');
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
                <!-- Class & Section Selector -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <select name="class_id" class="form-control" id="class_selector" required>
                            <option value=""><?php echo get_phrase('select_class'); ?></option>
                            <?php
                            $classes = $this->db->get('class')->result_array();
                            foreach ($classes as $row) {
                                $selected = ($row['class_id'] == $class_id) ? 'selected' : '';
                                echo '<option value="' . $row['class_id'] . '" ' . $selected . '>' . $row['name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="section_id" class="form-control" id="section_selector" required>
                            <option value=""><?php echo get_phrase('select_section'); ?></option>
                            <?php
                            if ($class_id) {
                                $sections = $this->db->get_where('section', array('class_id' => $class_id))->result_array();
                                foreach ($sections as $row) {
                                    $selected = ($row['section_id'] == $section_id) ? 'selected' : '';
                                    echo '<option value="' . $row['section_id'] . '" ' . $selected . '>' . $row['name'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="teacher_id" class="form-control" id="teacher_selector">
                            <option value=""><?php echo get_phrase('select_teacher'); ?></option>
                            <?php
                            $teachers = $this->db->get('teacher')->result_array();
                            foreach ($teachers as $row) {
                                echo '<option value="' . $row['teacher_id'] . '">' . $row['name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="subject_id" class="form-control" id="subject_selector">
                            <option value=""><?php echo get_phrase('select_subject'); ?></option>
                            <?php
                            if ($class_id) {
                                $subjects = $this->db->get_where('subject', array('class_id' => $class_id))->result_array();
                                foreach ($subjects as $row) {
                                    echo '<option value="' . $row['subject_id'] . '">' . $row['name'] . '</option>';
                                }
                            }
                            ?>
                        </select>
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
                    <div class="col-md-8 text-right">
                        <button class="btn btn-info" id="print_timetable">
                            <i class="fa fa-print"></i> <?php echo get_phrase('print_timetable'); ?>
                        </button>
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
                        <label><?php echo get_phrase('time_slot'); ?></label>
                        <select class="form-control" id="time_slot" name="time_slot" required>
                            <?php
                            $time_slots = array(
                                array('start' => '08:00', 'end' => '08:45'),
                                array('start' => '08:45', 'end' => '09:30'),
                                array('start' => '09:30', 'end' => '10:15'),
                                array('start' => '10:15', 'end' => '11:00'),
                                array('start' => '11:00', 'end' => '11:45'),
                                array('start' => '11:45', 'end' => '12:30'),
                                array('start' => '13:30', 'end' => '14:15'),
                                array('start' => '14:15', 'end' => '15:00')
                            );
                            
                            foreach ($time_slots as $slot) {
                                echo '<option value="' . $slot['start'] . '-' . $slot['end'] . '">' 
                                    . $slot['start'] . ' - ' . $slot['end'] . '</option>';
                            }
                            ?>
                        </select>
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
        const classId = $('#class_selector').val();
        const sectionId = $('#section_selector').val();
        const teacherId = $('#teacher_selector').val();
        const subjectId = $('#subject_selector').val();
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth() + 1;
        
        if (!classId || !sectionId) return;
        
        $.ajax({
            url: '<?php echo base_url(); ?>admin/get_timetable_data_ajax',
            type: 'POST',
            data: {
                class_id: classId,
                section_id: sectionId,
                teacher_id: teacherId,
                subject_id: subjectId,
                month: month,
                year: year
            },
            success: function(response) {
                timetableData = JSON.parse(response);
                initCalendar(currentDate.getFullYear(), currentDate.getMonth());
            }
        });
    }
    
    function openTimeSlotModal(date, timeSlot) {
        $('#selected_date').val(date);
        $('#time_slot').val(timeSlot);
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
        const classId = $('#class_selector').val();
        const sectionId = $('#section_selector').val();
        const teacherId = $('#teacher_selector').val();
        const subjectId = $('#subject_selector').val();
        const date = $('#selected_date').val();
        const timeSlot = $('#time_slot').val();
        
        if (!classId || !sectionId || !teacherId || !subjectId) {
            alert('Please select all required fields');
            return;
        }
        
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
        const classId = $('#class_selector').val();
        const sectionId = $('#section_selector').val();
        const date = $('#selected_date').val();
        const timeSlot = $('#time_slot').val();
        
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
    });
    
    $('#print_timetable').click(function() {
        window.print();
    });
    
    // Initialize calendar
    initCalendar(currentDate.getFullYear(), currentDate.getMonth());
});
</script> 