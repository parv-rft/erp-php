<?php
$teacher_id = $this->session->userdata('teacher_id');
$teacher_name = $this->db->get_where('teacher', array('teacher_id' => $teacher_id))->row()->name;
?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4>
                        <i class="fa fa-calendar"></i> My Teaching Schedule
                        <button class="btn btn-success btn-sm pull-right" onclick="window.print()">
                            <i class="fa fa-print"></i> Print Schedule
                        </button>
                    </h4>
                </div>
            </div>
            <div class="panel-body">
                <div class="alert alert-info">
                    <strong>Welcome, <?php echo $teacher_name; ?>!</strong> 
                    Below is your teaching schedule for the current academic period.
                </div>

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

<style>
.timetable-table { 
    margin-top: 20px;
    border-collapse: collapse;
    width: 100%;
}

.timetable-table thead th {
    background: #337ab7;
    color: #fff !important;
    font-weight: 600;
    text-align: center;
    padding: 12px 8px;
    border: 1px solid #2e6da4;
}

.time-col { 
    width: 120px;
    background: #f8f9fa !important;
    color: #000 !important;
    font-weight: bold;
}

.timetable-cell { 
    height: 100px;
    padding: 8px !important;
    vertical-align: top;
    position: relative;
    background: #fff;
    transition: all 0.3s ease;
}

.time-display { 
    text-align: center;
    font-weight: bold;
    padding: 5px;
    color: #333;
}

.class-slot { 
    height: 100%;
    padding: 10px;
    background: #f5f5f5;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.class-slot:hover {
    background: #e3f2fd;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
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
</style>

<script type="text/javascript">
$(document).ready(function() {
    loadMyTimetable();
    
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
                toastr.error(response.message);
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
            toastr.error('Failed to load your schedule. Please refresh the page or contact support.');
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
                        <div class="class-slot">
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
                        <div class="empty-slot">
                            No class
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
</script> 