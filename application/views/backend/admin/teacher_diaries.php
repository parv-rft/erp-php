<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo get_phrase('all_teacher_diaries'); ?></h4>
                </div>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?php echo get_phrase('title'); ?></th>
                                <th><?php echo get_phrase('teacher'); ?></th>
                                <th><?php echo get_phrase('class'); ?></th>
                                <th><?php echo get_phrase('section'); ?></th>
                                <th><?php echo get_phrase('date'); ?></th>
                                <th><?php echo get_phrase('actions'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if(empty($diaries)): 
                            ?>
                            <tr>
                                <td colspan="7" class="text-center">
                                    <div class="alert alert-info">
                                        <?php echo get_phrase('no_diary_entries_found'); ?>. 
                                        <?php echo get_phrase('teachers_need_to_create_diary_entries_first'); ?>.
                                    </div>
                                </td>
                            </tr>
                            <?php 
                            else:
                                $count = 1;
                                $this->load->model('teacher_diary_model');
                                foreach ($diaries as $diary): 
                                
                                // Handle class name
                                $class_name = '';
                                if (isset($diary['class_id']) && !empty($diary['class_id'])) {
                                    $class_name = $this->teacher_diary_model->get_class_name($diary['class_id']);
                                }
                                
                                // Handle section name
                                $section_name = '';
                                if (isset($diary['section_id']) && !empty($diary['section_id'])) {
                                    $section_name = $this->teacher_diary_model->get_section_name($diary['section_id']);
                                }
                            ?>
                            <tr>
                                <td><?php echo $count++; ?></td>
                                <td><?php echo $diary['title']; ?></td>
                                <td><?php echo $diary['teacher_name']; ?></td>
                                <td><?php echo !empty($class_name) ? $class_name : '<span class="label label-default">'.get_phrase('not_specified').'</span>'; ?></td>
                                <td><?php echo !empty($section_name) ? $section_name : '<span class="label label-default">'.get_phrase('not_specified').'</span>'; ?></td>
                                <td>
                                    <?php echo date('d M, Y', strtotime($diary['date'])); ?>
                                    <?php if (!empty($diary['time'])): ?>
                                        <br><small><?php echo date('h:i A', strtotime($diary['time'])); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?php echo base_url('admin/view_teacher_diary/'.$diary['diary_id']); ?>" class="btn btn-primary btn-xs">
                                        <i class="fa fa-eye"></i> <span style="color: white;"><?php echo get_phrase('view'); ?></span>
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

<script type="text/javascript">
    jQuery(document).ready(function($) {
        $('.datatable').DataTable();
    });
</script> 