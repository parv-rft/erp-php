<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo get_phrase('my_diaries'); ?></h4>
                </div>
            </div>
            <div class="panel-body">
                <div class="row" style="margin-bottom: 20px;">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#addDiaryModal">
                            <i class="fa fa-plus"></i> <?php echo get_phrase('add_new_diary'); ?>
                        </button>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-bordered datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?php echo get_phrase('title'); ?></th>
                                <th><?php echo get_phrase('date'); ?></th>
                                <th><?php echo get_phrase('class'); ?></th>
                                <th><?php echo get_phrase('actions'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if(empty($diaries)): 
                            ?>
                            <tr>
                                <td colspan="5" class="text-center">
                                    <div class="alert alert-info">
                                        <?php echo get_phrase('no_diary_entries_found'); ?>. 
                                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addDiaryModal">
                                            <i class="fa fa-plus"></i> <?php echo get_phrase('create_your_first_diary'); ?>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php 
                            else:
                                $count = 1;
                                foreach ($diaries as $diary): 
                                // Get class and section name if available
                                $class_name = '';
                                if (!empty($diary['class_id'])) {
                                    $class_name = $this->db->get_where('class', array('class_id' => $diary['class_id']))->row()->name;
                                    
                                    if (!empty($diary['section_id'])) {
                                        $section_name = $this->db->get_where('section', array('section_id' => $diary['section_id']))->row()->name;
                                        $class_name .= ' - ' . $section_name;
                                    }
                                }
                            ?>
                            <tr>
                                <td><?php echo $count++; ?></td>
                                <td><?php echo $diary['title']; ?></td>
                                <td>
                                    <?php echo date('d M, Y', strtotime($diary['date'])); ?>
                                    <?php if (!empty($diary['time'])): ?>
                                        <br><small><?php echo date('h:i A', strtotime($diary['time'])); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo !empty($class_name) ? $class_name : '<span class="text-muted">'.get_phrase('not_specified').'</span>'; ?></td>
                                <td>
                                    <a href="<?php echo base_url('teacher/view_diary/'.$diary['diary_id']); ?>" class="btn btn-primary btn-xs">
                                        <i class="fa fa-eye"></i> <span style="color: white;"><?php echo get_phrase('view'); ?></span>
                                    </a>
                                    <a href="<?php echo base_url('teacher/edit_diary/'.$diary['diary_id']); ?>" class="btn btn-info btn-xs">
                                        <i class="fa fa-pencil"></i> <span style="color: white;"><?php echo get_phrase('edit'); ?></span>
                                    </a>
                                    <a href="#" onclick="confirm_modal('<?php echo base_url('teacher/my_diaries/delete/'.$diary['diary_id']); ?>');" class="btn btn-danger btn-xs">
                                        <i class="fa fa-trash"></i> <span style="color: white;"><?php echo get_phrase('delete'); ?></span>
                                    </a>
                                </td>
                            </tr>
                            <?php 
                                endforeach;
                            endif; 
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Diary Modal -->
<div class="modal fade" id="addDiaryModal" tabindex="-1" role="dialog" aria-labelledby="addDiaryModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="addDiaryModalLabel"><?php echo get_phrase('add_new_diary'); ?></h4>
            </div>
            <?php echo form_open(base_url() . 'teacher/my_diaries/create', array('class' => 'form-horizontal', 'id' => 'diary_form')); ?>
                <div class="modal-body">
                    <?php if(!empty($this->session->flashdata('error_message'))): ?>
                    <div class="alert alert-danger">
                        <?php echo $this->session->flashdata('error_message'); ?>
                    </div>
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo get_phrase('title'); ?> <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="title" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo get_phrase('class'); ?></label>
                        <div class="col-sm-9">
                            <select class="form-control" name="class_id" id="class_id" onchange="load_sections(this.value)">
                                <option value=""><?php echo get_phrase('select_class'); ?></option>
                                <?php foreach ($classes as $class): ?>
                                    <option value="<?php echo $class['class_id']; ?>"><?php echo $class['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo get_phrase('section'); ?></label>
                        <div class="col-sm-9">
                            <select class="form-control" name="section_id" id="section_selector">
                                <option value=""><?php echo get_phrase('select_class_first'); ?></option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo get_phrase('date'); ?> <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" name="date" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo get_phrase('time'); ?></label>
                        <div class="col-sm-9">
                            <input type="time" class="form-control" name="time">
                            <span class="help-block"><?php echo get_phrase('optional'); ?></span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo get_phrase('description'); ?> <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <textarea name="description" class="form-control" rows="6" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo get_phrase('close'); ?></button>
                    <button type="submit" id="submit_btn" class="btn btn-primary"><?php echo get_phrase('save_diary'); ?></button>
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        $('.datatable').DataTable();
        
        // Form validation on submit
        $('#diary_form').on('submit', function(e) {
            // Show loading indicator
            $('#submit_btn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> ' + '<?php echo get_phrase("saving"); ?>');
        });
    });
    
    // Function to load sections based on class selection
    function load_sections(class_id) {
        if (!class_id) {
            // Reset section dropdown if no class selected
            $('#section_selector').html('<option value=""><?php echo get_phrase("select_class_first"); ?></option>');
            return;
        }
        
        // Show loading indicator in the section selector
        $('#section_selector').html('<option><?php echo get_phrase("loading"); ?>...</option>');
        
        $.ajax({
            url: '<?php echo base_url(); ?>teacher/get_sections_by_class',
            type: 'POST',
            data: {class_id: class_id},
            success: function(response) {
                $('#section_selector').html(response);
            },
            error: function(xhr, status, error) {
                console.error(error);
                $('#section_selector').html('<option value=""><?php echo get_phrase("error_loading_sections"); ?></option>');
                alert('<?php echo get_phrase("error_loading_sections"); ?>. <?php echo get_phrase("please_try_again"); ?>.');
            }
        });
    }
</script> 