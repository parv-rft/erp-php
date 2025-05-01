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
                <!-- Class filter -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <select name="filter_class_id" class="form-control" id="filter_class_id">
                            <option value=""><?php echo get_phrase('all_classes'); ?></option>
                            <?php
                            // Get classes assigned to this teacher
                            $this->db->select('DISTINCT(c.class_id), c.name');
                            $this->db->from('timetable t');
                            $this->db->join('class c', 'c.class_id = t.class_id');
                            $this->db->where('t.teacher_id', $teacher_id);
                            $this->db->order_by('c.name', 'ASC');
                            $classes = $this->db->get()->result_array();
                            
                            foreach ($classes as $row) {
                                echo '<option value="' . $row['class_id'] . '">' . $row['name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-8 text-right">
                        <button class="btn btn-default" id="print_timetable">
                            <i class="fa fa-print"></i> <?php echo get_phrase('print'); ?>
                        </button>
                    </div>
                </div>
                
                <!-- Calendar -->
                <div id="calendar"></div>
            </div>
        </div>
    </div>
</div>

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

<!-- View Event Modal -->
<div class="modal fade" id="viewEventModal" tabindex="-1" role="dialog" aria-labelledby="viewEventModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewEventModalLabel">Timetable Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="viewEventBody">
                <!-- Event details will be inserted here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

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

    // Store the current event being viewed
    var currentEvent = null;

    // Initialize FullCalendar
    var calendar = $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay,listWeek'
        },
        events: {
            url: '<?php echo base_url();?>teacher/get_teacher_timetable_data',
            type: 'POST',
            data: function() {
                var date = $('#calendar').fullCalendar('getDate');
                return {
                    class_id: $('#filter_class_id').val(),
                    year: date.format('YYYY'),
                    month: date.format('M')
                };
            },
            error: function(xhr, textStatus, errorThrown) {
                console.error("Error fetching events:", textStatus, errorThrown);
                console.log(xhr.responseText);
                toastr.error('Error loading timetable data: ' + errorThrown);
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
            // Store current event
            currentEvent = event;
            
            // Format the event details
            var startTime = moment(event.start).format('h:mm A');
            var endTime = moment(event.end).format('h:mm A');
            var dateRange = moment(event.start).format('MMMM D, YYYY');
            
            if (moment(event.start).format('YYYY-MM-DD') !== moment(event.end).format('YYYY-MM-DD')) {
                dateRange += ' to ' + moment(event.end).format('MMMM D, YYYY');
            }
            
            // Display event details in modal
            var content = `
                <div class="event-details">
                    <p><strong>Subject:</strong> ${event.title}</p>
                    <p><strong>Date:</strong> ${dateRange}</p>
                    <p><strong>Time:</strong> ${startTime} - ${endTime}</p>
                    <p>${event.description}</p>
                </div>
            `;
            
            // Display in modal
            $('#viewEventBody').html(content);
            $('#viewEventModal').modal('show');
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
    
    // Filter class change handler
    $('#filter_class_id').change(function() {
        calendar.fullCalendar('refetchEvents');
    });
    
    // Print button click
    $('#print_timetable').click(function() {
        window.print();
    });
});
</script> 