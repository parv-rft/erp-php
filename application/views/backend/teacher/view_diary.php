<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo $diary['title']; ?></h4>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="pull-right">
                            <a href="<?php echo base_url('teacher/edit_diary/'.$diary['diary_id']); ?>" class="btn btn-info">
                                <i class="fa fa-pencil"></i> <?php echo get_phrase('edit_diary'); ?>
                            </a>
                            <a href="<?php echo base_url('teacher/my_diaries'); ?>" class="btn btn-default">
                                <i class="fa fa-arrow-left"></i> <?php echo get_phrase('back_to_list'); ?>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="row" style="margin-top: 20px;">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label><?php echo get_phrase('title'); ?>:</label>
                            <p class="form-control-static"><?php echo $diary['title']; ?></p>
                        </div>
                        
                        <?php 
                        $this->load->model('teacher_diary_model');
                        
                        // Check if class_id and section_id fields exist in the diary entry
                        if (isset($diary['class_id']) && !empty($diary['class_id'])) {
                            $class_name = $this->teacher_diary_model->get_class_name($diary['class_id']);
                            if (!empty($class_name)): 
                        ?>
                        <div class="form-group">
                            <label><?php echo get_phrase('class'); ?>:</label>
                            <p class="form-control-static"><?php echo $class_name; ?></p>
                        </div>
                        <?php 
                            endif;
                        }
                        
                        if (isset($diary['section_id']) && !empty($diary['section_id'])) {
                            $section_name = $this->teacher_diary_model->get_section_name($diary['section_id']);
                            if (!empty($section_name)): 
                        ?>
                        <div class="form-group">
                            <label><?php echo get_phrase('section'); ?>:</label>
                            <p class="form-control-static"><?php echo $section_name; ?></p>
                        </div>
                        <?php 
                            endif;
                        }
                        ?>
                        
                        <div class="form-group">
                            <label><?php echo get_phrase('date'); ?>:</label>
                            <p class="form-control-static">
                                <?php echo date('d M, Y', strtotime($diary['date'])); ?>
                                <?php if (!empty($diary['time'])): ?>
                                    at <?php echo date('h:i A', strtotime($diary['time'])); ?>
                                <?php endif; ?>
                            </p>
                        </div>
                        
                        <div class="form-group">
                            <label><?php echo get_phrase('description'); ?>:</label>
                            <div class="well" style="white-space: pre-wrap;"><?php echo $diary['description']; ?></div>
                        </div>
                        
                        <div class="form-group">
                            <label><?php echo get_phrase('created_at'); ?>:</label>
                            <p class="form-control-static"><?php echo date('d M, Y h:i A', strtotime($diary['created_at'])); ?></p>
                        </div>
                        
                        <?php if ($diary['updated_at'] && $diary['updated_at'] != $diary['created_at']): ?>
                        <div class="form-group">
                            <label><?php echo get_phrase('last_updated'); ?>:</label>
                            <p class="form-control-static"><?php echo date('d M, Y h:i A', strtotime($diary['updated_at'])); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 