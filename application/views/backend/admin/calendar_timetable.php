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
                        <div class="col-md-3">
                            <select name="filter_teacher_id" class="form-control" id="filter_teacher_id">
                                <option value=""><?php echo get_phrase('all_teachers'); ?></option>
                                <?php
                                $teachers = $this->db->get('teacher')->result_array();
                                foreach ($teachers as $row) {
                                    echo '<option value="' . $row['teacher_id'] . '">' . $row['name'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-5 text-right">
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
                    <button type="button" class="btn btn-warning" id="editEvent">Edit</button>
                    <button type="button" class="btn btn-danger" id="deleteEvent">Delete</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
                        class_id: $('#filter_class_id').val(),
                        teacher_id: $('#filter_teacher_id').val()
                    };
                },
                error: function(xhr, textStatus, errorThrown) {
                    console.error("Error fetching events:", textStatus, errorThrown);
                    console.log(xhr.responseText);
                    toastr.error('Error loading timetable data: ' + errorThrown);
                }
            },
            selectable: true,
            select: function(start, end) {
                openTimetableModal(start);
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
            eventRender: function(event, element) {
                element.attr('title', event.description.replace(/<br>/g, '\n'));
                
                if (typeof $(element).tooltip === 'function') {
                    $(element).tooltip({
                        placement: 'top',
                        title: event.description.replace(/<br>/g, '\n'),
                        container: 'body',
                        html: true
                    });
                }
            },
            timeFormat: 'h:mm A',
            displayEventEnd: true
        });

        // Filter class or teacher change handler
        $('#filter_class_id, #filter_teacher_id').change(function() {
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
        
        // Edit event button click
        $('#editEvent').click(function() {
            if (currentEvent) {
                $('#viewEventModal').modal('hide');
                openTimetableModal(currentEvent.start, currentEvent);
            }
        });
        
        // Delete event button click
        $('#deleteEvent').click(function() {
            if (currentEvent && confirm('Are you sure you want to delete this timetable entry?')) {
                // Show loading state
                $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Deleting...');
                
                // Send delete request
                $.ajax({
                    url: '<?php echo base_url(); ?>admin/delete_timetable_slot_ajax',
                    type: 'POST',
                    data: { timetable_id: currentEvent.id },
                    success: function(response) {
                        try {
                            var data = typeof response === 'string' ? JSON.parse(response) : response;
                            
                            if (data.status === 'success') {
                                // Show success message
                                toastr.success(data.message || 'Timetable deleted successfully');
                                
                                // Close modal and refresh calendar
                                $('#viewEventModal').modal('hide');
                                calendar.fullCalendar('refetchEvents');
                            } else {
                                // Show error message
                                toastr.error(data.message || 'Failed to delete timetable');
                            }
                        } catch (e) {
                            console.error('Error parsing response:', e);
                            toastr.error('Error processing response: ' + e.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX error:', status, error);
                        toastr.error('Server error: ' + error);
                    },
                    complete: function() {
                        // Reset button
                        $('#deleteEvent').prop('disabled', false).html('Delete');
                    }
                });
            }
        });

        // Open timetable modal with date or event
        function openTimetableModal(date, event = null) {
            $('#timetableForm')[0].reset();
            
            // Set default start and end dates
            if (event) {
                $('#timetable_id').val(event.id);
                $('#timetable_start_date').val(moment(event.start).format('YYYY-MM-DD'));
                $('#timetable_end_date').val(moment(event.end || event.start).format('YYYY-MM-DD'));
                $('#timetable_start_time').val(moment(event.start).format('HH:mm'));
                $('#timetable_end_time').val(moment(event.end || moment(event.start).add(1, 'hour')).format('HH:mm'));
                
                // Set class_id and trigger change to load sections and subjects
                $('#class_id').val(event.class_id).trigger('change');
                
                // Set teacher and wait for sections/subjects to load
                setTimeout(function() {
                    $('#section_id').val(event.section_id);
                    $('#subject_id').val(event.subject_id);
                    $('#teacher_id').val(event.teacher_id);
                }, 500);
            } else {
                $('#timetable_id').val('');
                $('#timetable_start_date').val(moment(date).format('YYYY-MM-DD'));
                $('#timetable_end_date').val(moment(date).format('YYYY-MM-DD'));
                var currentHour = moment().startOf('hour').add(1, 'hour');
                $('#timetable_start_time').val(currentHour.format('HH:mm'));
                $('#timetable_end_time').val(currentHour.add(1, 'hour').format('HH:mm'));
            }
            
            $('#timetableModal').modal('show');
        }

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

        // Save timetable button click
        $('#save_timetable').click(function(e) {
            e.preventDefault();
            
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
            var isValid = true;
            Object.keys(formData).forEach(function(key) {
                if (!formData[key] && key !== 'timetable_id') {
                    toastr.error(key.replace('_', ' ').toUpperCase() + ' is required');
                    isValid = false;
                }
            });

            if (!isValid) {
                return false;
            }

            // Show loading indicator
            $('#save_timetable').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
            
            console.log('Submitting data to: ' + '<?php echo base_url("admin/save_timetable_ajax"); ?>');
            console.log('Form data:', formData);
            
            // Submit form via AJAX
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
                            
                            // Close modal and refresh calendar
                            $('#timetableModal').modal('hide');
                            calendar.fullCalendar('refetchEvents');
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
    });
    </script> 
</body>
</html> 