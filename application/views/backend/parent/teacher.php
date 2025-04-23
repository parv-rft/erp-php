					
            <div class="row">
                    <div class="col-sm-12">
				  	<div class="panel panel-info">
                            <div class="panel-heading"> <i class="fa fa-list"></i>&nbsp;&nbsp;<?php echo get_phrase('list_teachers');?></div>
                            <div class="panel-wrapper collapse in" aria-expanded="true">
                                <div class="panel-body table-responsive">
			
                                <table id="example23" class="display nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th width="80"><div><?php echo get_phrase('Photo');?></div></th>
                            <th><div><?php echo get_phrase('Name');?></div></th>
                            <th><div><?php echo get_phrase('Role');?></div></th>
                            <th><div><?php echo get_phrase('Email');?></div></th>
                            <th><div><?php echo get_phrase('Phone');?></div></th>
                            <th><div><?php echo get_phrase('Gender');?></div></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($select_teacher as $key => $teacher): ?>
                        <tr>
                            <td><img src="<?php echo $this->crud_model->get_image_url('teacher', $teacher['teacher_id']);?>" class="img-circle" width="30px"></td>
                            <td><?php echo $teacher['name'];?></td>
                            <td>
                           <?php
                              if($teacher['role']== 1) echo get_phrase('Class Teacher');
                              elseif($teacher['role']== 2) echo get_phrase('Subject Teacher');
                              else echo get_phrase('Staff');
                           ?>
                            </td>
                            <td><?php echo $teacher['email'];?></td>
                            <td><?php echo $teacher['phone'];?></td>
                            <td><?php echo $teacher['sex'];?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($select_teacher)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center;"><?php echo get_phrase('No teachers found.');?></td>
                        </tr>
                    <?php endif; ?>
						
                    </tbody>
                </table>



                </div>
            </div>
        </div>
    </div>
</div>
