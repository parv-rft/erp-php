<div class="row">
    <div class="col-sm-12">
        <!-- Display flash messages -->
        <?php if ($this->session->flashdata('flash_message')): ?>
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php echo $this->session->flashdata('flash_message'); ?>
        </div>
        <?php endif; ?>
        
        <?php if ($this->session->flashdata('error_message')): ?>
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php echo $this->session->flashdata('error_message'); ?>
        </div>
        <?php endif; ?>
        
        <!-- Add alert about database update -->
        <div class="alert alert-info">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Note:</strong> If you encounter a "Unknown column 'remark'" error, reload this page to fix the issue.
        </div>
        
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo get_phrase('teacher_attendance'); ?> | <?php echo date('d M, Y', strtotime($date)); ?></h4>
                </div>
            </div>
            <div class="panel-body">
                <form id="attendance_form" action="<?php echo base_url(); ?>admin/teacher_attendance/take_attendance" method="post" class="form-horizontal form-groups-bordered validate">
                    <input type="hidden" name="date" value="<?php echo $date; ?>">
                
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th><?php echo get_phrase('name'); ?></th>
                                        <th><?php echo get_phrase('email'); ?></th>
                                        <th><?php echo get_phrase('status'); ?></th>
                                        <th><?php echo get_phrase('remark'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 1;
                                    $teachers = $this->db->get('teacher')->result_array();
                                    
                                    foreach ($teachers as $row):
                                        // Check if attendance record exists for this teacher on this date
                                        $attendance_query = $this->db->get_where('teacher_attendance', array(
                                            'teacher_id' => $row['teacher_id'],
                                            'date' => $date
                                        ));
                                        
                                        // Handle missing remark column safely
                                        if ($attendance_query->num_rows() > 0) {
                                            $attendance_row = $attendance_query->row_array();
                                            // Check if remark column exists
                                            $remark = isset($attendance_row['remark']) ? $attendance_row['remark'] : '';
                                            $status = isset($attendance_row['status']) ? $attendance_row['status'] : 0;
                                        } else {
                                            $remark = '';
                                            $status = 0;
                                        }
                                    ?>
                                    <tr>
                                        <td><?php echo $count++; ?></td>
                                        <td>
                                            <?php echo $row['name']; ?>
                                        </td>
                                        <td>
                                            <?php echo $row['email']; ?>
                                        </td>
                                        <td>
                                            <select name="status_<?php echo $row['teacher_id']; ?>" class="form-control">
                                                <option value="1" <?php if($status == 1) echo 'selected'; ?>><?php echo get_phrase('present'); ?></option>
                                                <option value="2" <?php if($status == 2) echo 'selected'; ?>><?php echo get_phrase('absent'); ?></option>
                                                <option value="3" <?php if($status == 3) echo 'selected'; ?>><?php echo get_phrase('late'); ?></option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="remark_<?php echo $row['teacher_id']; ?>" value="<?php echo $remark; ?>" class="form-control" placeholder="<?php echo get_phrase('remark'); ?>">
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-info btn-block" id="submit_attendance">
                                <i class="fa fa-check"></i> <?php echo get_phrase('save_attendance'); ?>
                            </button>
                        </div>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    // Form submission handler with validation
    $('#attendance_form').on('submit', function(e) {
        console.log('Form submitted');
        
        // Optional: Show loading state
        $('#submit_attendance').html('<i class="fa fa-spinner fa-spin"></i> Saving...');
        $('#submit_attendance').attr('disabled', 'disabled');
        
        // Let the form submit normally after validation
        return true;
    });
});
</script> 