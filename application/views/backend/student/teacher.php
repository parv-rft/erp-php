					
            <div class="row">
                    <div class="col-sm-12">
				  	<div class="panel panel-info">
                            <div class="panel-heading"> <i class="fa fa-users"></i>&nbsp;&nbsp;<?php echo get_phrase('class_teachers');?></div>
                            <div class="panel-wrapper collapse in" aria-expanded="true">
                                <div class="panel-body table-responsive">
			
                                <table id="example23" class="display nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th width="80"><div><?php echo get_phrase('photo');?></div></th>
                            <th><div><?php echo get_phrase('name');?></div></th>
                            <th><div><?php echo get_phrase('role');?></div></th>
                            <th><div><?php echo get_phrase('email');?></div></th>
                            <th><div><?php echo get_phrase('phone');?></div></th>
                            <th><div><?php echo get_phrase('gender');?></div></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($teachers as $row): ?>
                        <tr>
                            <td><img src="<?php echo $this->crud_model->get_image_url('teacher', $row['teacher_id']);?>" class="img-circle" width="30px"></td>
                            <td><?php echo $row['name'];?></td>
                            <td>
                                
                           <?php if($row['role']== 1) echo get_phrase('class_teacher');?>
                           <?php if($row['role']== 2) echo get_phrase('subject_teacher');?>
                        
                            </td>
                            <td><?php echo $row['email'] ? $row['email'] : '-';?></td>
                            <td><?php echo $row['phone'] ? $row['phone'] : '-';?></td>
                            <td><?php echo $row['sex'] ? $row['sex'] : '-';?></td>

                           
                        </tr>

                        <?php endforeach; ?>
						
                    </tbody>
                </table>



                </div>
            </div>
        </div>
    </div>
</div>
