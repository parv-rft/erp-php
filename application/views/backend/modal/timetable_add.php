<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo get_phrase('add_timetable'); ?></h4>
                </div>
            </div>
            <div class="panel-body">
                <?php echo form_open(base_url() . 'admin/timetable/create', array('class' => 'form-horizontal form-groups-bordered validate', 'id' => 'timetable_add_form')); ?>
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('class'); ?></label>
                        
                        <div class="col-sm-9">
                            <select name="class_id" class="form-control" required data-validate="required" data-message-required="<?php echo get_phrase('value_required'); ?>" onchange="get_class_sections(this.value)">
                                <option value=""><?php echo get_phrase('select_class'); ?></option>
                                <?php
                                $classes = $this->db->get('class')->result_array();
                                if (!empty($classes)):
                                    foreach ($classes as $row):
                                ?>
                                <option value="<?php echo $row['class_id']; ?>"><?php echo $row['name']; ?></option>
                                <?php 
                                    endforeach;
                                endif;
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('section'); ?></label>
                        
                        <div class="col-sm-9">
                            <select name="section_id" class="form-control" id="section_selector_holder" required data-validate="required" data-message-required="<?php echo get_phrase('value_required'); ?>">
                                <option value=""><?php echo get_phrase('select_class_first'); ?></option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="field-3" class="col-sm-3 control-label"><?php echo get_phrase('subject'); ?></label>
                        
                        <div class="col-sm-9">
                            <select name="subject_id" class="form-control" id="subject_selector_holder" required data-validate="required" data-message-required="<?php echo get_phrase('value_required'); ?>">
                                <option value=""><?php echo get_phrase('select_class_first'); ?></option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="field-4" class="col-sm-3 control-label"><?php echo get_phrase('teacher'); ?></label>
                        
                        <div class="col-sm-9">
                            <select name="teacher_id" class="form-control" required data-validate="required" data-message-required="<?php echo get_phrase('value_required'); ?>">
                                <option value=""><?php echo get_phrase('select_teacher'); ?></option>
                                <?php
                                $teachers = $this->db->get('teacher')->result_array();
                                if (!empty($teachers)):
                                    foreach ($teachers as $row):
                                ?>
                                <option value="<?php echo $row['teacher_id']; ?>"><?php echo $row['name']; ?></option>
                                <?php 
                                    endforeach;
                                endif;
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="field-5" class="col-sm-3 control-label"><?php echo get_phrase('day'); ?></label>
                        
                        <div class="col-sm-9">
                            <select name="day" class="form-control" required data-validate="required" data-message-required="<?php echo get_phrase('value_required'); ?>">
                                <option value=""><?php echo get_phrase('select_day'); ?></option>
                                <option value="Monday">Monday</option>
                                <option value="Tuesday">Tuesday</option>
                                <option value="Wednesday">Wednesday</option>
                                <option value="Thursday">Thursday</option>
                                <option value="Friday">Friday</option>
                                <option value="Saturday">Saturday</option>
                                <option value="Sunday">Sunday</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="field-6" class="col-sm-3 control-label"><?php echo get_phrase('starting_time'); ?></label>
                        
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" name="starting_time" class="form-control timepicker" required data-validate="required" data-message-required="<?php echo get_phrase('value_required'); ?>">
                                <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="field-7" class="col-sm-3 control-label"><?php echo get_phrase('ending_time'); ?></label>
                        
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" name="ending_time" class="form-control timepicker" required data-validate="required" data-message-required="<?php echo get_phrase('value_required'); ?>">
                                <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="field-8" class="col-sm-3 control-label"><?php echo get_phrase('room_number'); ?></label>
                        
                        <div class="col-sm-9">
                            <input type="text" name="room_number" class="form-control" required data-validate="required" data-message-required="<?php echo get_phrase('value_required'); ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-9">
                            <button type="submit" class="btn btn-info"><?php echo get_phrase('add_timetable'); ?></button>
                        </div>
                    </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function get_class_sections(class_id) {
        if (!class_id) {
            $('#section_selector_holder').html('<option value=""><?php echo get_phrase("select_class_first"); ?></option>');
            $('#subject_selector_holder').html('<option value=""><?php echo get_phrase("select_class_first"); ?></option>');
            return;
        }
        
        // Show loading indicator
        $('#section_selector_holder').html('<option value=""><?php echo get_phrase("loading..."); ?></option>');
        $('#subject_selector_holder').html('<option value=""><?php echo get_phrase("loading..."); ?></option>');
        
        // Load sections
        $.ajax({
            url: '<?php echo base_url(); ?>admin/get_class_section/' + class_id,
            type: 'GET',
            success: function(response) {
                $('#section_selector_holder').html(response);
            },
            error: function(xhr, status, error) {
                console.error('Error loading sections: ' + error);
                $('#section_selector_holder').html('<option value=""><?php echo get_phrase("error_loading_sections"); ?></option>');
            }
        });
        
        // Load subjects
        $.ajax({
            url: '<?php echo base_url(); ?>admin/get_class_subject/' + class_id,
            type: 'GET',
            success: function(response) {
                $('#subject_selector_holder').html(response);
            },
            error: function(xhr, status, error) {
                console.error('Error loading subjects: ' + error);
                $('#subject_selector_holder').html('<option value=""><?php echo get_phrase("error_loading_subjects"); ?></option>');
            }
        });
    }
    
    $(document).ready(function() {
        // Initialize timepicker with error handling
        try {
            if ($.fn.timepicker) {
                $('.timepicker').timepicker({
                    showMeridian: false,
                    defaultTime: false,
                    minuteStep: 5
                });
            } else {
                console.error('Timepicker plugin not available');
                // Fallback to regular input
                $('.timepicker').attr('type', 'time');
            }
        } catch(e) {
            console.error('Error initializing timepicker: ' + e.message);
            // Fallback to regular input
            $('.timepicker').attr('type', 'time');
        }
        
        // Form validation and submission
        $('#timetable_add_form').on('submit', function(e) {
            var valid = true;
            
            // Basic validation
            $(this).find('[required]').each(function() {
                if (!$(this).val()) {
                    valid = false;
                    var label = $(this).closest('.form-group').find('label').text();
                    alert('Please enter ' + label.trim());
                    $(this).focus();
                    return false;
                }
            });
            
            if (!valid) {
                e.preventDefault();
                return false;
            }
            
            return true;
        });
    });
</script> 