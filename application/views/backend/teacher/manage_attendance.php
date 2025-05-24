<?php $active_sms_service = $this->db->get_where('settings', array('type' => 'active_sms_service'))->row()->description; ?>
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i>&nbsp;&nbsp;<?php echo get_phrase('attendance'); ?>
            </div>
            <div class="panel-body table-responsive">
                <?php echo form_open(base_url() . 'teacher/attendance_selector', array('class' => 'form-horizontal form-groups-bordered validate', 'target' => '_top', 'enctype' => 'multipart/form-data')); ?>
                
                <div class="form-group">
                    <label class="col-md-12" for="example-text"><?php echo get_phrase('class'); ?></label>
                    <div class="col-sm-12">
                        <select name="class_id" id="class_id" class="form-control select2" onchange="return get_class_sections(this.value)">
                            <option value=""><?php echo get_phrase('select_class'); ?></option>
                            <?php 
                            $class = $this->db->get('class')->result_array();
                            foreach($class as $key => $class): ?>
                                <option value="<?php echo $class['class_id']; ?>" <?php if(isset($class_id) && $class_id == $class['class_id']) echo 'selected="selected"'; ?>>
                                    <?php echo $class['name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-12" for="example-text"><?php echo get_phrase('section'); ?></label>
                    <div class="col-sm-12">
                        <select name="section_id" class="form-control select2" id="section_selector_holder">
                            <option value=""><?php echo get_phrase('select_class_first'); ?></option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-12" for="example-text"><?php echo get_phrase('date'); ?></label>
                    <div class="col-sm-12">
                        <input type="date" class="form-control datepicker" name="timestamp" value="<?php echo isset($date) ? date('Y-m-d', strtotime(str_replace('/', '-', $date))) : date('Y-m-d'); ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-info btn-block btn-rounded btn-sm">
                        <i class="fa fa-search"></i>&nbsp;<?php echo get_phrase('get_student'); ?>
                    </button>
                </div>
                </form>
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
        url: '<?php echo base_url(); ?>teacher/get_sections_by_class/' + class_id,
        success: function(response) {
            jQuery('#section_selector_holder').html(response);
        }
    });
}
</script>

