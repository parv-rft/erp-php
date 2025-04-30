<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo get_phrase('teacher_attendance'); ?> - <?php echo date('d M Y', strtotime($date)); ?></h4>
                </div>
            </div>
            <div class="panel-body">
                <?php echo form_open(base_url() . 'admin/teacher_attendance/save', array('class' => 'form-horizontal form-groups-bordered validate')); ?>
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
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $count = 1;
                                if (empty($attendance_data)) {
                                    echo '<tr><td colspan="4" class="text-center">'.get_phrase('no_teachers_found').'</td></tr>';
                                } else {
                                    foreach ($attendance_data as $row) {
                                ?>
                                <tr>
                                    <td><?php echo $count++; ?></td>
                                    <td>
                                        <?php echo $row['name']; ?>
                                        <input type="hidden" name="teacher_id[]" value="<?php echo $row['teacher_id']; ?>">
                                    </td>
                                    <td><?php echo $row['email']; ?></td>
                                    <td>
                                        <select name="status[]" class="form-control" style="width:auto;">
                                            <option value="1" <?php if (isset($row['status']) && $row['status'] == 1) echo 'selected'; ?>>Present</option>
                                            <option value="2" <?php if (isset($row['status']) && $row['status'] == 2) echo 'selected'; ?>>Absent</option>
                                            <option value="3" <?php if (isset($row['status']) && $row['status'] == 3) echo 'selected'; ?>>Late</option>
                                            <option value="0" <?php if (!isset($row['status']) || $row['status'] == 0) echo 'selected'; ?>>Undefined</option>
                                        </select>
                                    </td>
                                </tr>
                                <?php 
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-5">
                        <button type="submit" class="btn btn-info"><?php echo get_phrase('save_attendance'); ?></button>
                        <a href="<?php echo base_url(); ?>admin/teacher_attendance" class="btn btn-default"><?php echo get_phrase('go_back'); ?></a>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    // Make the status dropdown more user-friendly with colors
    $(document).ready(function() {
        $('select[name="status[]"]').each(function() {
            var value = $(this).val();
            updateSelectStyle($(this), value);
            
            $(this).change(function() {
                var newVal = $(this).val();
                updateSelectStyle($(this), newVal);
            });
        });
        
        function updateSelectStyle(select, value) {
            select.removeClass('btn-success btn-danger btn-warning');
            if (value == '1') {
                select.addClass('btn-success');
            } else if (value == '2') {
                select.addClass('btn-danger');
            } else if (value == '3') {
                select.addClass('btn-warning');
            }
        }
    });
</script> 