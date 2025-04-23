<?php 
// Get current year for dropdown
$current_year = date('Y'); 
?>

<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-calendar"></i>&nbsp;&nbsp;<?php echo get_phrase('Select Report Month/Year');?></div>
            <div class="panel-body">
                <?php echo form_open(base_url() . 'student/attendance_report/', array('class' => 'form-inline', 'method' => 'post')); ?>
                    <div class="form-group">
                        <label for="month"><?php echo get_phrase('Month'); ?>:</label>
                        <select name="month" class="form-control" id="month">
                            <?php for ($m = 1; $m <= 12; $m++): ?>
                                <option value="<?php echo $m; ?>" <?php if ($month == $m) echo 'selected'; ?> >
                                    <?php echo date('F', mktime(0, 0, 0, $m, 1)); ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="year"><?php echo get_phrase('Year'); ?>:</label>
                        <select name="year" class="form-control" id="year">
                            <?php for ($y = $current_year; $y >= $current_year - 5; $y--): // Show last 5 years + current ?>
                                <option value="<?php echo $y; ?>" <?php if ($year == $y) echo 'selected'; ?> >
                                    <?php echo $y; ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-info btn-sm"><?php echo get_phrase('View Report'); ?></button>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<?php 
// Only show table if month and year are set (form submitted or default used)
if (!empty($month) && !empty($year)):
?>
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i>&nbsp;&nbsp;<?php echo get_phrase('Attendance for') . ' ' . date('F Y', mktime(0, 0, 0, $month, 1, $year)); ?></div>
            <div class="panel-wrapper collapse in" aria-expanded="true">
                <div class="panel-body table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th><?php echo get_phrase('Date');?></th>
                                <th><?php echo get_phrase('Day');?></th>
                                <th><?php echo get_phrase('Status');?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            for ($i = 1; $i <= $days_in_month; $i++):
                                $full_date = $year . '-' . sprintf('%02d', $month) . '-' . sprintf('%02d', $i);
                                $timestamp = strtotime($full_date);
                                $day_name = date('l', $timestamp);
                                $status = isset($attendance_data[$full_date]) ? $attendance_data[$full_date] : null;
                            ?>
                            <tr>
                                <td><?php echo date('d M Y', $timestamp); ?></td>
                                <td><?php echo get_phrase(strtolower($day_name)); ?></td>
                                <td>
                                    <?php if ($status == '1'): ?>
                                        <span class="label label-success"><?php echo get_phrase('present');?></span>
                                    <?php elseif ($status == '2'): ?>
                                        <span class="label label-danger"><?php echo get_phrase('absent');?></span>
                                    <?php elseif ($status == '3'): ?>
                                        <span class="label label-warning"><?php echo get_phrase('holiday');?></span>
                                    <?php elseif ($status !== null): ?>
                                        <span class="label label-info"><?php echo get_phrase('status_') . $status;?></span>
                                    <?php else: ?>
                                        <span class="label label-default"><?php echo get_phrase('undefined');?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endfor; ?>
                        </tbody>
                    </table>
                    <hr>
                    <div class="text-right">
                        <h4><?php echo get_phrase('Total Present');?>: <?php echo $total_present; ?></h4>
                        <h4><?php echo get_phrase('Total Absent');?>: <?php echo $total_absent; ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?> 