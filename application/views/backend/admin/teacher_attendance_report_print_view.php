<!DOCTYPE html>
<html>
<head>
    <title><?php echo $page_title; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .present {
            background-color: #d4edda;
            color: #155724;
        }
        .absent {
            background-color: #f8d7da;
            color: #721c24;
        }
        .late {
            background-color: #fff3cd;
            color: #856404;
        }
        .legend {
            text-align: center;
            margin-top: 10px;
            margin-bottom: 20px;
        }
        .legend span {
            margin: 0 10px;
            padding: 5px 10px;
            border-radius: 3px;
        }
        .present-legend {
            background-color: #d4edda;
            color: #155724;
        }
        .absent-legend {
            background-color: #f8d7da;
            color: #721c24;
        }
        .late-legend {
            background-color: #fff3cd;
            color: #856404;
        }
        .undefined-legend {
            background-color: #f5f5f5;
            color: #333;
        }
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <h2><?php echo get_phrase('teacher_attendance_report'); ?> - <?php echo date('F Y', mktime(0, 0, 0, $month, 1, $year)); ?></h2>
    
    <table>
        <thead>
            <tr>
                <th><?php echo get_phrase('teacher'); ?></th>
                <?php
                $days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                for ($i = 1; $i <= $days_in_month; $i++) {
                    $date = $year . '-' . $month . '-' . ($i < 10 ? '0' . $i : $i);
                    $day = date('D', strtotime($date));
                    echo '<th>' . $i . ' (' . $day . ')</th>';
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php 
            if(empty($attendance_report)) {
                echo '<tr><td colspan="'.($days_in_month+1).'" style="text-align:center">'.get_phrase('no_data_found').'</td></tr>';
            } else {
                foreach ($attendance_report as $teacher_id => $data): 
            ?>
            <tr>
                <td><?php echo $data['teacher_name']; ?></td>
                <?php
                for ($i = 1; $i <= $days_in_month; $i++) {
                    $status = $data['attendance_data'][$i];
                    $status_class = '';
                    $status_text = '-';
                    
                    if ($status == 1) {
                        $status_class = 'present';
                        $status_text = 'P';
                    } elseif ($status == 2) {
                        $status_class = 'absent';
                        $status_text = 'A';
                    } elseif ($status == 3) {
                        $status_class = 'late';
                        $status_text = 'L';
                    }
                    
                    echo '<td class="' . $status_class . '">' . $status_text . '</td>';
                }
                ?>
            </tr>
            <?php 
                endforeach; 
            }
            ?>
        </tbody>
    </table>
    
    <div class="legend">
        <span class="present-legend">P = Present</span>
        <span class="absent-legend">A = Absent</span>
        <span class="late-legend">L = Late</span>
        <span class="undefined-legend">- = Undefined</span>
    </div>
    
    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print();" style="padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">
            <?php echo get_phrase('print'); ?>
        </button>
        <button onclick="window.close();" style="padding: 10px 20px; background-color: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer; margin-left: 10px;">
            <?php echo get_phrase('close'); ?>
        </button>
    </div>
</body>
</html> 