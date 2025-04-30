<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo get_phrase('edit_diary'); ?></h4>
                </div>
            </div>
            <div class="panel-body">
                <?php echo form_open(base_url() . 'teacher/my_diaries/update/' . $diary['diary_id'], array('class' => 'form-horizontal')); ?>
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo get_phrase('title'); ?></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="title" value="<?php echo $diary['title']; ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo get_phrase('class'); ?></label>
                        <div class="col-sm-9">
                            <select class="form-control" name="class_id" id="edit_class_id" onchange="load_sections_for_edit(this.value)">
                                <option value=""><?php echo get_phrase('select_class'); ?></option>
                                <?php 
                                $this->load->model('teacher_diary_model');
                                $classes = $this->teacher_diary_model->get_all_classes();
                                foreach ($classes as $class): 
                                ?>
                                    <option value="<?php echo $class['class_id']; ?>" <?php if($diary['class_id'] == $class['class_id']) echo 'selected'; ?>>
                                        <?php echo $class['name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo get_phrase('section'); ?></label>
                        <div class="col-sm-9">
                            <select class="form-control" name="section_id" id="edit_section_selector">
                                <option value=""><?php echo get_phrase('select_class_first'); ?></option>
                                <?php 
                                if($diary['class_id']) {
                                    $sections = $this->teacher_diary_model->get_sections_by_class($diary['class_id']);
                                    foreach ($sections as $section): 
                                ?>
                                    <option value="<?php echo $section['section_id']; ?>" <?php if($diary['section_id'] == $section['section_id']) echo 'selected'; ?>>
                                        <?php echo $section['name']; ?>
                                    </option>
                                <?php 
                                    endforeach;
                                } 
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo get_phrase('date'); ?></label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" name="date" value="<?php echo $diary['date']; ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo get_phrase('time'); ?></label>
                        <div class="col-sm-9">
                            <input type="time" class="form-control" name="time" value="<?php echo $diary['time']; ?>">
                            <span class="help-block"><?php echo get_phrase('optional'); ?></span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo get_phrase('description'); ?></label>
                        <div class="col-sm-9">
                            <textarea name="description" class="form-control" rows="6" required><?php echo $diary['description']; ?></textarea>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-9">
                            <button type="submit" class="btn btn-success"><?php echo get_phrase('update_diary'); ?></button>
                            <a href="<?php echo base_url('teacher/my_diaries'); ?>" class="btn btn-default"><?php echo get_phrase('cancel'); ?></a>
                        </div>
                    </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    // Function to load sections based on class selection for edit form
    function load_sections_for_edit(class_id) {
        $.ajax({
            url: '<?php echo base_url(); ?>teacher/get_sections_by_class',
            type: 'POST',
            data: {class_id: class_id},
            success: function(response) {
                $('#edit_section_selector').html(response);
            },
            error: function(xhr, status, error) {
                console.error(error);
                alert('Error loading sections. Please try again.');
            }
        });
    }
</script> 