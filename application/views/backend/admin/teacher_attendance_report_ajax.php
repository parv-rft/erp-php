<div class="panel panel-primary">
    <div class="panel-heading">
        <div class="panel-title">
            <h4><?php echo get_phrase('teacher_attendance_report'); ?> - <?php echo date('F Y', mktime(0, 0, 0, $month, 1, $year)); ?></h4>
        </div>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <?php 
                $days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                
                if(!isset($attendance_report) || empty($attendance_report)) {
                    echo '<div class="alert alert-info text-center">
                        <i class="fa fa-info-circle"></i> ' . get_phrase('no_attendance_data_found_for_this_month') . '
                    </div>';
                } else {
                ?>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th><?php echo get_phrase('teacher'); ?></th>
                                <?php
                                for ($i = 1; $i <= $days_in_month; $i++) {
                                    $date = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
                                    $day = date('D', strtotime($date));
                                    echo '<th>' . $i . ' (' . $day . ')</th>';
                                }
                                ?>
                                <th><?php echo get_phrase('present'); ?></th>
                                <th><?php echo get_phrase('absent'); ?></th>
                                <th><?php echo get_phrase('late'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($attendance_report as $teacher_data): ?>
                            <tr>
                                <td><?php echo $teacher_data['teacher_name']; ?></td>
                                <?php
                                for ($i = 1; $i <= $days_in_month; $i++) {
                                    $status = isset($teacher_data['attendance_data'][$i]) ? $teacher_data['attendance_data'][$i] : 0;
                                    $status_class = '';
                                    $status_text = '-';
                                    
                                    if ($status == 1) {
                                        $status_class = 'success';
                                        $status_text = 'P';
                                    } elseif ($status == 2) {
                                        $status_class = 'danger';
                                        $status_text = 'A';
                                    } elseif ($status == 3) {
                                        $status_class = 'warning';
                                        $status_text = 'L';
                                    }
                                    
                                    echo '<td class="' . $status_class . '">' . $status_text . '</td>';
                                }
                                // Add summary columns
                                echo '<td class="success">' . $teacher_data['stats']['present'] . '</td>';
                                echo '<td class="danger">' . $teacher_data['stats']['absent'] . '</td>';
                                echo '<td class="warning">' . $teacher_data['stats']['late'] . '</td>';
                                ?>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="legend">
                            <span class="badge badge-success">P = Present</span>
                            <span class="badge badge-danger">A = Absent</span>
                            <span class="badge badge-warning">L = Late</span>
                            <span class="badge">- = Undefined</span>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12 text-center">
                        <a href="<?php echo base_url(); ?>admin/teacher_attendance_report_print_view/<?php echo $month; ?>/<?php echo $year; ?>" target="_blank" class="btn btn-primary"><i class="fa fa-print"></i> <?php echo get_phrase('print_report'); ?></a>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div> 