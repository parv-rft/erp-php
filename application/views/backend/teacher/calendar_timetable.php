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
                                <button class="btn btn-info" onclick="loadTimetable()">
                                    <i class="fa fa-search"></i> <?php echo get_phrase('load_timetable'); ?>
                                </button>
                                <button class="btn btn-success" onclick="window.print()">
                            <i class="fa fa-print"></i> <?php echo get_phrase('print'); ?>
                        </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row" style="margin-top: 20px;">
                    <div class="col-md-12">
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
                                        <td colspan="8" class="text-center"><?php echo get_phrase('please_select_filters'); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
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
    color: #333 !important;
    font-weight: bold;
}

.timetable-cell { 
    height: 100px;
    padding: 5px !important;
    vertical-align: top;
    position: relative;
    background: #fff;
}

.time-display { 
    text-align: center;
    font-weight: bold;
    padding: 5px;
    color: #333;
}

.view-cell { 
    height: 100%;
    padding: 10px;
    background: #f5f5f5;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.view-cell:hover {
    background: #e3f2fd;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.subject-name { 
    font-weight: bold;
    color: #2196F3;
    margin-bottom: 5px;
}

.class-name { 
    color: #666;
    font-size: 13px;
    margin-bottom: 3px;
}

.room-number { 
    color: #999;
    font-size: 12px;
}

.empty-cell { 
    background: #fafafa;
}

@media print {
    .panel-heading, .form-group, .btn {
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

    // Load initial timetable
    loadTimetable();
});

// Class change event - load sections
$('#class_id').change(function() {
    const classId = $(this).val();
    selectedClassId = classId;
    
    if (classId) {
        $.ajax({
            url: '<?php echo base_url(); ?>get_sections/' + classId,
            type: 'GET',
            success: function(response) {
                $('#section_id').html(response);
            },
            error: function(xhr, status, error) {
                console.error('Error loading sections:', error);
                toastr.error('Failed to load sections');
            }
        });
    } else {
        $('#section_id').html('<option value=""><?php echo get_phrase('all_sections'); ?></option>');
            }
});

// Section change event
$('#section_id').change(function() {
    selectedSectionId = $(this).val();
});

function loadTimetable() {
    selectedClassId = $('#class_id').val();
    selectedSectionId = $('#section_id').val();
    
    $.ajax({
        url: '<?php echo base_url(); ?>teacher/get_calendar_timetable_data',
        type: 'POST',
        data: {
            class_id: selectedClassId,
            section_id: selectedSectionId,
            teacher_id: '<?php echo $teacher_id; ?>'
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
            toastr.error('Failed to load timetable');
            $('#timetable-body').html('<tr><td colspan="8" class="text-center text-danger"><?php echo get_phrase('error_loading_timetable'); ?></td></tr>');
            }
    });
}

function renderTimetable() {
    if (!timetableData.length) {
        $('#timetable-body').html('<tr><td colspan="8" class="text-center"><?php echo get_phrase('no_timetable_entries_found'); ?></td></tr>');
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
                        <div class="view-cell">
                            <div class="subject-name">${entry.subject_name}</div>
                            <div class="class-name">${entry.class_name} - ${entry.section_name}</div>
                            <div class="room-number">Room: ${entry.room_number || 'N/A'}</div>
                        </div>
                    </td>`;
            } else {
                html += '<td class="timetable-cell empty-cell"></td>';
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