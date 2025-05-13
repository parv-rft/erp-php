<div class="row">
                    <div class="col-sm-12">
				  	<div class="panel panel-info">
                            <div class="panel-heading"> <i class="fa fa-list"></i>&nbsp;&nbsp;<?php echo get_phrase('unpaid_invoices');?></div>
                            <div class="panel-wrapper collapse in" aria-expanded="true">
                                <div class="panel-body table-responsive">
                                
                                <div class="row" style="margin-bottom: 15px;">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="invoice_search"><?php echo get_phrase('search_by_student');?></label>
                                            <input type="text" class="form-control" id="invoice_search" placeholder="<?php echo get_phrase('enter_student_name');?>">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="invoice_sort_by"><?php echo get_phrase('sort_by');?></label>
                                            <select class="form-control" id="invoice_sort_by" onchange="sortInvoiceHistory()">
                                                <option value=""><?php echo get_phrase('select_sorting_option');?></option>
                                                <option value="amount_asc"><?php echo get_phrase('amount');?> (<?php echo get_phrase('ascending');?>)</option>
                                                <option value="amount_desc"><?php echo get_phrase('amount');?> (<?php echo get_phrase('descending');?>)</option>
                                                <option value="date_asc"><?php echo get_phrase('date');?> (<?php echo get_phrase('ascending');?>)</option>
                                                <option value="date_desc"><?php echo get_phrase('date');?> (<?php echo get_phrase('descending');?>)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
			
 								<table id="example23" class="display nowrap" cellspacing="0" width="100%">
                	<thead>
                		<tr>
                			<th>#</th>
                    		<th><div><?php echo get_phrase('student');?></div></th>
                    		<th><div><?php echo get_phrase('title');?></div></th>
                    		<th><div><?php echo get_phrase('description');?></div></th>
                            <th><div><?php echo get_phrase('total');?></div></th>
                            <th><div><?php echo get_phrase('paid');?></div></th>
                    		<th><div><?php echo get_phrase('date');?></div></th>
                    		<th><div><?php echo get_phrase('payment_status');?></div></th>
                    		<th><div><?php echo get_phrase('options');?></div></th>
						</tr>
					</thead>
                    <tbody>
                    	<?php
                    		$count = 1;
                    		$this->db->where('status' , '2');
                    		$this->db->order_by('creation_timestamp' , 'desc');
                    		$invoices = $this->db->get('invoice')->result_array();
                    		foreach($invoices as $key => $row):
                    	?>
                        <tr>
                        	<td><?php echo $count++;?></td>
							<td><?php echo $this->crud_model->get_type_name_by_id('student', $row['student_id']);?></td>
							<td><?php echo $row['title'];?></td>
							<td><?php echo $row['description'];?></td>
							<td><?php echo $this->db->get_where('settings', array('type' => 'currency'))->row()->description; ?><?php echo number_format($row['amount'],2,".",",");?></td>
                            <td><?php echo $this->db->get_where('settings', array('type' => 'currency'))->row()->description; ?><?php echo number_format($row['amount_paid'],2,".",",");?></td>
							<td><?php echo $row['creation_timestamp'];?></td>
							<td>
                            <span class="label label-<?php if($row['status']=='1')echo 'success'; elseif ($row['status']=='2') echo 'danger'; else echo 'warning';?>">
                            <?php if($row ['status'] == '1'):?>
                            <?php echo 'Paid';?>
                            <?php endif;?>

                            <?php if($row ['status'] == '2'):?>
                            <?php echo 'Unpaid';?>
                            <?php endif;?>
                            </span>
							</td>

							<td>
							<?php if ($row['due'] != 0):?>
							<a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_take_payment/<?php echo $row['invoice_id'];?>');"><button type="button" class="btn btn-info btn-circle btn-xs"><i class="fa fa-credit-card"></i></button></a>
							<?php endif;?>
							 
							<a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_view_invoice/<?php echo $row['invoice_id'];?>');"><button type="button" class="btn btn-warning btn-circle btn-xs"><i class="fa fa-print"></i></button></a>
							 <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_edit_invoice/<?php echo $row['invoice_id'];?>');"><button type="button" class="btn btn-success btn-circle btn-xs"><i class="fa fa-edit"></i></button></a>
							<a href="#" onclick="confirm_modal('<?php echo base_url();?>admin/student_payment/delete_invoice/<?php echo $row['invoice_id'];?>');"><button type="button" class="btn btn-danger btn-circle btn-xs"><i class="fa fa-times"></i></button></a>
							
                           
        					</td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
					
				</div>
				</div>
				</div>
				</div>
				</div>
				
 <div class="row">
                    <div class="col-sm-12">
				  	<div class="panel panel-info">
                            <div class="panel-heading"> <i class="fa fa-list"></i>&nbsp;&nbsp;<?php echo get_phrase('payment_history');?></div>
                            <div class="panel-wrapper collapse in" aria-expanded="true">
                                <div class="panel-body table-responsive">
                                
                                <div class="row" style="margin-bottom: 15px;">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="student_search"><?php echo get_phrase('search_by_student');?></label>
                                            <input type="text" class="form-control" id="student_search" placeholder="<?php echo get_phrase('enter_student_name');?>">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="sort_by"><?php echo get_phrase('sort_by');?></label>
                                            <select class="form-control" id="sort_by" onchange="sortPaymentHistory()">
                                                <option value=""><?php echo get_phrase('select_sorting_option');?></option>
                                                <option value="amount_asc"><?php echo get_phrase('amount');?> (<?php echo get_phrase('ascending');?>)</option>
                                                <option value="amount_desc"><?php echo get_phrase('amount');?> (<?php echo get_phrase('descending');?>)</option>
                                                <option value="date_asc"><?php echo get_phrase('date');?> (<?php echo get_phrase('ascending');?>)</option>
                                                <option value="date_desc"><?php echo get_phrase('date');?> (<?php echo get_phrase('descending');?>)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

<table id="payment_history_table" class="display nowrap" cellspacing="0" width="100%">
					    <thead>
					        <tr>
					            <th><div>#</div></th>
					            <th><div><?php echo get_phrase('student');?></div></th>
					            <th><div><?php echo get_phrase('title');?></div></th>
					            <th><div><?php echo get_phrase('description');?></div></th>
					            <th><div><?php echo get_phrase('method');?></div></th>
					            <th><div><?php echo get_phrase('amount');?></div></th>
					            <th><div><?php echo get_phrase('date');?></div></th>
					            <th><div><?php echo get_phrase('actions');?></div></th>
					        </tr>
					    </thead>
					    <tbody>
					        <?php 
					        	$count = 1;
					        	$this->db->where('payment_type' , 'income');
					        	$this->db->order_by('timestamp' , 'desc');
					        	$payments = $this->db->get('payment')->result_array();
					        	foreach ($payments as $key => $row):
					        ?>
					        <tr>
					            <td><?php echo $count++;?></td>
					            <td><?php echo $this->crud_model->get_type_name_by_id('student', $row['student_id']);?></td>
					            <td><?php echo $row['title'];?></td>
					            <td><?php echo $row['description'];?></td>
					            <td>
					            	<?php 
					            		if ($row['method'] == 1)
					            			echo get_phrase('cash');
					            		if ($row['method'] == 2)
					            			echo get_phrase('cheque');
					            		if ($row['method'] == 3)
					            			echo get_phrase('card');
					                    if ($row['method'] == 'paypal')
					                    	echo 'paypal';
					            	    ?>
					            </td>
					            <td data-amount="<?php echo $row['amount']; ?>"><?php echo $this->db->get_where('settings', array('type' => 'currency'))->row()->description; ?><?php echo number_format($row['amount'],2,".",",");?></td>
					            <td data-timestamp="<?php echo $row['timestamp']; ?>"><?php echo date('d M,Y', $row['timestamp']);?></td>
					            <td >
			                <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_view_invoice/<?php echo $row['invoice_id'];?>');"> <button type="button" class="btn btn-info btn-circle btn-xs"><i class="fa fa-print"></i></button></a>	            	
					            </td>
					        </tr>
					        <?php endforeach;?>
					    </tbody>
					</table>
					
<script type="text/javascript">
$(document).ready(function() {
    // Initialize payment history table with export buttons
    $('#payment_history_table').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        "paging": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false
    });
    
    // Enable search by student name manually since we're using DataTables
    $("#student_search").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        var table = $('#payment_history_table').DataTable();
        table.search(value).draw();
    });

    // Enable search by student name for invoices
    $("#invoice_search").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        var table = $('#example23').DataTable();
        table.search(value).draw();
    });
});

function sortPaymentHistory() {
    var sortBy = $("#sort_by").val();
    var table = $('#payment_history_table').DataTable();
    
    // Sort based on selected option
    if (sortBy === "amount_asc") {
        table.order([5, 'asc']).draw(); // Column index 5 is amount
    } else if (sortBy === "amount_desc") {
        table.order([5, 'desc']).draw();
    } else if (sortBy === "date_asc") {
        table.order([6, 'asc']).draw(); // Column index 6 is date
    } else if (sortBy === "date_desc") {
        table.order([6, 'desc']).draw();
    }
}

function sortInvoiceHistory() {
    var sortBy = $("#invoice_sort_by").val();
    var table = $('#example23').DataTable();
    
    // Sort based on selected option
    if (sortBy === "amount_asc") {
        table.order([4, 'asc']).draw(); // Column index 4 is amount
    } else if (sortBy === "amount_desc") {
        table.order([4, 'desc']).draw();
    } else if (sortBy === "date_asc") {
        table.order([6, 'asc']).draw(); // Column index 6 is date
    } else if (sortBy === "date_desc") {
        table.order([6, 'desc']).draw();
    }
}
</script>
						
			
</div>
				</div>
				</div>
				</div>
				</div>