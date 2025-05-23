<div class="row">
    <div class="col-sm-6">
		<div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-plus"></i>&nbsp;&nbsp;<?php echo get_phrase('Create Single Invoice');?></div>
                    <div class="panel-body table-responsive">
			
    <!----CREATION FORM STARTS---->

    <?php echo form_open(base_url() . 'admin/student_payment/single_invoice' , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
                
            <div class="form-group">
                 	<label class="col-md-12" for="example-text"><?php echo get_phrase('Invoice Number');?></label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" name="invoice_number" value="<?php echo rand(10000, 1000000). 'INV'. date('Y');?>" / required>
                </div>
            </div>

            <div class="form-group">
                 	<label class="col-md-12" for="example-text"><?php echo get_phrase('Receipt Number');?></label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" name="receipt_number" value="<?php echo 'RCPT'. rand(1000, 9999) . date('Ymd');?>" / required>
                </div>
            </div>

            <div class="form-group">
                 	<label class="col-md-12" for="example-text"><?php echo get_phrase('Payment Title');?></label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" name="title" / required>
                </div>
            </div>

            <div class="form-group">
                 	<label class="col-md-12" for="example-text"><?php echo get_phrase('Admission Number');?></label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" name="admission_number" id="admission_number" onchange="get_student_details_by_admission(this.value)" required>
                </div>
            </div>

            <div class="form-group">
                 	<label class="col-md-12" for="example-text"><?php echo get_phrase('Student Name');?></label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" name="student_name" id="student_name" readonly>
                </div>
            </div>

            <div class="form-group">
                 	<label class="col-md-12" for="example-text"><?php echo get_phrase('Class');?></label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" name="class_name" id="class_name" readonly>
                </div>
            </div>

            <input type="hidden" name="student_id" id="student_id" value="">
            <input type="hidden" name="class_id" id="class_id" value="">

            <div class="form-group">
                <label class="col-md-12" for="example-text"><?php echo get_phrase('Fee Items');?></label>
                <div class="col-sm-12">
                    <div id="fee_items_container">
                        <!-- Fee items will be added here dynamically -->
                        <div class="fee-item-row">
                            <div class="row">
                                <div class="col-md-5">
                                    <select name="fee_items[0][fee_type]" class="form-control select2" required>
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
                                    <input type="number" name="fee_items[0][amount]" class="form-control fee-amount" placeholder="Amount" required onchange="calculateTotalAmount()">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger btn-circle btn-xs" onclick="removeFeeItem(this)" style="display:none;"><i class="fa fa-times"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-info btn-sm btn-rounded" onclick="addFeeItem()">
                        <i class="fa fa-plus"></i> <?php echo get_phrase('Add Fee Item');?>
                    </button>
                </div>
            </div>

			<div class="form-group">
                <label class="col-md-12" for="example-text"><?php echo get_phrase('select_date');?></label>
                <div class="col-sm-12">
                 	<input type="date" name="creation_timestamp" value="<?php echo date('Y-m-d');?>" class="form-control datepicker" id="example-date-input" required>
                </div>
            </div>

            <div class="form-group">
                 	<label class="col-md-12" for="discount_type_text_single"><?php echo get_phrase('Payment Discount Type');?></label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" name="discount_type" id="discount_type_text_single" list="discount_suggestions_single" placeholder="<?php echo get_phrase('enter_or_select_discount_type');?>" oninput="calculateTotalAmount()">
                    <datalist id="discount_suggestions_single">
                        <option value="<?php echo get_phrase('no_discount');?>">
                        <option value="<?php echo get_phrase('sibling_discount');?>">
                        <option value="<?php echo get_phrase('parent_is_campus_employee_discount');?>">
                    </datalist>
                </div>
            </div>

            <div class="form-group">
                 	<label class="col-md-12" for="example-text"><?php echo get_phrase('Payment Discount');?> %</label>
                <div class="col-sm-12">
                    <input type="number" class="form-control" name="discount" value="0" onchange="calculateTotalAmount()" onkeyup="calculateTotalAmount()">
                </div>
            </div>
            <div class="form-group">
                 	<label class="col-md-12" for="example-text"><?php echo get_phrase('Total Amount');?></label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" id="total_amount" name="amount" readonly>
                </div>
            </div>

            <div class="form-group">
                 	<label class="col-md-12" for="example-text"><?php echo get_phrase('Amount Paid');?></label>
                <div class="col-sm-12">
                    <input type="number" class="form-control" name="amount_paid" value="0" oninput="calculateTotalAmount()">
                </div>
            </div>

            <div class="form-group">
                 	<label class="col-md-12" for="example-text"><?php echo get_phrase('Remaining Amount');?></label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" id="remaining_amount" name="remaining_amount" readonly>
                </div>
            </div>

								
			<div class="form-group">
                 	<label class="col-md-12" for="example-text"><?php echo get_phrase('Payment Status');?></label>
                <div class="col-sm-12">
                    <select name="status" class="form-control select2" required>
                    <option value=""><?php echo get_phrase('payment_status');?></option>
                    <option value="1">Paid</option>
                    <option value="2">Unpaid</option>
                   </select>

                </div>
            </div>

            <div class="form-group">
                 	<label class="col-md-12" for="example-text"><?php echo get_phrase('Payment Method');?></label>
                <div class="col-sm-12">
                    <select name="payment_method" class="form-control select2" required>
                    <option value=""><?php echo get_phrase('payment_method');?></option>
                    <option value="1">Card</option>
                    <option value="2">Cash</option>
                    <option value="3">Cheque</option>
                   </select>

                </div>
            </div>


            <div class="form-group">
                 	<label class="col-md-12" for="example-text"><?php echo get_phrase('Description');?></label>
                <div class="col-sm-12">
                    <textarea class="form-control" name="description"></textarea>
                </div>
            </div>
            
            <div class="form-group">
                    <button type="submit" class="btn btn-info btn-block btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;<?php echo get_phrase('create');?></button>
			</div>
							
    </form>                
		</div>
	</div>
</div>
			<!----CREATION FORM ENDS-->

<div class="col-sm-6">
	<div class="panel panel-info">
        <div class="panel-heading"> <i class="fa fa-plus"></i>&nbsp;&nbsp;<?php echo get_phrase('Create Mass Invoice');?></div>
            <div class="panel-body table-responsive">
				
        <?php echo form_open(base_url() . 'admin/student_payment/mass_invoice' , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
        <div class="form-group">
                 	<label class="col-md-12" for="example-text"><?php echo get_phrase('Invoice Number');?></label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" name="invoice_number" value="<?php echo rand(10000, 1000000). 'INV'. date('Y');?>" / required>
                </div>
            </div>
            
            <div class="form-group">
                 	<label class="col-md-12" for="example-text"><?php echo get_phrase('Receipt Number');?></label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" name="receipt_number" value="<?php echo 'RCPT'. rand(1000, 9999) . date('Ymd');?>" / required>
                </div>
            </div>


            <div class="form-group">
                 	<label class="col-md-12" for="example-text"><?php echo get_phrase('Payment Title');?></label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" name="title" / required>
                </div>
            </div>



            <div class="form-group">
                 	<label class="col-md-12" for="example-text"><?php echo get_phrase('class');?></label>
                <div class="col-sm-12">
                    <select name="class_id" id="class_id" class="form-control select2" onchange="return get_class_mass_student(this.value)">
                    <option value=""><?php echo get_phrase('select_class');?></option>

                    <?php $class =  $this->db->get('class')->result_array();
                    foreach($class as $key => $class):?>
                    <option value="<?php echo $class['class_id'];?>"><?php echo $class['name'];?></option>
                    <?php endforeach;?>
                   </select>

                </div>
            </div>

								
			<div class="form-group">
                    <label class="col-md-12" for="example-text"><?php echo get_phrase('Student');?></label>
                <div class="col-sm-12">
                   <div id="mass_student_selector_holder"></div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-12" for="example-text"><?php echo get_phrase('Fee Items');?></label>
                <div class="col-sm-12">
                    <div id="mass_fee_items_container">
                        <!-- Fee items will be added here dynamically -->
                        <div class="fee-item-row">
                            <div class="row">
                                <div class="col-md-5">
                                    <select name="fee_items[0][fee_type]" class="form-control select2" required>
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
                                    <input type="number" name="fee_items[0][amount]" class="form-control mass-fee-amount" placeholder="Amount" required onchange="calculateMassTotalAmount()">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger btn-circle btn-xs" onclick="removeMassFeeItem(this)" style="display:none;"><i class="fa fa-times"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-info btn-sm btn-rounded" onclick="addMassFeeItem()">
                        <i class="fa fa-plus"></i> <?php echo get_phrase('Add Fee Item');?>
                    </button>
                </div>
            </div>

			<div class="form-group">
                <label class="col-md-12" for="example-text"><?php echo get_phrase('select_date');?></label>
                <div class="col-sm-12">
                 	<input type="date" name="creation_timestamp" value="<?php echo date('Y-m-d');?>" class="form-control datepicker" id="example-date-input" required>
                </div>
            </div>

            <div class="form-group">
                 	<label class="col-md-12" for="discount_type_text_mass"><?php echo get_phrase('Payment Discount Type');?></label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" name="discount_type" id="discount_type_text_mass" list="discount_suggestions_mass" placeholder="<?php echo get_phrase('enter_or_select_discount_type');?>" oninput="calculateMassTotalAmount()">
                    <datalist id="discount_suggestions_mass">
                        <option value="<?php echo get_phrase('no_discount');?>">
                        <option value="<?php echo get_phrase('sibling_discount');?>">
                        <option value="<?php echo get_phrase('parent_is_campus_employee_discount');?>">
                    </datalist>
                </div>
            </div>
            
            <div class="form-group">
                 	<label class="col-md-12" for="example-text"><?php echo get_phrase('Payment Discount');?> %</label>
                <div class="col-sm-12">
                    <input type="number" class="form-control" name="discount" value="0" onchange="calculateMassTotalAmount()" onkeyup="calculateMassTotalAmount()">
                </div>
            </div>
            <div class="form-group">
                 	<label class="col-md-12" for="example-text"><?php echo get_phrase('Total Amount');?></label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" id="mass_total_amount" name="amount" readonly>
                </div>
            </div> 

            <div class="form-group">
                 	<label class="col-md-12" for="example-text"><?php echo get_phrase('Amount Paid');?></label>
                <div class="col-sm-12">
                    <input type="number" class="form-control" name="amount_paid" value="0" oninput="calculateMassTotalAmount()">
                </div>
            </div>

            <div class="form-group">
                 	<label class="col-md-12" for="example-text"><?php echo get_phrase('Remaining Amount');?></label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" id="mass_remaining_amount" name="mass_remaining_amount" readonly>
                </div>
            </div>

								
			<div class="form-group">
                 	<label class="col-md-12" for="example-text"><?php echo get_phrase('Payment Status');?></label>
                <div class="col-sm-12">
                    <select name="status" class="form-control select2" required>
                    <option value=""><?php echo get_phrase('payment_status');?></option>
                    <option value="1">Paid</option>
                    <option value="2">Unpaid</option>
                   </select>

                </div>
            </div>

            <div class="form-group">
                 	<label class="col-md-12" for="example-text"><?php echo get_phrase('Payment Method');?></label>
                <div class="col-sm-12">
                    <select name="payment_method" class="form-control select2" required>
                    <option value=""><?php echo get_phrase('payment_method');?></option>
                    <option value="1">Card</option>
                    <option value="2">Cash</option>
                    <option value="3">Cheque</option>
                   </select>

                </div>
            </div>


            <div class="form-group">
                 	<label class="col-md-12" for="example-text"><?php echo get_phrase('Description');?></label>
                <div class="col-sm-12">
                    <textarea class="form-control" name="description"></textarea>
                </div>
            </div>
            
            <div class="form-group">
                    <button type="submit" class="btn btn-info btn-block btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;<?php echo get_phrase('create');?></button>
			</div>
							
    </form>                                  
			</div>
		</div>
	</div>
</div>
			
            <!----TABLE LISTING ENDS--->

<script type="text/javascript">
function select(){
    var chk = $('.check');
    for(i = 0; i < chk.length; i++){
        chk[i].checked = true;
    }
}

function unselect(){
    var chk = $('.check');
    for(i = 0; i < chk.length; i++){
        chk[i].checked = false;
    }
}

// Counter for fee items
var feeItemCounter = 1;
var massFeeItemCounter = 1;

function addFeeItem() {
    var container = document.getElementById('fee_items_container');
    var newRow = document.createElement('div');
    newRow.className = 'fee-item-row';
    newRow.innerHTML = `
        <div class="row" style="margin-top: 10px;">
            <div class="col-md-5">
                <select name="fee_items[${feeItemCounter}][fee_type]" class="form-control select2" required>
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
                <input type="number" name="fee_items[${feeItemCounter}][amount]" class="form-control fee-amount" placeholder="Amount" required onchange="calculateTotalAmount()">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger btn-circle btn-xs" onclick="removeFeeItem(this)"><i class="fa fa-times"></i></button>
            </div>
        </div>
    `;
    container.appendChild(newRow);
    feeItemCounter++;
    
    // Show delete buttons since we have more than one item
    var deleteButtons = document.querySelectorAll('#fee_items_container .btn-danger');
    if (deleteButtons.length > 1) {
        deleteButtons.forEach(function(button) {
            button.style.display = 'block';
        });
    }
    
    // Initialize select2 for the new row
    $(newRow).find('.select2').select2();
    $(newRow).find('.fee-amount').on('input', calculateTotalAmount);

    calculateTotalAmount();
}

function removeFeeItem(button) {
    var row = button.closest('.fee-item-row');
    row.parentNode.removeChild(row);
    calculateTotalAmount();
    
    // Hide delete buttons if only one row remains
    var deleteButtons = document.querySelectorAll('#fee_items_container .btn-danger');
    if (deleteButtons.length <= 1) {
        deleteButtons[0].style.display = 'none';
    }
}

function calculateTotalAmount() {
    var total = 0;
    var amountInputs = document.querySelectorAll('#fee_items_container .fee-amount');
    amountInputs.forEach(function(input) {
        if (input.value) {
            total += parseFloat(input.value);
        }
    });

    var discountType = document.getElementById('discount_type_text_single').value.trim().toLowerCase();
    var discountPercentage = parseFloat(document.querySelector('form[action*="single_invoice"] input[name="discount"]').value) || 0;
    var amountPaid = parseFloat(document.querySelector('form[action*="single_invoice"] input[name="amount_paid"]').value) || 0;

    // If discount type is 'no discount' (case insensitive) or empty, force 0% discount
    if (discountType === "<?php echo strtolower(get_phrase('no_discount'));?>" || discountType === '') {
        discountPercentage = 0;
    }

    var discountedTotal = total * (1 - (discountPercentage / 100));
    document.getElementById('total_amount').value = discountedTotal.toFixed(2);

    var remainingAmount = discountedTotal - amountPaid;
    document.getElementById('remaining_amount').value = remainingAmount.toFixed(2);
}

// Mass invoice fee items functions
function addMassFeeItem() {
    var container = document.getElementById('mass_fee_items_container');
    var newRow = document.createElement('div');
    newRow.className = 'fee-item-row';
    newRow.innerHTML = `
        <div class="row" style="margin-top: 10px;">
            <div class="col-md-5">
                <select name="fee_items[${massFeeItemCounter}][fee_type]" class="form-control select2" required>
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
                <input type="number" name="fee_items[${massFeeItemCounter}][amount]" class="form-control mass-fee-amount" placeholder="Amount" required onchange="calculateMassTotalAmount()">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger btn-circle btn-xs" onclick="removeMassFeeItem(this)"><i class="fa fa-times"></i></button>
            </div>
        </div>
    `;
    container.appendChild(newRow);
    massFeeItemCounter++;
    
    // Show delete buttons since we have more than one item
    var deleteButtons = document.querySelectorAll('#mass_fee_items_container .btn-danger');
    if (deleteButtons.length > 1) {
        deleteButtons.forEach(function(button) {
            button.style.display = 'block';
        });
    }
    
    // Initialize select2 for the new row
    $(newRow).find('.select2').select2();
    $(newRow).find('.mass-fee-amount').on('input', calculateMassTotalAmount);

    calculateMassTotalAmount();
}

function removeMassFeeItem(button) {
    var row = button.closest('.fee-item-row');
    row.parentNode.removeChild(row);
    calculateMassTotalAmount();
    
    // Hide delete buttons if only one row remains
    var deleteButtons = document.querySelectorAll('#mass_fee_items_container .btn-danger');
    if (deleteButtons.length <= 1) {
        deleteButtons[0].style.display = 'none';
    }
}

function calculateMassTotalAmount() {
    var total = 0;
    var amountInputs = document.querySelectorAll('#mass_fee_items_container .mass-fee-amount');
    amountInputs.forEach(function(input) {
        if (input.value) {
            total += parseFloat(input.value);
        }
    });

    var discountType = document.getElementById('discount_type_text_mass').value.trim().toLowerCase();
    var discountPercentage = parseFloat(document.querySelector('form[action*="mass_invoice"] input[name="discount"]').value) || 0;
    var amountPaid = parseFloat(document.querySelector('form[action*="mass_invoice"] input[name="amount_paid"]').value) || 0;

    if (discountType === "<?php echo strtolower(get_phrase('no_discount'));?>" || discountType === '') {
        discountPercentage = 0;
    }
    
    var discountedTotal = total * (1 - (discountPercentage / 100));
    document.getElementById('mass_total_amount').value = discountedTotal.toFixed(2);

    var remainingAmount = discountedTotal - amountPaid;
    document.getElementById('mass_remaining_amount').value = remainingAmount.toFixed(2);
}

function get_class_student(class_id){
    $.ajax({
        url:        '<?php echo base_url();?>admin/get_class_student/' + class_id,
        success:    function(response){
            jQuery('#student_selector_holder').html(response);
        } 

    });
}

function get_student_details_by_admission(admission_number){
    if(admission_number != '') {
        $.ajax({
            url: '<?php echo base_url();?>admin/get_student_by_admission/' + admission_number,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if(response.status === 'success') {
                    jQuery('#student_id').val(response.student_id);
                    jQuery('#student_name').val(response.student_name);
                    jQuery('#class_id').val(response.class_id);
                    jQuery('#class_name').val(response.class_name);
                } else {
                    alert('Student not found with this admission number.');
                    jQuery('#student_id').val('');
                    jQuery('#student_name').val('');
                    jQuery('#class_id').val('');
                    jQuery('#class_name').val('');
                }
            },
            error: function() {
                alert('Error occurred while fetching student details.');
                jQuery('#student_id').val('');
                jQuery('#student_name').val('');
                jQuery('#class_id').val('');
                jQuery('#class_name').val('');
            }
        });
    } else {
        jQuery('#student_id').val('');
        jQuery('#student_name').val('');
        jQuery('#class_id').val('');
        jQuery('#class_name').val('');
    }
}
</script>

<script type="text/javascript">
function get_class_mass_student(class_id){
    $.ajax({
        url:        '<?php echo base_url();?>admin/get_class_mass_student/' + class_id,
        success:    function(response){
            jQuery('#mass_student_selector_holder').html(response);
        } 

    });
}

// Initialize calculations
document.addEventListener('DOMContentLoaded', function() {
    calculateTotalAmount();
    calculateMassTotalAmount();

    // Event listeners for single invoice form
    const singleInvoiceForm = document.querySelector('form[action*="single_invoice"]');
    if (singleInvoiceForm) {
        // Text input for discount_type already has oninput which calls calculateTotalAmount()
        const discountInput = singleInvoiceForm.querySelector('input[name="discount"]');
        const amountPaidInput = singleInvoiceForm.querySelector('input[name="amount_paid"]');
        
        if (discountInput) {
            discountInput.addEventListener('input', calculateTotalAmount);
        }
        if (amountPaidInput) {
            amountPaidInput.addEventListener('input', calculateTotalAmount);
        }
    }

    // Event listeners for mass invoice form
    const massInvoiceForm = document.querySelector('form[action*="mass_invoice"]');
    if (massInvoiceForm) {
        // Text input for discount_type already has oninput which calls calculateMassTotalAmount()
        const massDiscountInput = massInvoiceForm.querySelector('input[name="discount"]');
        const massAmountPaidInput = massInvoiceForm.querySelector('input[name="amount_paid"]');

        if (massDiscountInput) {
            massDiscountInput.addEventListener('input', calculateMassTotalAmount);
        }
        if (massAmountPaidInput) {
            massAmountPaidInput.addEventListener('input', calculateMassTotalAmount);
        }
    }
    
    // Add event listeners to existing fee item amount fields for single invoice
    document.querySelectorAll('#fee_items_container .fee-amount').forEach(function(input) {
        input.addEventListener('input', calculateTotalAmount);
    });

    // Add event listeners to existing fee item amount fields for mass invoice
    document.querySelectorAll('#mass_fee_items_container .mass-fee-amount').forEach(function(input) {
        input.addEventListener('input', calculateMassTotalAmount);
    });
});
</script>
</script>
</script>