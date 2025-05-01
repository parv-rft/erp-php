<?php
$teacher_id = $this->uri->segment(3) ? $this->uri->segment(3) : '';
$teacher_name = '';

if (!empty($teacher_id)) {
    $teacher = $this->db->get_where('teacher', array('teacher_id' => $teacher_id))->row();
    if ($teacher) {
        $teacher_name = $teacher->name;
    }
}
?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    <?php if (!empty($teacher_name)): ?>
                        <h4><?php echo get_phrase('timetable_for') . ': ' . $teacher_name; ?></h4>
                    <?php else: ?>
                        <h4><?php echo get_phrase('teacher_timetable'); ?></h4>
                    <?php endif; ?>
                </div>
            </div>
            <div class="panel-body">
                <?php if (empty($teacher_id)): ?>
                <!-- Teacher Selection -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="teacher_select"><?php echo get_phrase('select_teacher'); ?></label>
                            <select class="form-control" id="teacher_select">
                                <option value=""><?php echo get_phrase('select_teacher'); ?></option>
                                <?php
                                $teachers = $this->db->get('teacher')->result_array();
                                foreach($teachers as $row):
                                ?>
                                <option value="<?php echo $row['teacher_id']; ?>"><?php echo $row['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button class="btn btn-primary" id="view_teacher_timetable">
                            <?php echo get_phrase('view_timetable'); ?>
                        </button>
                    </div>
                </div>
                <?php else: ?>
                <!-- Calendar View -->
                <div id="calendar"></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($teacher_id)): ?>
<!-- Include required CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<!-- Include required JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<style>
#calendar {
    background: white;
    border-radius: 4px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.12);
    margin-top: 20px;
}

.fc-event {
    cursor: pointer;
}

.fc-day-grid-event .fc-content {
    white-space: normal;
    overflow: hidden;
}

@media print {
    .btn-group, .form-control {
        display: none !important;
    }
    
    #calendar {
        box-shadow: none;
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

    // Initialize FullCalendar
    var calendar = $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay,listWeek'
        },
        events: {
            url: '<?php echo base_url();?>admin/get_timetable_data_ajax',
            type: 'POST',
            data: function() {
                var date = $('#calendar').fullCalendar('getDate');
                return {
                    teacher_id: '<?php echo $teacher_id; ?>',
                    year: date.format('YYYY'),
                    month: date.format('M')
                };
            },
            error: function() {
                toastr.error('Error fetching timetable data');
            }
        },
        eventRender: function(event, element) {
            if (event.description) {
                element.attr('title', event.description.replace(/<br>/g, '\n'));
                
                // Add tooltip with bootstrap or a tooltip library if available
                if (typeof $(element).tooltip === 'function') {
                    $(element).tooltip({
                        placement: 'top',
                        title: event.description.replace(/<br>/g, '\n'),
                        container: 'body',
                        html: true
                    });
                }
            }
        },
        eventClick: function(event) {
            // Show event details
            var detailsHtml = '<div>' + event.description + '</div>';
            var startTime = moment(event.start).format('h:mm A');
            var endTime = moment(event.end).format('h:mm A');
            
            // Use toastr to show event details
            toastr.info(
                '<strong>Time:</strong> ' + startTime + ' - ' + endTime + '<br>' +
                event.description,
                event.title
            );
        },
        timeFormat: 'h:mm A',
        displayEventEnd: true,
        firstDay: 0, // Sunday as first day
        height: 'auto',
        aspectRatio: 1.8,
        loading: function(isLoading) {
            if (isLoading) {
                // Show loading indicator
                $('#calendar').addClass('loading');
            } else {
                // Hide loading indicator
                $('#calendar').removeClass('loading');
            }
        }
    });
    
    // Add print button
    $('<button class="btn btn-info" id="print_timetable"><i class="fa fa-print"></i> Print</button>')
        .appendTo('.fc-right');
    
    // Add back button 
    $('<a href="<?php echo base_url("admin/teacher_timetable"); ?>" class="btn btn-default" id="back_button"><i class="fa fa-arrow-left"></i> Back</a>')
        .appendTo('.fc-left');
    
    // Print button click
    $('#print_timetable').click(function() {
        window.print();
    });
});
</script>
<?php else: ?>
<script type="text/javascript">
$(document).ready(function() {
    // View teacher timetable button
    $('#view_teacher_timetable').click(function() {
        var teacher_id = $('#teacher_select').val();
        if (teacher_id) {
            window.location.href = '<?php echo base_url("admin/teacher_timetable/"); ?>' + teacher_id;
        } else {
            alert('<?php echo get_phrase("please_select_a_teacher"); ?>');
        }
    });
});
</script>
<?php endif; ?> 