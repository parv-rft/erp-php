<?php
// Calculate days in month and month name
$number_of_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
$month_name = date('F', mktime(0, 0, 0, $month, 1, $year));
$days = array();
for ($i = 1; $i <= $number_of_days; $i++) {
    $days[] = $i;
}
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title"><?php echo get_phrase('teacher_attendance_report_for'); ?> <?php echo $month_name . ' ' . $year; ?></h4>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th><?php echo get_phrase('teacher'); ?></th>
                        <?php foreach ($days as $day) : ?>
                            <th class="text-center"><?php echo $day; ?></th>
                        <?php endforeach; ?>
                        <th width="100" class="text-center"><?php echo get_phrase('present'); ?></th>
                        <th width="100" class="text-center"><?php echo get_phrase('absent'); ?></th>
                        <th width="100" class="text-center"><?php echo get_phrase('late'); ?></th>
                        <th width="100" class="text-center"><?php echo get_phrase('half_day'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($attendance_report as $teacher_id => $report) : ?>
                        <tr>
                            <td><?php echo $report['teacher_name']; ?></td>
                            <?php foreach ($days as $day) : ?>
                                <?php
                                $attendance_status = isset($report['attendance_data'][$day]) ? $report['attendance_data'][$day] : 0;
                                $status_class = '';
                                $status_icon = '';

                                if ($attendance_status == 1) {
                                    $status_class = 'success';
                                    $status_icon = '<i class="fa fa-check"></i>';
                                } else if ($attendance_status == 2) {
                                    $status_class = 'danger';
                                    $status_icon = '<i class="fa fa-times"></i>';
                                } else if ($attendance_status == 3) {
                                    $status_class = 'warning';
                                    $status_icon = '<i class="fa fa-clock-o"></i>';
                                } else if ($attendance_status == 4) {
                                    $status_class = 'info';
                                    $status_icon = '<i class="fa fa-adjust"></i>';
                                } else {
                                    $status_class = 'default';
                                    $status_icon = '-';
                                }
                                ?>
                                <td class="text-center bg-<?php echo $status_class; ?>">
                                    <?php echo $status_icon; ?>
                                </td>
                            <?php endforeach; ?>
                            <td class="text-center">
                                <span class="badge badge-success">
                                    <?php echo $report['stats']['present']; ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-danger">
                                    <?php echo $report['stats']['absent']; ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-warning">
                                    <?php echo $report['stats']['late']; ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-info">
                                    <?php 
                                    // Half day status is 4
                                    $half_day_count = 0;
                                    foreach ($report['attendance_data'] as $status) {
                                        if ($status == 4) $half_day_count++;
                                    }
                                    echo $half_day_count;
                                    ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title"><?php echo get_phrase('legend'); ?></h4>
    </div>
    <div class="panel-body">
        <span class="badge badge-success"><?php echo get_phrase('present'); ?></span>
        <span class="badge badge-danger"><?php echo get_phrase('absent'); ?></span>
        <span class="badge badge-warning"><?php echo get_phrase('late'); ?></span>
        <span class="badge badge-info"><?php echo get_phrase('half_day'); ?></span>
        <span class="badge badge-default"><?php echo get_phrase('undefined'); ?></span>
    </div>
</div> 