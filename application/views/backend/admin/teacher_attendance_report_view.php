<?php
$days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
$attendance_query = $this->db->get_where('teacher_attendance', array(
    'year' => $year,
    'month' => $month
));

if($teacher_id != 'all') {
    $this->db->where('teacher_id', $teacher_id);
}

$attendance_data = $attendance_query->result_array();
$teachers = $this->db->get('teacher')->result_array();

$attendance_array = array();
foreach($attendance_data as $row) {
    $attendance_array[$row['teacher_id']][$row['day']] = $row['status'];
}
?>

<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th><?php echo get_phrase('teacher');?></th>
                    <?php
                    for($i = 1; $i <= $days; $i++) {
                        echo '<th>' . $i . '</th>';
                    }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach($teachers as $row):
                    if($teacher_id == 'all' || $teacher_id == $row['teacher_id']):
                ?>
                <tr>
                    <td><?php echo $row['name'];?></td>
                    <?php
                    for($i = 1; $i <= $days; $i++) {
                        $attendance_status = isset($attendance_array[$row['teacher_id']][$i]) ? 
                            $attendance_array[$row['teacher_id']][$i] : 0;
                        
                        $status_class = '';
                        if($attendance_status == 1) {
                            $status_class = 'success';
                        } else if($attendance_status == 2) {
                            $status_class = 'danger';
                        }
                        
                        echo '<td class="' . $status_class . '">';
                        if($attendance_status == 1) {
                            echo '<i class="fa fa-check"></i>';
                        } else if($attendance_status == 2) {
                            echo '<i class="fa fa-times"></i>';
                        } else {
                            echo '-';
                        }
                        echo '</td>';
                    }
                    ?>
                </tr>
                <?php
                    endif;
                endforeach;
                ?>
            </tbody>
        </table>
        
        <hr>
        <div class="row">
            <div class="col-md-12 text-right">
                <div class="label label-success"><?php echo get_phrase('present');?></div>
                <div class="label label-danger"><?php echo get_phrase('absent');?></div>
                <div class="label label-default"><?php echo get_phrase('not_marked');?></div>
            </div>
        </div>
    </div>
</div> 