<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title">
                    <i class="fa fa-certificate"></i> <?php echo get_phrase('Transfer Certificates'); ?>
                </div>
            </div>
            <div class="panel-wrapper collapse in" aria-expanded="true">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12 text-right" style="margin-bottom: 20px;">
                            <a href="<?php echo base_url(); ?>admin/transfer_certificate/add" class="btn btn-info btn-rounded btn-sm text-white">
                                <i class="fa fa-plus"></i> <?php echo get_phrase('Add Transfer Certificate'); ?>
                            </a>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table id="certificates_table" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th><?php echo get_phrase('TC No'); ?></th>
                                    <th><?php echo get_phrase('Student Name'); ?></th>
                                    <th><?php echo get_phrase('Admission No'); ?></th>
                                    <th><?php echo get_phrase('Class'); ?></th>
                                    <th><?php echo get_phrase('Issue Date'); ?></th>
                                    <th><?php echo get_phrase('Actions'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($certificates as $row): ?>
                                <tr>
                                    <td><?php echo $row['tc_no']; ?></td>
                                    <td><?php echo $row['student_name']; ?></td>
                                    <td><?php echo $row['admission_number']; ?></td>
                                    <td><?php echo $row['student_class']; ?></td>
                                    <td><?php echo date('d M Y', strtotime($row['date_of_issue'])); ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <?php echo get_phrase('Actions'); ?> <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-right">
                                                <li>
                                                    <a href="<?php echo base_url(); ?>admin/transfer_certificate/print/<?php echo $row['tc_id']; ?>" target="_blank">
                                                        <i class="fa fa-print"></i> <?php echo get_phrase('Print'); ?>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<?php echo base_url(); ?>admin/transfer_certificate/edit/<?php echo $row['tc_id']; ?>">
                                                        <i class="fa fa-edit"></i> <?php echo get_phrase('Edit'); ?>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#" onclick="confirm_modal('<?php echo base_url(); ?>admin/transfer_certificate/delete/<?php echo $row['tc_id']; ?>');">
                                                        <i class="fa fa-trash"></i> <?php echo get_phrase('Delete'); ?>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#certificates_table').DataTable({
            responsive: true
        });
    });
</script> 