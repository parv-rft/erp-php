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
                    <div class="col-md-6">
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
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label"><?php echo get_phrase('year'); ?></label>
                            <select name="year" id="year" class="form-control selectboxit">
                                <?php
                                for ($i = 2019; $i <= 2050; $i++) {
                                    $selected = ($i == date('Y')) ? 'selected' : '';
                                ?>
                                    <option value="<?php echo $i; ?>" <?php echo $selected; ?>><?php echo $i; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="row" style="margin-top: 20px;">
                    <div class="col-md-12 text-center">
                        <div class="btn-group" role="group" style="display: inline-flex;">
                            <button type="button" class="btn btn-primary btn-lg" style="width: 180px; height: 45px;" onclick="get_attendance_report()">
                                <i class="fa fa-search"></i> <?php echo get_phrase('show_report'); ?>
                            </button>
                            <button type="button" class="btn btn-success btn-lg" id="print_report" style="width: 180px; height: 45px; margin-left: 10px;" onclick="return print_attendance_report();">
                                <i class="fa fa-print"></i> <?php echo get_phrase('print_report');?>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div id="status_message" class="alert" style="display:none; margin-top: 15px;"></div>
                <div id="ajax_loading" style="display:none; text-align:center; margin-top: 20px; margin-bottom: 20px;">
                    <i class="fa fa-spinner fa-spin fa-3x"></i>
                    <p><?php echo get_phrase('loading'); ?>...</p>
                </div>
                <div id="report_holder" style="margin-top: 20px;"></div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
function get_attendance_report() {
    var month = $('#month').val();
    var year = $('#year').val();
    
    if(month != '' && year != '') {
        $('#report_holder').empty();
        $('#ajax_loading').show();
        $('#print_report').hide();
        $('#status_message').hide();
        
        console.log('Requesting teacher attendance report for: ' + month + '/' + year);
        
        $.ajax({
            url: '<?php echo base_url();?>admin/load_teacher_attendance_report/' + month + '/' + year,
            type: 'GET',
            dataType: 'html',
            timeout: 30000, // 30 second timeout
            beforeSend: function() {
                console.log('AJAX request started');
            },
            success: function(response) {
                $('#ajax_loading').hide();
                console.log('AJAX request successful');
                
                if (response.trim() === '') {
                    $('#status_message').html('Empty response received from server').addClass('alert-warning').show();
                    $('#report_holder').html('<div class="alert alert-warning">No data received from server</div>');
                } else {
                    $('#report_holder').html(response);
                    $('#print_report').show();
                }
            },
            error: function(xhr, status, error) {
                $('#ajax_loading').hide();
                console.error("AJAX Error:", status, error);
                
                // Try to get more detailed error information
                var errorMessage = error || 'Unknown error';
                var additionalInfo = '';
                
                if (xhr.responseText) {
                    // Try to extract specific error from response
                    console.log('Response Text:', xhr.responseText);
                    if (xhr.responseText.indexOf('Fatal error') !== -1) {
                        additionalInfo = '<br>PHP Fatal Error detected. Check server logs.';
                    } else if (xhr.responseText.indexOf('Warning') !== -1) {
                        additionalInfo = '<br>PHP Warning detected. Check server logs.';
                    } else if (xhr.responseText.indexOf('Notice') !== -1) {
                        additionalInfo = '<br>PHP Notice detected. Check server logs.';
                    } else if (xhr.responseText.length > 200) {
                        additionalInfo = '<br>Large error response received. Check server logs.';
                    } else if (xhr.responseText.length > 0) {
                        additionalInfo = '<br>Server response: ' + xhr.responseText.substring(0, 200);
                    }
                }
                
                $('#status_message').html('Error: ' + errorMessage + additionalInfo).addClass('alert-danger').show();
                $('#report_holder').html('<div class="alert alert-danger">' +
                    '<h4><i class="fa fa-exclamation-triangle"></i> Error!</h4>' +
                    '<p>Failed to load attendance report. Please try again or contact administrator.</p>' +
                    '<p>Error details: ' + status + ' - ' + errorMessage + '</p>' +
                    '</div>'
                );
                $('#print_report').hide();
            },
            complete: function() {
                console.log('AJAX request completed');
            }
        });
    } else {
        $('#status_message').html('Please select month and year').addClass('alert-info').show();
        $('#report_holder').html('<div class="alert alert-info">Please select month and year</div>');
        $('#print_report').hide();
    }
}

function print_attendance_report() {
    var month = $('#month').val();
    var year = $('#year').val();
    
    if(month != '' && year != '') {
        try {
            var printUrl = '<?php echo base_url();?>admin/teacher_attendance_report_print_view/' + month + '/' + year;
            console.log('Opening print view: ' + printUrl);
            window.open(printUrl, '_blank');
        } catch (e) {
            console.error('Error opening print view:', e);
            alert('Error opening print view: ' + e.message);
        }
    } else {
        alert('<?php echo get_phrase("please_select_month_and_year_first"); ?>');
    }
    return false;
}

// Initialize the page
$(document).ready(function() {
    $('.selectboxit').selectBoxIt({
        showFirstOption: true
    });
    
    // Auto-hide any status messages after 5 seconds
    setTimeout(function() {
        $('.alert-dismissible').fadeOut('slow');
    }, 5000);
    
    // Add click handlers for debugging
    $('#print_report').on('click', function() {
        console.log('Print report button clicked');
    });
});
</script>