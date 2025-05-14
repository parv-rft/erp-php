<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo get_phrase('Session Recovery'); ?></title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/font-awesome.min.css'); ?>">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }
        .recovery-container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .recovery-title {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }
        .recovery-message {
            margin-bottom: 20px;
            line-height: 1.6;
        }
        .btn-primary {
            background-color: #337ab7;
            border-color: #2e6da4;
        }
        .btn-warning {
            background-color: #f0ad4e;
            border-color: #eea236;
        }
        .btn-group {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            justify-content: center;
        }
    </style>
</head>
<body>
    <div class="recovery-container">
        <div class="recovery-title">
            <h2><?php echo get_phrase('Session Expired'); ?></h2>
        </div>
        
        <div class="recovery-message">
            <div class="alert alert-warning">
                <i class="fa fa-exclamation-triangle"></i> 
                <?php echo get_phrase('The print session has expired or is invalid.'); ?>
            </div>
            
            <p>
                <?php echo get_phrase('This can happen if:'); ?>
            </p>
            
            <ul>
                <li><?php echo get_phrase('Too much time has passed since you requested the print'); ?></li>
                <li><?php echo get_phrase('You refreshed the browser or restarted the system'); ?></li>
                <li><?php echo get_phrase('Your browser has privacy settings that clear session data'); ?></li>
            </ul>
            
            <p>
                <?php echo get_phrase('You have two options:'); ?>
            </p>
        </div>
        
        <div class="btn-group">
            <a href="<?php echo base_url('admin/new_student'); ?>" class="btn btn-warning">
                <i class="fa fa-arrow-left"></i> 
                <?php echo get_phrase('Return to Form'); ?>
            </a>
            
            <button id="try-recovery" class="btn btn-primary">
                <i class="fa fa-refresh"></i> 
                <?php echo get_phrase('Try Recovering Data'); ?>
            </button>
        </div>
        
        <div id="recovery-status" style="margin-top: 20px; display: none;">
            <div class="alert alert-info">
                <i class="fa fa-spinner fa-spin"></i> 
                <?php echo get_phrase('Trying to recover your form data...'); ?>
            </div>
        </div>
    </div>
    
    <script src="<?php echo base_url('assets/js/jquery-1.11.0.min.js'); ?>"></script>
    <script>
        $(document).ready(function() {
            // Try to recover data from localStorage when clicked
            $('#try-recovery').on('click', function() {
                $('#recovery-status').show();
                
                try {
                    // Check if we have the backup data in localStorage
                    var formBackup = localStorage.getItem('admission_form_backup');
                    var tempId = localStorage.getItem('admission_print_temp_id');
                    
                    if (!formBackup || !tempId) {
                        showError('<?php echo get_phrase("No backup data found in your browser."); ?>');
                        return;
                    }
                    
                    // Try to parse the backup data
                    var formData = JSON.parse(formBackup);
                    
                    // Send the recovered data to the server
                    $.ajax({
                        url: '<?php echo base_url(); ?>admin/recover_admission_print',
                        type: 'POST',
                        data: {
                            form_data: formData,
                            temp_id: tempId,
                            recovery: true
                        },
                        success: function(response) {
                            try {
                                var result = JSON.parse(response);
                                if (result.success) {
                                    // Redirect to the print view
                                    window.location.href = '<?php echo base_url(); ?>admin/admission_print_view/' + result.temp_id;
                                } else {
                                    showError(result.message || '<?php echo get_phrase("Recovery failed"); ?>');
                                }
                            } catch (e) {
                                showError('<?php echo get_phrase("Invalid response from server"); ?>');
                            }
                        },
                        error: function() {
                            showError('<?php echo get_phrase("Could not connect to server"); ?>');
                        }
                    });
                } catch (e) {
                    showError('<?php echo get_phrase("Error processing backup data"); ?>: ' + e.message);
                }
            });
            
            function showError(message) {
                $('#recovery-status').html(
                    '<div class="alert alert-danger">' +
                    '<i class="fa fa-times-circle"></i> ' +
                    message +
                    '</div>'
                );
            }
        });
    </script>
</body>
</html> 