<?php if($section_id!=null && $month!=null && $year!=null && $class_id!=null):?>

<div class="row" align="center">
                    <div class="col-sm-12">
				  	<div class="panel panel-info">
                          
						
            <div class="panel-wrapper collapse in" aria-expanded="true">
                <div class="panel-body">
								
            <h3 style="color: #696969;">Attendance Sheet</h3>
            <?php 
                $classes    =   $this->db->get('class')->result_array();
                foreach ($classes as $key => $class) {
                    if(isset($class_id) && $class_id==$class['class_id']) $class_name = $class['name'];
                }
                $sections    =   $this->db->get('section')->result_array();
                foreach ($sections as $key => $section) {
                    if(isset($section_id) && $section_id==$section['section_id']) $section_name = $section['name'];
                }
            ?>
            <?php
                $full_date = "5"."-".$month."-".$year;
                $full_date = date_create($full_date);
                $full_date = date_format($full_date,"F, Y");?>
            <h4 style="color: #696969;">Class <?php echo $class_name; ?> : Section <?php echo $section_name; ?><br><?php echo $full_date; ?></h4>

	</div>
	</div>
	</div>
	</div>
	</div>
<hr/>


<div class="row" align="center">
                    <div class="col-sm-12">
				  	<div class="panel panel-info">

                            <div class="panel-wrapper collapse in" aria-expanded="true">
                                <div class="panel-body">
                                <div align="center">
        KEYS: 
        Present&nbsp;-&nbsp; <i class="fa fa-circle" style="color: #00a651;"></i>&nbsp;&nbsp;
        Absent&nbsp;-&nbsp;<i class="fa fa-circle" style="color: #EE4749;"></i>&nbsp;&nbsp;
        Half Day&nbsp;-&nbsp; <i class="fa fa-circle" style="color: #0000FF;"></i>&nbsp;&nbsp;
        Late&nbsp;-&nbsp; <i class="fa fa-circle" style="color: #FF6600;"></i>&nbsp;&nbsp;
        Undefine&nbsp;-&nbsp;<i class="fa fa-circle" style="color: black;"></i>
        </div>
                                
    <div id="attendance-table-area">
    <table class="table table-bordered table-striped datatable">
        <thead>
            <tr>
                <th><?php echo get_phrase('student_name'); ?></th>
                <?php
                $days = date("t",mktime(0,0,0,$month,1,$year)); 
                    for ($i=1; $i <= $days; $i++) { 
                       echo '<th>' . $i . '</th>';
                    }
                ?>
                <th><?php echo get_phrase('total_present'); ?></th>
                <th><?php echo get_phrase('total_leave'); ?></th>
                <th><?php echo get_phrase('total_half_day'); ?></th>
                <th><?php echo get_phrase('total_late'); ?></th>
            </tr>
        </thead>
        <tbody>
        <?php 
            //STUDENTS ATTENDANCE
            $students   =   $this->db->get_where('student' , array('class_id'=>$class_id))->result_array();
            foreach($students as $key => $student)
            {
                $present_count = 0;
                $absent_count = 0;
                $half_day_count = 0;
                $late_count = 0;
                ?>
            <tr>
                <td><?php echo $student['name']; ?></td>
                <?php 
                for ($i=1; $i <= $days; $i++) {
                    $full_date = $year."-".$month."/".$i;
                    $verify_data  =  array('student_id' => $student['student_id'], 'date' => $full_date);
                    $attendance = $this->db->get_where('attendance' , $verify_data)->row();
                    $status     = $attendance->status;
                    $status_class = '';
                    switch ($status) {
                        case 1: 
                            $status_class = 'present';
                            $present_count++;
                            break;
                        case 2: 
                            $status_class = 'absent';
                            $absent_count++;
                            break;
                        case 3: 
                            $status_class = 'half-day';
                            $half_day_count++;
                            break;
                        case 4: 
                            $status_class = 'late';
                            $late_count++;
                            break;
                        default: 
                            $status_class = 'undefined';
                            break;
                    }
                    ?>
                    <td class="<?php echo $status_class; ?>"><i class="fa fa-circle"></i></td>
                    <?php 
                }
                ?>
                <td class="text-center"><strong><?php echo $present_count; ?></strong></td>
                <td class="text-center"><strong><?php echo $absent_count; ?></strong></td>
                <td class="text-center"><strong><?php echo $half_day_count; ?></strong></td>
                <td class="text-center"><strong><?php echo $late_count; ?></strong></td>
            </tr>
            <?php
            }
            ;?>
        </tbody>
    </table>

    <style>
    .present { color: #00a651; }
    .absent { color: #EE4749; }
    .half-day { color: #0000FF; }
    .late { color: #FF6600; }
    .undefined { color: black; }
    </style>

        <a href="#" onclick="printTableArea('attendance-table-area'); return false;" class="btn btn-success btn-sm btn-rounded btn-block" style="color:white"> <i class="fa fa-print"></i> Print</a>
		
	</div>
	</div>
	</div>
	</div>
	</div>

<script>
function printTableArea(divId) {
    var printContents = document.getElementById(divId).innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
}
</script>

<?php endif;?>