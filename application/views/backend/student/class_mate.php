					
            <div class="row">
                    <div class="col-sm-12">
				  	<div class="panel panel-info">
                            <div class="panel-heading"> <i class="fa fa-users"></i>&nbsp;&nbsp;<?php echo get_phrase('Classmates');?></div>
                            <div class="panel-wrapper collapse in" aria-expanded="true">
                                <div class="panel-body table-responsive">
			
                                <table id="example23" class="display nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th><div><?php echo get_phrase('name');?></div></th>
                            <th><div><?php echo get_phrase('phone');?></div></th>
                            <th><div><?php echo get_phrase('email');?></div></th>
                            <th><div><?php echo get_phrase('gender');?></div></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($classmates as $row): ?>
                        <tr>
                            <td><?php echo $row['name'];?></td>
                            <td><?php echo $row['phone'] ? $row['phone'] : '-'; ?></td>
                            <td><?php echo $row['email'] ? $row['email'] : '-'; ?></td>
                            <td><?php echo $row['sex'] ? $row['sex'] : '-'; ?></td>
                        </tr>
                    <?php endforeach; ?>
						
                    </tbody>
                </table>



                </div>
            </div>
        </div>
    </div>
</div>
