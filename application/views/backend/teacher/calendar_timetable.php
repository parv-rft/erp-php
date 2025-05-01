<?php
$teacher_id = $this->session->userdata('teacher_id');
?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo get_phrase('my_timetable'); ?></h4>
                </div>
            </div>
            <div class="panel-body">
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
    background: #e3f2fd;
    border: 1px solid #90caf9;
}

.time-slot:hover {
    background: #bbdefb;
}

.class-info {
    font-weight: bold;
}

.subject-info {
    color: #666;
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
    .btn-group, .form-control {
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
                    <div class="date">${prevMonthStartDay + i}</div>
                    <div class="time-slots"></div>
                </div>`;
            } else if (day <= monthLength) {
                // Current month days
                const isToday = day === today.getDate() && 
                              month === today.getMonth() && 
                              year === today.getFullYear();
                const isWeekend = (i % 7 === 0) || (i % 7 === 6);
                
                calendarHtml += `<div class="calendar-day${isToday ? ' today' : ''}${isWeekend ? ' weekend' : ''}" 
                                     data-date="${year}-${(month + 1).toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}">
                    <div class="date">${day}</div>
                    <div class="time-slots"></div>
                </div>`;
                day++;
            } else {
                // Next month days
                calendarHtml += `<div class="calendar-day other-month">
                    <div class="date">${nextMonthDay}</div>
                    <div class="time-slots"></div>
                </div>`;
                nextMonthDay++;
            }
        }
        
        calendarHtml += '</div>';
        $('#calendar').html(calendarHtml);
        
        // Update month display
        const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                          'July', 'August', 'September', 'October', 'November', 'December'];
        $('#current-month').text(monthNames[month] + ' ' + year);
        
        // Load timetable data
        loadTeacherTimetable(year, month + 1);
    }
    
    // Load teacher's timetable data
    function loadTeacherTimetable(year, month) {
        $.ajax({
            url: '<?php echo base_url();?>teacher/get_teacher_timetable_data',
            type: 'POST',
            data: {
                year: year,
                month: month,
                teacher_id: <?php echo $teacher_id; ?>
            },
            success: function(response) {
                const data = JSON.parse(response);
                displayTimetableData(data);
            }
        });
    }
    
    // Display timetable data on calendar
    function displayTimetableData(data) {
        data.forEach(entry => {
            const dayCell = $(`.calendar-day[data-date="${entry.date}"]`);
            if (dayCell.length) {
                const timeSlot = `<div class="time-slot">
                    <div class="time-info">${entry.time_slot}</div>
                    <div class="class-info">${entry.class_name} - ${entry.section_name}</div>
                    <div class="subject-info">${entry.subject_name}</div>
                </div>`;
                dayCell.find('.time-slots').append(timeSlot);
            }
        });
    }
    
    // Initialize calendar with current month
    initCalendar(currentDate.getFullYear(), currentDate.getMonth());
    
    // Previous month button
    $('#prev-month').click(function() {
        currentDate.setMonth(currentDate.getMonth() - 1);
        initCalendar(currentDate.getFullYear(), currentDate.getMonth());
    });
    
    // Next month button
    $('#next-month').click(function() {
        currentDate.setMonth(currentDate.getMonth() + 1);
        initCalendar(currentDate.getFullYear(), currentDate.getMonth());
    });
    
    // Print button
    $('#print_timetable').click(function() {
        window.print();
    });
});
</script> 