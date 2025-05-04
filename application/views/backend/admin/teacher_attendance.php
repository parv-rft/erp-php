<?php $active_sms_service = $this->db->get_where('settings', array('type' => 'active_sms_service'))->row()->description; ?>
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo get_phrase('teacher_attendance'); ?></h4>
                </div>
            </div>
            <div class="panel-body">
                <?php echo form_open(base_url() . 'admin/teacher_attendance/attendance_selector', array('class' => 'form-horizontal form-groups-bordered validate')); ?>
                <div class="row">
                    <div class="col-md-offset-3 col-md-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?php echo get_phrase('date'); ?></label>
                            <div class="col-md-9">
                                <input type="date" name="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-md-offset-3 col-md-9">
                                <button type="submit" class="btn btn-info"><?php echo get_phrase('manage_attendance'); ?></button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo get_phrase('attendance_report'); ?></h4>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-offset-3 col-md-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?php echo get_phrase('month'); ?></label>
                            <div class="col-md-9">
                                <select name="month" class="form-control" id="month">
                                    <?php
                                    for ($i = 1; $i <= 12; $i++):
                                        $month_name = date('F', mktime(0, 0, 0, $i, 1));
                                    ?>
                                    <option value="<?php echo $i; ?>" <?php if(date('n') == $i) echo 'selected'; ?>><?php echo $month_name; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?php echo get_phrase('year'); ?></label>
                            <div class="col-md-9">
                                <select name="year" class="form-control" id="year">
                                    <?php
                                    $current_year = date('Y');
                                    $start_year = $current_year - 5;
                                    $end_year = $current_year + 2;
                                    
                                    for ($i = $start_year; $i <= $end_year; $i++):
                                    ?>
                                    <option value="<?php echo $i; ?>" <?php if($current_year == $i) echo 'selected'; ?>><?php echo $i; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?php echo get_phrase('teacher'); ?></label>
                            <div class="col-md-9">
                                <select name="teacher_id" class="form-control" id="teacher_id">
                                    <option value="all"><?php echo get_phrase('all_teachers'); ?></option>
                                    <?php
                                    $teachers = $this->db->get('teacher')->result_array();
                                    foreach ($teachers as $row):
                                    ?>
                                    <option value="<?php echo $row['teacher_id']; ?>"><?php echo $row['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-md-offset-3 col-md-9">
                                <button type="button" class="btn btn-info btn-block" id="generate_report">
                                    <i class="fa fa-file-text"></i> <?php echo get_phrase('generate_report'); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if(isset($class_id) && isset($section_id)): ?>
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-info">
            <div class="panel-body table-responsive">
                <?php 
                $class_name = $this->db->get_where('class', array('class_id' => $class_id))->row()->name;
                $section_name = $this->db->get_where('section', array('section_id' => $section_id))->row()->name;
                ?>
                <h3 style="color:#696969;"><?php echo get_phrase('attendance_for'); ?>: <?php echo $class_name; ?></h3>
                <h3 style="color:#696969;"><?php echo get_phrase('section'); ?>: <?php echo $section_name; ?></h3>
                <h3 style="color:#696969;"><?php echo date('d M Y', strtotime($date)); ?></h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-info">
            <div class="panel-body table-responsive">
                <form action="<?php echo base_url(); ?>teacher/attendance_update/<?php echo $class_id . '/' . $section_id . '/' . strtotime($date); ?>" method="post">
                    <table id="example23" class="display nowrap" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th><div>#</div></th>
                                <th><div><?php echo get_phrase('image'); ?></div></th>
                                <th><div><?php echo get_phrase('name'); ?></div></th>
                                <th><div><?php echo get_phrase('sex'); ?></div></th>
                                <th><div><?php echo get_phrase('roll'); ?></div></th>
                                <th><div><?php echo get_phrase('status'); ?></div></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $students = $this->db->get_where('enroll', array(
                                'class_id' => $class_id,
                                'section_id' => $section_id,
                                'year' => $this->db->get_where('settings', array('type' => 'running_year'))->row()->description
                            ))->result_array();
                            
                            $i = 1;
                            foreach($students as $row):
                                $student = $this->db->get_where('student', array('student_id' => $row['student_id']))->row();
                                $attendance = $this->db->get_where('attendance', array(
                                    'student_id' => $student->student_id,
                                    'timestamp' => strtotime($date)
                                ))->row();
                            ?>
                            <tr class="gradeA">
                                <td><?php echo $i; ?></td>
                                <td><img src="<?php echo $this->crud_model->get_image_url('student', $student->student_id); ?>" class="img-circle" style="max-height:30px;"></td>
                                <td><?php echo $student->name; ?></td>
                                <td><?php echo $student->sex; ?></td>
                                <td><?php echo $student->roll; ?></td>
                                <td>
                                    <select name="status_<?php echo $student->student_id; ?>" class="status form-control">
                                        <option value="1" <?php if($attendance->status == 1) echo 'selected="selected"'; ?>><?php echo get_phrase('present'); ?></option>
                                        <option value="2" <?php if($attendance->status == 2) echo 'selected="selected"'; ?>><?php echo get_phrase('absent'); ?></option>
                                        <option value="3" <?php if($attendance->status == 3) echo 'selected="selected"'; ?>><?php echo get_phrase('late'); ?></option>
                                        <option value="4" <?php if($attendance->status == 4) echo 'selected="selected"'; ?>><?php echo get_phrase('half_day'); ?></option>
                                    </select>
                                </td>
                            </tr>
                            <?php 
                            $i++;
                            endforeach;
                            ?>
                        </tbody>
                    </table>

                    <div class="form-group">
                        <button type="submit" class="btn btn-info btn-block btn-rounded btn-sm">
                            <i class="fa fa-plus"></i>&nbsp;<?php echo get_phrase('save'); ?>
                        </button>
                    </div>
                </form>

                <?php if($active_sms_service == '' || $active_sms_service == 'disabled'): ?>
                    <div class="alert alert-warning">
                        <?php echo get_phrase('sms_service_not_configured'); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<style>
    div.datepicker {
        border: 1px solid #c4c4c4 !important;
    }
</style>

<script type="text/javascript">
function get_class_sections(class_id) {
    $.ajax({
        url: '<?php echo base_url(); ?>admin/get_class_section/' + class_id,
        success: function(response) {
            jQuery('#section_selector_holder').html(response);
        }
    });
}

// Generate attendance report
$(document).ready(function() {
    $('#generate_report').click(function() {
        var month = $('#month').val();
        var year = $('#year').val();
        var teacher_id = $('#teacher_id').val();
        
        if (month == "" || year == "") {
            $.toast({
                text: 'Please select month and year',
                position: 'top-right',
                loaderBg: '#f56954',
                icon: 'warning',
                hideAfter: 3500,
                stack: 6
            });
            return false;
        }
        
        console.log('Redirecting to report with month=' + month + ', year=' + year + ', teacher_id=' + teacher_id);
        
        // Redirect directly to the view page instead of going through the controller
        window.location.href = '<?php echo base_url(); ?>admin/teacher_attendance_report_view/' + month + '/' + year + '/' + teacher_id;
    });
});
</script>

