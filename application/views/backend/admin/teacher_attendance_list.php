<?php
$attendance_status = array();
foreach($attendance_data as $row) {
    $attendance_status[$row['teacher_id']] = $row['status'];
}
?>
<hr>
<div class="row">
    <div class="col-md-12">
        <form method="post" action="<?php echo base_url();?>index.php?admin/teacher_attendance/take_attendance/">
            <input type="hidden" name="date" value="<?php echo $date;?>">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th><?php echo get_phrase('teacher_name');?></th>
                        <th><?php echo get_phrase('status');?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $count = 1;
                    foreach($teachers as $row):
                    ?>
                    <tr>
                        <td><?php echo $count++;?></td>
                        <td>
                            <?php echo $row['name'];?>
                            <input type="hidden" name="teacher_id[]" value="<?php echo $row['teacher_id'];?>">
                        </td>
                        <td>
                            <select class="form-control" name="status[]">
                                <option value="0" <?php if(isset($attendance_status[$row['teacher_id']]) && $attendance_status[$row['teacher_id']] == 0) echo 'selected';?>><?php echo get_phrase('undefined');?></option>
                                <option value="1" <?php if(isset($attendance_status[$row['teacher_id']]) && $attendance_status[$row['teacher_id']] == 1) echo 'selected';?>><?php echo get_phrase('present');?></option>
                                <option value="2" <?php if(isset($attendance_status[$row['teacher_id']]) && $attendance_status[$row['teacher_id']] == 2) echo 'selected';?>><?php echo get_phrase('absent');?></option>
                            </select>
                        </td>
                    </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
            <center>
                <button type="submit" class="btn btn-success">
                    <i class="fa fa-check"></i> <?php echo get_phrase('save_attendance');?>
                </button>
            </center>
        </form>
    </div>
</div> 