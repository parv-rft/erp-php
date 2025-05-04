<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4>
                        <?php echo get_phrase('teacher_attendance_report'); ?> | 
                        <?php echo date('F Y', mktime(0, 0, 0, $month, 1, $year)); ?>
                        <?php if ($teacher_id != 'all'): ?>
                            | <?php echo $this->db->get_where('teacher', array('teacher_id' => $teacher_id))->row()->name; ?>
                        <?php endif; ?>
                    </h4>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th><?php echo get_phrase('name'); ?></th>
                                        <?php
                                        $days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                                        for ($i = 1; $i <= $days_in_month; $i++): 
                                            $date = $year . '-' . sprintf('%02d', $month) . '-' . sprintf('%02d', $i);
                                            $day = date('D', strtotime($date));
                                        ?>
                                        <th><?php echo $i; ?><br/><?php echo $day; ?></th>
                                        <?php endfor; ?>
                                        <th><?php echo get_phrase('present'); ?></th>
                                        <th><?php echo get_phrase('absent'); ?></th>
                                        <th><?php echo get_phrase('late'); ?></th>
                                        <th><?php echo get_phrase('total'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 1;
                                    
                                    // Get teachers based on teacher_id parameter
                                    if ($teacher_id == 'all') {
                                        $teachers = $this->db->get('teacher')->result_array();
                                    } else {
                                        $teachers = $this->db->get_where('teacher', array('teacher_id' => $teacher_id))->result_array();
                                    }
                                    
                                    foreach ($teachers as $row):
                                        $teacher_id = $row['teacher_id'];
                                        $present_count = 0;
                                        $absent_count = 0;
                                        $late_count = 0;
                                    ?>
                                    <tr>
                                        <td><?php echo $count++; ?></td>
                                        <td><?php echo $row['name']; ?></td>
                                        <?php
                                        for ($i = 1; $i <= $days_in_month; $i++): 
                                            $date = $year . '-' . sprintf('%02d', $month) . '-' . sprintf('%02d', $i);
                                            
                                            // Get attendance for this date
                                            $attendance_query = $this->db->get_where('teacher_attendance', array(
                                                'teacher_id' => $teacher_id,
                                                'date' => $date
                                            ));
                                            
                                            $attendance = $attendance_query->num_rows() > 0 ? $attendance_query->row() : null;
                                            $status = $attendance ? $attendance->status : 0;
                                            
                                            // Update counters
                                            if ($status == 1) $present_count++;
                                            else if ($status == 2) $absent_count++;
                                            else if ($status == 3) $late_count++;
                                            
                                            $status_class = '';
                                            $status_icon = '';
                                            
                                            if ($status == 1) {
                                                $status_class = 'success';
                                                $status_icon = '<i class="fa fa-check"></i>';
                                            } else if ($status == 2) {
                                                $status_class = 'danger';
                                                $status_icon = '<i class="fa fa-times"></i>';
                                            } else if ($status == 3) {
                                                $status_class = 'warning';
                                                $status_icon = '<i class="fa fa-clock-o"></i>';
                                            } else {
                                                $status_class = 'default';
                                                $status_icon = '<i class="fa fa-circle-o"></i>';
                                            }
                                        ?>
                                        <td class="text-center">
                                            <span class="label label-<?php echo $status_class; ?>">
                                                <?php echo $status_icon; ?>
                                            </span>
                                        </td>
                                        <?php endfor; ?>
                                        <td><?php echo $present_count; ?></td>
                                        <td><?php echo $absent_count; ?></td>
                                        <td><?php echo $late_count; ?></td>
                                        <td><?php echo $present_count + $absent_count + $late_count; ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <div class="row">
                    <div class="col-md-12 text-center">
                        <a href="<?php echo base_url(); ?>admin/teacher_attendance_report" class="btn btn-primary">
                            <i class="fa fa-arrow-left"></i> <?php echo get_phrase('back'); ?>
                        </a>
                        <button onclick="window.print();" class="btn btn-success">
                            <i class="fa fa-print"></i> <?php echo get_phrase('print'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    // Print specific area of the page
    function printDiv() {
        var printContents = document.getElementById('print_area').innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script> 