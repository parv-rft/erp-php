<?php $active_sms_gateway = $this->db->get_where('sms_settings' , array('type' => 'active_sms_gateway'))->row()->info; ?>

<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo get_phrase('teacher_attendance_report'); ?></h4>
                </div>
            </div>
            <div class="panel-body">
                <?php echo form_open(base_url() . 'admin/teacher_attendance_report/generate', array('class' => 'form-horizontal form-groups-bordered validate')); ?>
                <div class="row">
                    <div class="col-md-offset-1 col-md-10">
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?php echo get_phrase('month'); ?></label>
                            <div class="col-md-9">
                                <select name="month" class="form-control select2" required>
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
                                <select name="year" class="form-control select2" required>
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
                                <select name="teacher_id" class="form-control select2" required>
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
                                <button type="submit" class="btn btn-info btn-block"><?php echo get_phrase('generate_report'); ?></button>
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
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo get_phrase('report_info'); ?></h4>
                </div>
            </div>
            <div class="panel-body">
                <div class="alert alert-info">
                    <p><i class="fa fa-info-circle"></i> <?php echo get_phrase('select_month_year_and_teacher_to_generate_attendance_report'); ?></p>
                    <ul>
                        <li><?php echo get_phrase('attendance_report_will_show_daily_status_for_the_selected_month'); ?></li>
                        <li><?php echo get_phrase('you_can_select_all_teachers_or_a_specific_teacher'); ?></li>
                        <li><?php echo get_phrase('the_report_can_be_printed_for_record_keeping'); ?></li>
                    </ul>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <div align="center" style="margin-top: 20px;">
                            <h5><?php echo get_phrase('attendance_status_legend'); ?></h5>
                            <div class="attendance-legend">
                                <span class="label label-success"><i class="fa fa-check"></i> <?php echo get_phrase('present'); ?></span>
                                <span class="label label-danger"><i class="fa fa-times"></i> <?php echo get_phrase('absent'); ?></span>
                                <span class="label label-warning"><i class="fa fa-clock-o"></i> <?php echo get_phrase('late'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.attendance-legend {
    margin: 10px 0;
}
.attendance-legend span {
    margin: 0 10px;
    padding: 5px 10px;
    font-size: 14px;
}
</style>