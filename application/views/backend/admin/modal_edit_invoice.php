<?php 
$invoices	=	$this->db->get_where('invoice' , array('invoice_id' => $param2) )->result_array();
foreach($invoices as $key => $row):?>

 <div class="row">
                    <div class="col-sm-12">
				  	<div class="panel panel-info">
                            <div class="panel-heading"> <i class="fa fa-list"></i>&nbsp;&nbsp;<?php echo get_phrase('edit_invoice');?></div>
                                <div class="panel-body table-responsive">
       
        <?php echo form_open(base_url() . 'admin/student_payment/update_invoice/'. $row['invoice_id'], array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
               
					<div class="form-group">
                 	<label class="col-md-12" for="example-text"><?php echo get_phrase('student');?></label>
                    <div class="col-sm-12">
                        <select name="student_id" class="form-control select2" style="width:100%;" >
                            <?php 
                            $this->db->order_by('class_id','asc');
                            $students = $this->db->get('student')->result_array();
                            foreach($students as $key => $row2):
                            ?>
                                <option value="<?php echo $row2['student_id'];?>"
                                    <?php if($row['student_id']==$row2['student_id'])echo 'selected';?>>
                                    class <?php echo $this->crud_model->get_class_name($row2['class_id']);?> -
                                    roll <?php echo $row2['roll'];?> -
                                    <?php echo $row2['name'];?>
                                </option>
                            <?php
                            endforeach;
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                 	<label class="col-md-12" for="example-text"><?php echo get_phrase('receipt_number');?></label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" name="receipt_number" value="<?php echo $row['receipt_number'];?>"/>
                    </div>
                </div>
                
					<div class="form-group">
                 	<label class="col-md-12" for="example-text"><?php echo get_phrase('title');?></label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" name="title" value="<?php echo $row['title'];?>"/>
                    </div>
                </div>
                
					<div class="form-group">
                 	<label class="col-md-12" for="example-text"><?php echo get_phrase('description');?></label>
                    <div class="col-sm-12">
					<textarea type="text" rows="5" class="form-control" name="description" ><?php echo $row['description'];?></textarea>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-md-12" for="example-text"><?php echo get_phrase('Fee Items');?></label>
                    <div class="col-sm-12">
                        <div id="edit_fee_items_container">
                            <?php
                            // Get fee items for this invoice
                            $fee_items = $this->db->get_where('fee_items', array('invoice_id' => $row['invoice_id']))->result_array();
                            
                            if (empty($fee_items)) {
                                // If no fee items in the new table, create one from the invoice's fee_type
                                $fee_items = array(array(
                                    'fee_item_id' => 0,
                                    'fee_type' => $row['fee_type'],
                                    'amount' => $row['amount'],
                                    'discount' => $row['discount']
                                ));
                            }
                            
                            foreach ($fee_items as $index => $fee_item):
                            ?>
                            <div class="fee-item-row">
                                <div class="row" style="margin-top: 10px;">
                                    <div class="col-md-5">
                                        <select name="fee_items[<?php echo $index; ?>][fee_type]" class="form-control select2" required>
                                            <option value=""><?php echo get_phrase('select_fee_type');?></option>
                                            <option value="REGISTRATION FEE" <?php if($fee_item['fee_type'] == 'REGISTRATION FEE') echo 'selected'; ?>>REGISTRATION FEE</option>
                                            <option value="MONTHLY FEE" <?php if($fee_item['fee_type'] == 'MONTHLY FEE') echo 'selected'; ?>>MONTHLY FEE</option>
                                            <option value="ADMISSION FEE" <?php if($fee_item['fee_type'] == 'ADMISSION FEE') echo 'selected'; ?>>ADMISSION FEE</option>
                                            <option value="EXAMINATION FEES" <?php if($fee_item['fee_type'] == 'EXAMINATION FEES') echo 'selected'; ?>>EXAMINATION FEES</option>
                                            <option value="ANNUAL CHARGE" <?php if($fee_item['fee_type'] == 'ANNUAL CHARGE') echo 'selected'; ?>>ANNUAL CHARGE</option>
                                            <option value="DEVLOPMENT FUND" <?php if($fee_item['fee_type'] == 'DEVLOPMENT FUND') echo 'selected'; ?>>DEVELOPMENT FUND</option>
                                            <option value="A.C. CHARGES" <?php if($fee_item['fee_type'] == 'A.C. CHARGES') echo 'selected'; ?>>A.C. CHARGES</option>
                                            <option value="TUITION FEE" <?php if($fee_item['fee_type'] == 'TUITION FEE') echo 'selected'; ?>>TUITION FEE</option>
                                            <option value="COMPUTER-CUM-SMART CLASS" <?php if($fee_item['fee_type'] == 'COMPUTER-CUM-SMART CLASS') echo 'selected'; ?>>COMPUTER-CUM-SMART CLASS</option>
                                            <option value="READMIT CHARGE" <?php if($fee_item['fee_type'] == 'READMIT CHARGE') echo 'selected'; ?>>READMIT CHARGE</option>
                                            <option value="LATE FEE" <?php if($fee_item['fee_type'] == 'LATE FEE') echo 'selected'; ?>>LATE FEE</option>
                                            <option value="TRANSPORT FEE" <?php if($fee_item['fee_type'] == 'TRANSPORT FEE') echo 'selected'; ?>>TRANSPORT FEE</option>
                                            <option value="PTA" <?php if($fee_item['fee_type'] == 'PTA') echo 'selected'; ?>>PTA</option>
                                            <option value="SMART CLASS" <?php if($fee_item['fee_type'] == 'SMART CLASS') echo 'selected'; ?>>SMART CLASS</option>
                                            <option value="COMPUTER CLASS" <?php if($fee_item['fee_type'] == 'COMPUTER CLASS') echo 'selected'; ?>>COMPUTER CLASS</option>
                                            <option value="CHEQUE BOUNCE CHARGES" <?php if($fee_item['fee_type'] == 'CHEQUE BOUNCE CHARGES') echo 'selected'; ?>>CHEQUE BOUNCE CHARGES</option>
                                            <option value="SECURITY AND SAFETY" <?php if($fee_item['fee_type'] == 'SECURITY AND SAFETY') echo 'selected'; ?>>SECURITY AND SAFETY</option>
                                            <option value="PUPILS FUND" <?php if($fee_item['fee_type'] == 'PUPILS FUND') echo 'selected'; ?>>PUPILS FUND</option>
                                            <option value="ACTIVITIES" <?php if($fee_item['fee_type'] == 'ACTIVITIES') echo 'selected'; ?>>ACTIVITIES</option>
                                        </select>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="number" name="fee_items[<?php echo $index; ?>][amount]" class="form-control edit-fee-amount" value="<?php echo $fee_item['amount']; ?>" placeholder="Amount" required onchange="calculateEditTotalAmount()">
                                    </div>
                                    <div class="col-md-2">
                                        <?php if (isset($fee_item['fee_item_id']) && $fee_item['fee_item_id'] > 0): ?>
                                            <a href="<?php echo base_url('admin/student_payment/delete_fee_item/' . $fee_item['fee_item_id']); ?>" class="btn btn-danger btn-circle btn-xs" onclick="return confirm('Are you sure want to delete this fee item?');"><i class="fa fa-times"></i></a>
                                        <?php else: ?>
                                            <button type="button" class="btn btn-danger btn-circle btn-xs" onclick="removeEditFeeItem(this)" style="<?php echo (count($fee_items) <= 1) ? 'display:none;' : ''; ?>"><i class="fa fa-times"></i></button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div style="margin-top: 10px;">
                            <button type="button" class="btn btn-info btn-sm btn-rounded" onclick="addEditFeeItem()">
                                <i class="fa fa-plus"></i> <?php echo get_phrase('Add Fee Item');?>
                            </button>
                            
                            <?php if ($row['invoice_id']): ?>
                            <a href="#" data-toggle="modal" data-target="#addFeeItemModal" class="btn btn-success btn-sm btn-rounded">
                                <i class="fa fa-plus"></i> <?php echo get_phrase('Add New Fee Type');?>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                 	<label class="col-md-12" for="example-text"><?php echo get_phrase('total_amount');?></label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="edit_total_amount" name="amount" value="<?php echo $row['amount'];?>" readonly/>
                    </div>
                </div>
				
				<div class="form-group"> 
					 <label class="col-sm-12"><?php echo get_phrase('amount_you_have_paid');?>*</label>        
					 <div class="col-sm-12">
		                    <input type="text" class="form-control" name="amount_paid" value="<?php echo $row['amount_paid'];?>" readonly/>
		                </div>
		            </div>
					
                <div class="form-group">
                 	<label class="col-md-12" for="example-text"><?php echo get_phrase('status');?></label>
                    <div class="col-sm-12">
                        <select name="status" class="form-control select2" style="width:100%">
                            <option value="1" <?php if($row['status']== '1')echo 'selected';?>><?php echo get_phrase('paid');?></option>
                            <option value="2" <?php if($row['status']== '2')echo 'selected';?>><?php echo get_phrase('unpaid');?></option>
                        </select>
                    </div>
                </div>
               <div class="form-group">
                 	<label class="col-md-12" for="example-text"><?php echo get_phrase('date');?></label>
                    <div class="col-sm-12">
							<input class="form-control m-r-10" name="date" type="date" value="<?php echo $row['creation_timestamp']; ?>" id="example-date-input" required>
                    </div>

                </div>
                <div class="form-group">
                 
                      <button type="submit" class="btn btn-block  btn-info btn-sm"><i class="fa fa-plus"></i>&nbsp;<?php echo get_phrase('save_now');?></button>
                 
                </div>
        </form>
        
				</div>
				</div>
				</div>
				</div>

<!-- Modal for adding a new fee item -->
<div class="modal fade" id="addFeeItemModal" tabindex="-1" role="dialog" aria-labelledby="addFeeItemModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="addFeeItemModalLabel"><?php echo get_phrase('Add New Fee Item'); ?></h5>
            </div>
            <div class="modal-body">
                <?php echo form_open(base_url() . 'admin/student_payment/add_fee_item/' . $row['invoice_id'], array('class' => 'form-horizontal form-groups-bordered validate', 'target' => '_top')); ?>
                    <div class="form-group">
                        <label class="col-md-12" for="example-text"><?php echo get_phrase('Fee Type'); ?></label>
                        <div class="col-sm-12">
                            <select name="fee_type" class="form-control select2" required>
                                <option value=""><?php echo get_phrase('select_fee_type'); ?></option>
                                <option value="REGISTRATION FEE">REGISTRATION FEE</option>
                                <option value="MONTHLY FEE">MONTHLY FEE</option>
                                <option value="ADMISSION FEE">ADMISSION FEE</option>
                                <option value="EXAMINATION FEES">EXAMINATION FEES</option>
                                <option value="ANNUAL CHARGE">ANNUAL CHARGE</option>
                                <option value="DEVLOPMENT FUND">DEVELOPMENT FUND</option>
                                <option value="A.C. CHARGES">A.C. CHARGES</option>
                                <option value="TUITION FEE">TUITION FEE</option>
                                <option value="COMPUTER-CUM-SMART CLASS">COMPUTER-CUM-SMART CLASS</option>
                                <option value="READMIT CHARGE">READMIT CHARGE</option>
                                <option value="LATE FEE">LATE FEE</option>
                                <option value="TRANSPORT FEE">TRANSPORT FEE</option>
                                <option value="PTA">PTA</option>
                                <option value="SMART CLASS">SMART CLASS</option>
                                <option value="COMPUTER CLASS">COMPUTER CLASS</option>
                                <option value="CHEQUE BOUNCE CHARGES">CHEQUE BOUNCE CHARGES</option>
                                <option value="SECURITY AND SAFETY">SECURITY AND SAFETY</option>
                                <option value="PUPILS FUND">PUPILS FUND</option>
                                <option value="ACTIVITIES">ACTIVITIES</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12" for="example-text"><?php echo get_phrase('Amount'); ?></label>
                        <div class="col-sm-12">
                            <input type="number" class="form-control" name="amount" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12" for="example-text"><?php echo get_phrase('Discount'); ?></label>
                        <div class="col-sm-12">
                            <input type="number" class="form-control" name="discount" value="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-info btn-block btn-rounded btn-sm"><?php echo get_phrase('Add Fee Item'); ?></button>
                        </div>
                    </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
// Counter for fee items
var editFeeItemCounter = <?php echo count($fee_items); ?>;

// Function to add a new fee item row
function addEditFeeItem() {
    var container = document.getElementById('edit_fee_items_container');
    var newRow = document.createElement('div');
    newRow.className = 'fee-item-row';
    newRow.innerHTML = `
        <div class="row" style="margin-top: 10px;">
            <div class="col-md-5">
                <select name="fee_items[${editFeeItemCounter}][fee_type]" class="form-control select2" required>
                    <option value=""><?php echo get_phrase('select_fee_type');?></option>
                    <option value="REGISTRATION FEE">REGISTRATION FEE</option>
                    <option value="MONTHLY FEE">MONTHLY FEE</option>
                    <option value="ADMISSION FEE">ADMISSION FEE</option>
                    <option value="EXAMINATION FEES">EXAMINATION FEES</option>
                    <option value="ANNUAL CHARGE">ANNUAL CHARGE</option>
                    <option value="DEVLOPMENT FUND">DEVELOPMENT FUND</option>
                    <option value="A.C. CHARGES">A.C. CHARGES</option>
                    <option value="TUITION FEE">TUITION FEE</option>
                    <option value="COMPUTER-CUM-SMART CLASS">COMPUTER-CUM-SMART CLASS</option>
                    <option value="READMIT CHARGE">READMIT CHARGE</option>
                    <option value="LATE FEE">LATE FEE</option>
                    <option value="TRANSPORT FEE">TRANSPORT FEE</option>
                    <option value="PTA">PTA</option>
                    <option value="SMART CLASS">SMART CLASS</option>
                    <option value="COMPUTER CLASS">COMPUTER CLASS</option>
                    <option value="CHEQUE BOUNCE CHARGES">CHEQUE BOUNCE CHARGES</option>
                    <option value="SECURITY AND SAFETY">SECURITY AND SAFETY</option>
                    <option value="PUPILS FUND">PUPILS FUND</option>
                    <option value="ACTIVITIES">ACTIVITIES</option>
                </select>
            </div>
            <div class="col-md-5">
                <input type="number" name="fee_items[${editFeeItemCounter}][amount]" class="form-control edit-fee-amount" placeholder="Amount" required onchange="calculateEditTotalAmount()">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger btn-circle btn-xs" onclick="removeEditFeeItem(this)"><i class="fa fa-times"></i></button>
            </div>
        </div>
    `;
    container.appendChild(newRow);
    editFeeItemCounter++;
    
    // Show delete buttons since we have more than one item
    var deleteButtons = document.querySelectorAll('#edit_fee_items_container .btn-danger');
    if (deleteButtons.length > 1) {
        deleteButtons.forEach(function(button) {
            button.style.display = 'block';
        });
    }
    
    // Initialize select2 for the new row
    $(container).find('.select2').select2();
}

// Function to remove a fee item row
function removeEditFeeItem(button) {
    var row = button.closest('.fee-item-row');
    row.parentNode.removeChild(row);
    calculateEditTotalAmount();
    
    // Hide delete buttons if only one row remains
    var deleteButtons = document.querySelectorAll('#edit_fee_items_container .btn-danger');
    if (deleteButtons.length <= 1) {
        deleteButtons[0].style.display = 'none';
    }
}

// Function to calculate total amount
function calculateEditTotalAmount() {
    var total = 0;
    var amountInputs = document.querySelectorAll('.edit-fee-amount');
    amountInputs.forEach(function(input) {
        if (input.value) {
            total += parseFloat(input.value);
        }
    });
    document.getElementById('edit_total_amount').value = total.toFixed(2);
}

// Initialize calculation
document.addEventListener('DOMContentLoaded', function() {
    calculateEditTotalAmount();
});
</script>

<?php endforeach;?>