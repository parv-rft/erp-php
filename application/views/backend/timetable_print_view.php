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
            padding: 8px;
            text-align: center;
            font-size: 12px;
        }
        th {
            background-color: #337ab7;
            color: white;
            text-transform: uppercase;
            font-weight: bold;
        }
        .time-col {
            background-color: #f8f9fa;
            font-weight: bold;
            width: 120px;
            color: #333;
        }
        .timetable-cell {
            height: 80px;
            vertical-align: top;
            padding: 5px !important;
            position: relative;
        }
        .class-slot {
            background: linear-gradient(135deg, #E3F2FD, #BBDEFB);
            border-radius: 4px;
            border: 1px solid #90CAF9;
            padding: 8px;
            height: 100%;
        }
        .subject-name {
            font-weight: bold;
            color: #2196F3;
            margin-bottom: 5px;
            font-size: 13px;
        }
        .teacher-name, .class-info {
            color: #666;
            font-size: 12px;
            margin-bottom: 3px;
        }
        .room-info, .room-number {
            color: #999;
            font-size: 11px;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
        }
        .time-display {
            text-align: center;
            font-weight: bold;
            padding: 8px;
            background: #f1f3f4;
            border-radius: 4px;
            margin-bottom: 5px;
            color: #333;
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
        <div class="report-title"><?php echo get_phrase('Timetable'); ?></div>
        <div class="report-subtitle">
            <?php 
            if (isset($class_name) && isset($section_name)) {
                echo get_phrase('Class').': '.$class_name.' - '.$section_name;
            } elseif (isset($teacher_name)) {
                echo get_phrase('Teacher').': '.$teacher_name;
            }
            ?>
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
    
    <table class="timetable-table">
        <thead>
            <tr>
                <th class="time-col"><?php echo get_phrase('time_slot'); ?></th>
                <th><?php echo get_phrase('monday'); ?></th>
                <th><?php echo get_phrase('tuesday'); ?></th>
                <th><?php echo get_phrase('wednesday'); ?></th>
                <th><?php echo get_phrase('thursday'); ?></th>
                <th><?php echo get_phrase('friday'); ?></th>
                <th><?php echo get_phrase('saturday'); ?></th>
                <th><?php echo get_phrase('sunday'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($timetable_data)): ?>
                <tr>
                    <td colspan="8" style="text-align:center;"><?php echo get_phrase('no_timetable_data_available'); ?></td>
                </tr>
            <?php else: ?>
                <?php 
                // Define a helper function to format time (since we can't rely on model)
                function format_time($time) {
                    if (empty($time)) return '';
                    
                    $time_parts = explode(':', $time);
                    if (count($time_parts) < 2) return $time;
                    
                    $hour = intval($time_parts[0]);
                    $minutes = $time_parts[1];
                    $ampm = ($hour >= 12) ? 'PM' : 'AM';
                    $hour = ($hour % 12) ?: 12;
                    
                    return $hour . ':' . $minutes . ' ' . $ampm;
                }
                
                // Group entries by time slot
                $timeSlots = array();
                foreach ($timetable_data as $entry) {
                    $timeKey = $entry['time_slot_start'].'-'.$entry['time_slot_end'];
                    if (!isset($timeSlots[$timeKey])) {
                        $timeSlots[$timeKey] = array(
                            'start' => $entry['time_slot_start'],
                            'end' => $entry['time_slot_end'],
                            'days' => array(
                                'monday' => null,
                                'tuesday' => null,
                                'wednesday' => null,
                                'thursday' => null,
                                'friday' => null,
                                'saturday' => null,
                                'sunday' => null
                            )
                        );
                    }
                    $timeSlots[$timeKey]['days'][strtolower($entry['day_of_week'])] = $entry;
                }
                
                // Sort time slots
                ksort($timeSlots);
                
                foreach ($timeSlots as $timeKey => $slot):
                ?>
                <tr>
                    <td class="time-col">
                        <div class="time-display">
                            <?php echo format_time($slot['start']); ?><br>to<br><?php echo format_time($slot['end']); ?>
                        </div>
                    </td>
                    
                    <?php foreach (array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday') as $day): ?>
                        <td class="timetable-cell">
                            <?php if (isset($slot['days'][$day]) && $slot['days'][$day]): 
                                $entry = $slot['days'][$day];
                            ?>
                                <div class="class-slot">
                                    <div class="subject-name"><?php echo $entry['subject_name']; ?></div>
                                    
                                    <?php if(isset($entry['teacher_name'])): ?>
                                    <div class="teacher-name"><?php echo $entry['teacher_name']; ?></div>
                                    <?php endif; ?>
                                    
                                    <?php if(isset($entry['class_name']) && isset($entry['section_name']) && !isset($teacher_name)): ?>
                                    <div class="class-info"><?php echo get_phrase('Class').' '.$entry['class_name'].' - '.$entry['section_name']; ?></div>
                                    <?php endif; ?>
                                    
                                    <div class="room-info">
                                        <i class="fa fa-map-marker"></i> <?php echo get_phrase('Room').': '.($entry['room_number'] ? $entry['room_number'] : 'N/A'); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
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