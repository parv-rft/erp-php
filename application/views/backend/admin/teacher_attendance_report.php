<?php $active_sms_gateway = $this->db->get_where('sms_settings' , array('type' => 'active_sms_gateway'))->row()->info; ?>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="panel-title">
                    <i class="fa fa-bar-chart"></i>
                    <?php echo get_phrase('teacher_attendance_report'); ?>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label"><?php echo get_phrase('teacher'); ?></label>
                            <select class="form-control selectboxit" name="teacher_id" id="teacher_id">
                                <option value="all"><?php echo get_phrase('all_teachers'); ?></option>
                                <?php
                                $teachers = $this->db->get('teacher')->result_array();
                                foreach($teachers as $row):
                                ?>
                                <option value="<?php echo $row['teacher_id']; ?>"><?php echo $row['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label"><?php echo get_phrase('month'); ?></label>
                            <select name="month" id="month" class="form-control selectboxit">
                                <?php
                                for ($i = 1; $i <= 12; $i++):
                                    if ($i == 1)
                                        $m = get_phrase('january');
                                    else if ($i == 2)
                                        $m = get_phrase('february');
                                    else if ($i == 3)
                                        $m = get_phrase('march');
                                    else if ($i == 4)
                                        $m = get_phrase('april');
                                    else if ($i == 5)
                                        $m = get_phrase('may');
                                    else if ($i == 6)
                                        $m = get_phrase('june');
                                    else if ($i == 7)
                                        $m = get_phrase('july');
                                    else if ($i == 8)
                                        $m = get_phrase('august');
                                    else if ($i == 9)
                                        $m = get_phrase('september');
                                    else if ($i == 10)
                                        $m = get_phrase('october');
                                    else if ($i == 11)
                                        $m = get_phrase('november');
                                    else if ($i == 12)
                                        $m = get_phrase('december');
                                    ?>
                                    <option value="<?php echo $i; ?>"
                                        <?php if($i == date('n')) echo 'selected="selected"'; ?>><?php echo $m; ?>
                                    </option>
                                    <?php
                                endfor;
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label"><?php echo get_phrase('year'); ?></label>
                            <select name="year" id="year" class="form-control selectboxit">
                                <?php
                                $running_year = $this->db->get_where('settings', array('type' => 'running_year'))->row()->description;
                                $year_array = explode('-', $running_year);
                                $current_year = date('Y');
                                $previous_year = $current_year - 1;
                                $next_year = $current_year + 1;
                                ?>
                                <option value="<?php echo $previous_year; ?>"><?php echo $previous_year; ?></option>
                                <option value="<?php echo $current_year; ?>" selected><?php echo $current_year; ?></option>
                                <option value="<?php echo $next_year; ?>"><?php echo $next_year; ?></option>
                            </select>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-success btn-lg" id="submit" onclick="return get_attendance_report();">
                    <i class="fa fa-search"></i> <?php echo get_phrase('show_report');?>
                </button>
                <button type="button" class="btn btn-primary" id="print_report" onclick="return print_attendance_report();" style="display:none;">
                    <i class="fa fa-print"></i> <?php echo get_phrase('print_report');?>
                </button>
                <a href="<?php echo base_url();?>admin/teacher_attendance" class="btn btn-default">
                    <i class="fa fa-list"></i> <?php echo get_phrase('manage_attendance');?>
                </a>
                <div id="status_message" class="alert alert-info" style="margin-top: 15px; display: none;"></div>
                <div id="report_holder"></div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
function get_attendance_report() {
    var teacher_id = $('#teacher_id').val();
    var month = $('#month').val();
    var year = $('#year').val();
    
    if(month != '' && year != '') {
        $('#status_message').html('Loading data for ' + month + '/' + year + '...').show();
        $('#report_holder').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-3x"></i><p>Loading...</p></div>');
        
        // Show selected parameters in the browser console for debugging
        console.log("Parameters:", {teacher_id: teacher_id, month: month, year: year});
        
        $.ajax({
            url: '<?php echo base_url();?>admin/load_teacher_attendance_report/' + month + '/' + year,
            type: 'GET',
            timeout: 15000, // 15 second timeout
            success: function(response) {
                console.log("Response received", response ? "length: " + response.length : "none");
                jQuery('#report_holder').html(response);
                $('#print_report').show();
                $('#status_message').hide();
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", xhr, status, error);
                $('#report_holder').html('<div class="alert alert-danger">Error loading report: ' + error + '<br>Please try again or contact administrator.</div>');
                $('#status_message').html('Failed to load report: ' + status).show();
            },
            complete: function() {
                // Ensure we're getting a response at all
                console.log("AJAX request completed");
            }
        });
    } else {
        $('#status_message').html('Please select month and year').show();
        $('#report_holder').html('<div class="alert alert-warning">Please select month and year</div>');
    }
    return false;
}

function print_attendance_report() {
    var month = $('#month').val();
    var year = $('#year').val();
    var teacher_id = $('#teacher_id').val();
    
    // Open print view in new window
    var print_url = '<?php echo base_url();?>admin/teacher_attendance_report_print_view/' + month + '/' + year;
    if (teacher_id != 'all') {
        print_url += '/' + teacher_id;
    }
    
    window.open(print_url, '_blank');
    return false;
}

// Load report on page load to ensure it works
$(document).ready(function() {
    // Uncomment this line to automatically load report when page loads
    // get_attendance_report();
});
</script>