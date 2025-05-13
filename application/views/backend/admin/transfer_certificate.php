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
                                            <button type="button" class="btn btn-info btn-sm" onclick="showCertificateView(<?php echo $row['tc_id']; ?>)">
                                                <i class="fa fa-eye"></i> <?php echo get_phrase('View'); ?>
                                            </button>
                                            <a href="<?php echo base_url(); ?>admin/transfer_certificate/edit/<?php echo $row['tc_id']; ?>" class="btn btn-primary btn-sm text-white">
                                                <i class="fa fa-edit"></i> <?php echo get_phrase('Edit'); ?>
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm" onclick="confirm_modal('<?php echo base_url(); ?>admin/transfer_certificate/delete/<?php echo $row['tc_id']; ?>');">
                                                <i class="fa fa-trash"></i> <?php echo get_phrase('Delete'); ?>
                                            </button>
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
    
    // Function to show certificate view in modal popup
    function showCertificateView(tc_id) {
        // Create a modal dialog with print and close buttons
        var modal = 
        '<div class="modal fade" id="certificateViewModal" tabindex="-1" role="dialog" aria-labelledby="certificateViewModalLabel">' +
            '<div class="modal-dialog modal-lg" role="document">' +
                '<div class="modal-content">' +
                    '<div class="modal-header">' +
                        '<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="$(\'#certificateViewModal\').modal(\'hide\');"><span aria-hidden="true">&times;</span></button>' +
                        '<h4 class="modal-title" id="certificateViewModalLabel">Transfer Certificate</h4>' +
                    '</div>' +
                    '<div class="modal-body" style="max-height: 70vh; overflow-y: auto;">' +
                        '<div id="certificate_details_content">Loading...</div>' +
                    '</div>' +
                    '<div class="modal-footer">' +
                        '<button type="button" class="btn btn-default" onclick="$(\'#certificateViewModal\').modal(\'hide\');">Close</button>' +
                        '<button type="button" class="btn btn-primary" onclick="printCertificateDetails()"><i class="fa fa-print"></i> Print</button>' +
                    '</div>' +
                '</div>' +
            '</div>' +
        '</div>';
        
        // Append modal to body if it doesn't exist
        if (!$('#certificateViewModal').length) {
            $('body').append(modal);
        }
        
        // Show the modal
        $('#certificateViewModal').modal('show');
        
        // Load certificate details via AJAX
        $.ajax({
            url: '<?php echo base_url();?>admin/get_certificate_details/' + tc_id,
            type: 'GET',
            success: function(response) {
                $('#certificate_details_content').html(response);
            },
            error: function() {
                $('#certificate_details_content').html('<div class="alert alert-danger">Error loading certificate details</div>');
            }
        });
    }
    
    function printCertificateDetails() {
        var printContents = document.getElementById('certificate_details_content').innerHTML;
        var originalContents = document.body.innerHTML;
        
        // Create print window
        document.body.innerHTML = '<div class="container">' + printContents + '</div>';
        
        // Add print styles
        var style = document.createElement('style');
        style.type = 'text/css';
        style.innerHTML = '@media print { ' +
            'body { font-family: Arial, sans-serif; }' +
            '.details td { padding: 8px 0; vertical-align: top; }' +
            '.details td:first-child { width: 200px; font-weight: bold; }' +
            '.no-print { display: none !important; }' +
            'body { margin: 0; padding: 15px; }' +
        '}';
        document.head.appendChild(style);
        
        // Print and restore
        window.print();
        document.body.innerHTML = originalContents;
    }
</script> 