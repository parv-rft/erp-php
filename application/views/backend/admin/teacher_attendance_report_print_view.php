<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/font-awesome.min.css'); ?>">
    <style>
        body {
            font-size: 12px;
            font-family: Arial, sans-serif;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .school-logo {
            max-height: 80px;
            margin-bottom: 10px;
        }
        .school-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .report-title {
            font-size: 18px;
            margin-bottom: 5px;
        }
        .report-subtitle {
            font-size: 14px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: center;
        }
        th {
            background-color: #f5f5f5;
        }
        .present {
            background-color: #dff0d8;
        }
        .absent {
            background-color: #f2dede;
        }
        .late {
            background-color: #fcf8e3;
        }
        .halfday {
            background-color: #d9edf7;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
        }
        @media print {
            .no-print {
                display: none;
            }
            @page {
                size: landscape;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <?php 
        $system_name = $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;
        $system_address = $this->db->get_where('settings', array('type' => 'address'))->row()->description;
        ?>
        <div class="school-name"><?php echo $system_name; ?></div>
        <div class="school-address"><?php echo $system_address; ?></div>
        <div class="report-title"><?php echo get_phrase('Teacher Attendance Report'); ?></div>
        <div class="report-subtitle">
            <?php echo date('F', mktime(0, 0, 0, $month, 1, $year)) . ' ' . $year; ?>
        </div>
    </div>
    
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fa fa-print"></i> <?php echo get_phrase('print'); ?>
        </button>
        <button onclick="window.close()" class="btn btn-default">
            <i class="fa fa-times"></i> <?php echo get_phrase('close'); ?>
        </button>
    </div>
    
    <?php
    // Calculate days in month
    $number_of_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    $days = array();
    for ($i = 1; $i <= $number_of_days; $i++) {
        $days[] = $i;
    }
    ?>
    
    <table>
        <thead>
            <tr>
                <th rowspan="2"><?php echo get_phrase('teacher'); ?></th>
                <th colspan="<?php echo $number_of_days; ?>"><?php echo get_phrase('days'); ?></th>
                <th colspan="4"><?php echo get_phrase('summary'); ?></th>
            </tr>
            <tr>
                <?php foreach ($days as $day) : ?>
                    <th><?php echo $day; ?></th>
                <?php endforeach; ?>
                <th><?php echo get_phrase('present'); ?></th>
                <th><?php echo get_phrase('absent'); ?></th>
                <th><?php echo get_phrase('late'); ?></th>
                <th><?php echo get_phrase('half_day'); ?></th>
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
                            $status_class = 'present';
                            $status_icon = 'P';
                        } else if ($attendance_status == 2) {
                            $status_class = 'absent';
                            $status_icon = 'A';
                        } else if ($attendance_status == 3) {
                            $status_class = 'late';
                            $status_icon = 'L';
                        } else if ($attendance_status == 4) {
                            $status_class = 'halfday';
                            $status_icon = 'H';
                        } else {
                            $status_class = '';
                            $status_icon = '-';
                        }
                        ?>
                        <td class="<?php echo $status_class; ?>">
                            <?php echo $status_icon; ?>
                        </td>
                    <?php endforeach; ?>
                    <td class="present">
                        <?php echo $report['stats']['present']; ?>
                    </td>
                    <td class="absent">
                        <?php echo $report['stats']['absent']; ?>
                    </td>
                    <td class="late">
                        <?php echo $report['stats']['late']; ?>
                    </td>
                    <td class="halfday">
                        <?php 
                        // Half day status is 4
                        $half_day_count = 0;
                        foreach ($report['attendance_data'] as $status) {
                            if ($status == 4) $half_day_count++;
                        }
                        echo $half_day_count;
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <div class="footer">
        <p><?php echo get_phrase('generated_on'); ?>: <?php echo date('d-m-Y h:i A'); ?></p>
    </div>
    
    <script>
        // Auto print when page loads
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html> 