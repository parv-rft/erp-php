<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo get_phrase('teacher_attendance'); ?></h4>
                </div>
            </div>
            <div class="panel-body">
                <?php echo form_open(base_url() . 'admin/teacher_attendance_view', array('class' => 'form-horizontal form-groups-bordered', 'id' => 'attendance_form')); ?>
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('date'); ?></label>
                        
                        <div class="col-sm-7">
                            <div class="input-group">
                                <input type="text" name="date" class="form-control datepicker" id="attendance_date" data-format="yyyy-mm-dd" value="<?php echo date('Y-m-d'); ?>" readonly>
                                <div class="input-group-addon" id="calendar-icon" style="background-color: #ffffff; cursor: pointer;">
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-7">
                            <button type="submit" class="btn btn-info"><?php echo get_phrase('manage_attendance'); ?></button>
                        </div>
                    </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo get_phrase('attendance_report'); ?></h4>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <?php echo form_open('', array('class' => 'form-horizontal form-groups-bordered', 'id' => 'report_form')); ?>
                            <div class="form-group">
                                <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('month'); ?></label>
                                
                                <div class="col-sm-3">
                                    <select name="month" class="form-control selectboxit" id="report_month">
                                        <?php
                                        for ($i = 1; $i <= 12; $i++):
                                            if ($i == date('m')):
                                        ?>
                                        <option value="<?php echo $i; ?>" selected><?php echo date('F', mktime(0, 0, 0, $i, 1, date('Y'))); ?></option>
                                        <?php else: ?>
                                        <option value="<?php echo $i; ?>"><?php echo date('F', mktime(0, 0, 0, $i, 1, date('Y'))); ?></option>
                                        <?php endif; endfor; ?>
                                    </select>
                                </div>
                                
                                <label for="field-2" class="col-sm-1 control-label"><?php echo get_phrase('year'); ?></label>
                                
                                <div class="col-sm-3">
                                    <select name="year" class="form-control selectboxit" id="report_year">
                                        <?php
                                        $year_list = array_combine(range(date('Y'), date('Y')-10), range(date('Y'), date('Y')-10));
                                        foreach ($year_list as $key => $value):
                                        ?>
                                        <option value="<?php echo $key; ?>" <?php if($key == date('Y')) echo 'selected'; ?>><?php echo $value; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="col-sm-2">
                                    <button type="button" class="btn btn-info" id="show_report_btn"><?php echo get_phrase('show_report'); ?></button>
                                </div>
                            </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <div id="attendance_report_container"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        // Initialize datepicker with proper format and configuration
        if(typeof $.fn.datepicker !== 'undefined') {
            $("#attendance_date").datepicker({
                format: 'yyyy-mm-dd',
                startDate: '2010-01-01',
                endDate: '2030-12-31',
                autoclose: true,
                todayHighlight: true,
                container: '#calendar-icon'
            });
            
            // Make the calendar icon clickable to open the datepicker
            $("#calendar-icon").on('click', function() {
                $("#attendance_date").datepicker('show');
            });
            
            // Apply custom styling to the datepicker
            $('head').append('<style>\
                .datepicker { background-color: white !important; color: #333 !important; border: 1px solid #ccc; }\
                .datepicker table { background-color: white !important; }\
                .datepicker table tr td.day:hover { background-color: #eee !important; color: #000 !important; }\
                .datepicker table tr td.active { background-color: #337ab7 !important; color: white !important; }\
                .datepicker-dropdown:after { border-bottom-color: white !important; }\
                .datepicker-dropdown.datepicker-orient-top:after { border-top-color: white !important; }\
            </style>');
        } else {
            console.warn('Datepicker plugin is not available');
        }
        
        // Initialize select boxes
        if(typeof $.fn.selectBoxIt !== 'undefined') {
            $('.selectboxit').selectBoxIt({
                autoWidth: false
            });
        } else {
            console.warn('SelectBoxIt plugin is not available');
        }
        
        // Show report button handler with enhanced error handling
        $("#show_report_btn").click(function() {
            var month = $("#report_month").val();
            var year = $("#report_year").val();
            
            if (month && year) {
                $('#attendance_report_container').html('<div class="text-center"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><span class="sr-only">Loading...</span></div>');
                
                $.ajax({
                    type: 'GET',
                    url: '<?php echo base_url(); ?>admin/load_teacher_attendance_report/' + month + '/' + year,
                    timeout: 30000, // 30 seconds timeout
                    success: function(response) {
                        $('#attendance_report_container').html(response);
                    },
                    error: function(xhr, status, error) {
                        var errorMessage = 'Error loading report';
                        
                        if (xhr.status === 500) {
                            errorMessage = 'Internal Server Error: The server encountered an error processing your request. Please try again or contact support.';
                        } else if (xhr.status === 404) {
                            errorMessage = 'Not Found: The requested report could not be found.';
                        } else if (xhr.status === 403) {
                            errorMessage = 'Access Denied: You do not have permission to view this report.';
                        } else if (xhr.responseText) {
                            errorMessage += ': ' + xhr.responseText;
                        } else if (error) {
                            errorMessage += ': ' + error;
                        }
                        
                        $('#attendance_report_container').html('<div class="alert alert-danger">' + 
                            '<h4><i class="fa fa-exclamation-triangle"></i> Error</h4>' +
                            '<p>' + errorMessage + '</p>' +
                            '<p>Please try again later or reload the page.</p>' +
                        '</div>');
                        
                        console.error('AJAX Error:', xhr.status, status, error, xhr.responseText);
                    }
                });
            } else {
                $('#attendance_report_container').html('<div class="alert alert-warning">' +
                    '<i class="fa fa-exclamation-circle"></i> Please select both month and year' +
                '</div>');
            }
        });
    });
</script> 