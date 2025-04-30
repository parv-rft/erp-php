<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo get_phrase('teacher_attendance_report'); ?></h4>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <form method="post" action="<?php echo base_url('teacher/attendance_report'); ?>" class="form-horizontal form-groups-bordered">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo get_phrase('class'); ?></label>
                                        <select name="class_id" class="form-control" required>
                                            <option value=""><?php echo get_phrase('select_class'); ?></option>
                                            <?php
                                                $classes = $this->db->get('class')->result_array();
                                                foreach($classes as $row):
                                            ?>
                                            <option value="<?php echo $row['class_id']; ?>" <?php if($class_id == $row['class_id']) echo 'selected'; ?>>
                                                <?php echo $row['name']; ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo get_phrase('section'); ?></label>
                                        <select name="section_id" class="form-control" required>
                                            <option value=""><?php echo get_phrase('select_section'); ?></option>
                                            <?php
                                                $sections = $this->db->get_where('section', array('class_id' => $class_id))->result_array();
                                                foreach($sections as $row):
                                            ?>
                                            <option value="<?php echo $row['section_id']; ?>" <?php if($section_id == $row['section_id']) echo 'selected'; ?>>
                                                <?php echo $row['name']; ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo get_phrase('month'); ?></label>
                                        <select name="month" class="form-control" required>
                                            <option value=""><?php echo get_phrase('select_month'); ?></option>
                                            <?php
                                            for ($i = 1; $i <= 12; $i++):
                                                if ($i == 1) $m = 'january';
                                                else if ($i == 2) $m = 'february';
                                                else if ($i == 3) $m = 'march';
                                                else if ($i == 4) $m = 'april';
                                                else if ($i == 5) $m = 'may';
                                                else if ($i == 6) $m = 'june';
                                                else if ($i == 7) $m = 'july';
                                                else if ($i == 8) $m = 'august';
                                                else if ($i == 9) $m = 'september';
                                                else if ($i == 10) $m = 'october';
                                                else if ($i == 11) $m = 'november';
                                                else if ($i == 12) $m = 'december';
                                            ?>
                                            <option value="<?php echo $i; ?>" <?php if($month == $i) echo 'selected'; ?>>
                                                <?php echo get_phrase($m); ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo get_phrase('year'); ?></label>
                                        <select name="year" class="form-control" required>
                                            <option value=""><?php echo get_phrase('select_year'); ?></option>
                                            <?php
                                            for($i = 2015; $i <= 2030; $i++):
                                            ?>
                                            <option value="<?php echo $i; ?>" <?php if($year == $i) echo 'selected'; ?>>
                                                <?php echo $i; ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12 text-right" style="margin-top: 15px;">
                                    <button type="submit" class="btn btn-info btn-rounded btn-sm">
                                        <i class="fa fa-search"></i> <?php echo get_phrase('generate_report'); ?>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <?php if($class_id != '' && $section_id != '' && $month != '' && $year != ''): ?>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="attendance-report-header">
                                <h4><?php echo get_phrase('attendance_report'); ?></h4>
                                <p><?php echo get_phrase('class'); ?>: <?php echo $this->db->get_where('class', array('class_id' => $class_id))->row()->name; ?></p>
                                <p><?php echo get_phrase('section'); ?>: <?php echo $this->db->get_where('section', array('section_id' => $section_id))->row()->name; ?></p>
                                <p><?php echo get_phrase('month'); ?>: <?php echo date("F", mktime(0, 0, 0, $month, 1, $year)); ?></p>
                                <p><?php echo get_phrase('year'); ?>: <?php echo $year; ?></p>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-bordered attendance-report-table">
                                    <thead>
                                        <tr>
                                            <th><?php echo get_phrase('students'); ?> \ <?php echo get_phrase('dates'); ?></th>
                                            <?php
                                            $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                                            for($i = 1; $i <= $days; $i++) {
                                                $date = $year . '-' . $month . '-' . $i;
                                                $day = date('D', strtotime($date));
                                                echo '<th>' . $i . '<br>' . $day . '</th>';
                                            }
                                            ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $students = $this->db->get_where('enroll', array(
                                            'class_id' => $class_id,
                                            'section_id' => $section_id,
                                            'year' => $this->db->get_where('settings', array('type' => 'running_year'))->row()->description
                                        ))->result_array();

                                        foreach($students as $row):
                                            $student = $this->db->get_where('student', array('student_id' => $row['student_id']))->row();
                                        ?>
                                        <tr>
                                            <td><?php echo $student->name; ?></td>
                                            <?php
                                            for($i = 1; $i <= $days; $i++) {
                                                $date = $year . '-' . $month . '-' . $i;
                                                $timestamp = strtotime($date);
                                                $attendance = $this->db->get_where('attendance', array(
                                                    'student_id' => $student->student_id,
                                                    'timestamp' => $timestamp
                                                ))->row();
                                                
                                                $status_class = '';
                                                $status_text = '';
                                                
                                                if($attendance) {
                                                    if($attendance->status == 1) {
                                                        $status_class = 'present';
                                                        $status_text = get_phrase('present');
                                                    } elseif($attendance->status == 2) {
                                                        $status_class = 'absent';
                                                        $status_text = get_phrase('absent');
                                                    } elseif($attendance->status == 3) {
                                                        $status_class = 'late';
                                                        $status_text = get_phrase('late');
                                                    } elseif($attendance->status == 4) {
                                                        $status_class = 'half-day';
                                                        $status_text = get_phrase('half_day');
                                                    }
                                                } else {
                                                    $status_class = 'no-record';
                                                    $status_text = '-';
                                                }
                                                
                                                echo '<td class="' . $status_class . '">' . $status_text . '</td>';
                                            }
                                            ?>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="legend" style="margin-top: 20px;">
                                <span class="present-box"></span> <?php echo get_phrase('present'); ?> &nbsp;
                                <span class="absent-box"></span> <?php echo get_phrase('absent'); ?> &nbsp;
                                <span class="late-box"></span> <?php echo get_phrase('late'); ?> &nbsp;
                                <span class="half-day-box"></span> <?php echo get_phrase('half_day'); ?> &nbsp;
                                <span class="no-record-box"></span> <?php echo get_phrase('no_record'); ?>
                            </div>
                            
                            <div class="text-right" style="margin-top: 15px;">
                                <button onclick="printDiv('print-area')" class="btn btn-primary btn-rounded btn-sm">
                                    <i class="fa fa-print"></i> <?php echo get_phrase('print_report'); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div id="print-area" style="display: none;">
    <div class="attendance-report-header">
        <h3><?php echo get_phrase('attendance_report'); ?></h3>
        <p><?php echo get_phrase('class'); ?>: <?php echo $this->db->get_where('class', array('class_id' => $class_id))->row()->name; ?></p>
        <p><?php echo get_phrase('section'); ?>: <?php echo $this->db->get_where('section', array('section_id' => $section_id))->row()->name; ?></p>
        <p><?php echo get_phrase('month'); ?>: <?php echo date("F", mktime(0, 0, 0, $month, 1, $year)); ?></p>
        <p><?php echo get_phrase('year'); ?>: <?php echo $year; ?></p>
    </div>
    
    <table class="attendance-print-table">
        <thead>
            <tr>
                <th><?php echo get_phrase('students'); ?> \ <?php echo get_phrase('dates'); ?></th>
                <?php
                if(isset($days)) {
                    for($i = 1; $i <= $days; $i++) {
                        $date = $year . '-' . $month . '-' . $i;
                        $day = date('D', strtotime($date));
                        echo '<th>' . $i . '<br>' . $day . '</th>';
                    }
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            if(isset($students)) {
                foreach($students as $row):
                    $student = $this->db->get_where('student', array('student_id' => $row['student_id']))->row();
            ?>
            <tr>
                <td><?php echo $student->name; ?></td>
                <?php
                for($i = 1; $i <= $days; $i++) {
                    $date = $year . '-' . $month . '-' . $i;
                    $timestamp = strtotime($date);
                    $attendance = $this->db->get_where('attendance', array(
                        'student_id' => $student->student_id,
                        'timestamp' => $timestamp
                    ))->row();
                    
                    $status_class = '';
                    $status_text = '';
                    
                    if($attendance) {
                        if($attendance->status == 1) {
                            $status_class = 'present';
                            $status_text = get_phrase('present');
                        } elseif($attendance->status == 2) {
                            $status_class = 'absent';
                            $status_text = get_phrase('absent');
                        } elseif($attendance->status == 3) {
                            $status_class = 'late';
                            $status_text = get_phrase('late');
                        } elseif($attendance->status == 4) {
                            $status_class = 'half-day';
                            $status_text = get_phrase('half_day');
                        }
                    } else {
                        $status_class = 'no-record';
                        $status_text = '-';
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
</div>

<style>
.attendance-report-header {
    margin-bottom: 20px;
}

.attendance-report-table {
    border-collapse: collapse;
    width: 100%;
}

.attendance-report-table th,
.attendance-report-table td {
    padding: 8px;
    text-align: center;
    border: 1px solid #ddd;
}

.attendance-report-table th {
    background-color: #f5f5f5;
}

.present, .absent, .late, .half-day, .no-record {
    font-weight: bold;
}

.present {
    background-color: #dff0d8;
    color: #3c763d;
}

.absent {
    background-color: #f2dede;
    color: #a94442;
}

.late {
    background-color: #fcf8e3;
    color: #8a6d3b;
}

.half-day {
    background-color: #d9edf7;
    color: #31708f;
}

.no-record {
    background-color: #f5f5f5;
    color: #777;
}

.legend {
    margin-top: 15px;
}

.present-box, .absent-box, .late-box, .half-day-box, .no-record-box {
    display: inline-block;
    width: 15px;
    height: 15px;
    margin-right: 5px;
    vertical-align: middle;
}

.present-box {
    background-color: #dff0d8;
}

.absent-box {
    background-color: #f2dede;
}

.late-box {
    background-color: #fcf8e3;
}

.half-day-box {
    background-color: #d9edf7;
}

.no-record-box {
    background-color: #f5f5f5;
}

.attendance-print-table {
    width: 100%;
    border-collapse: collapse;
}

.attendance-print-table th,
.attendance-print-table td {
    border: 1px solid #000;
    padding: 5px;
    text-align: center;
}
</style>

<script>
function printDiv(divId) {
    var printContents = document.getElementById(divId).innerHTML;
    var originalContents = document.body.innerHTML;
    
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
}
</script> 