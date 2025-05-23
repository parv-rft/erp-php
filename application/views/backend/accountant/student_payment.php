<div class="row">
    <div class="col-sm-6">
		<div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-plus"></i>&nbsp;&nbsp;<?php echo get_phrase('Create Single Invoice');?></div>
                    <div class="panel-body table-responsive">
			
    <!----CREATION FORM STARTS---->

    <?php echo form_open(base_url() . 'accountant/student_payment/single_invoice' , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
                
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
                <label class="col-md-12" for="example-text"><?php echo get_phrase('Fee Type');?></label>
                <div class="col-sm-12">
                    <select name="fee_type" class="form-control select2" required>
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
            </div>

			<div class="form-group">
                <label class="col-md-12" for="example-text"><?php echo get_phrase('select_date');?></label>
                <div class="col-sm-12">
                 	<input type="date" name="creation_timestamp" value="<?php echo date('Y-m-d');?>" class="form-control datepicker" id="example-date-input" required>
                </div>
            </div>

            <div class="form-group">
                 	<label class="col-md-12" for="example-text"><?php echo get_phrase('Payment Amount');?></label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" name="amount" / required>
                </div>
            </div>

            <div class="form-group">
                 	<label class="col-md-12" for="example-text"><?php echo get_phrase('Payment Discount Type');?></label>
                <div class="col-sm-12">
                    <select name="discount_type" class="form-control select2" onchange="calculateTotalAmount()">
                        <option value=""><?php echo get_phrase('select_discount_type');?></option>
                        <option value="no_discount"><?php echo get_phrase('no_discount');?></option>
                        <option value="sibling_discount"><?php echo get_phrase('sibling_discount');?></option>
                        <option value="parent_staff_discount"><?php echo get_phrase('parent_staff_discount');?></option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                 	<label class="col-md-12" for="example-text"><?php echo get_phrase('Payment Discount');?> %</label>
                <div class="col-sm-12">
                    <input type="number" class="form-control" name="discount" value="0" onchange="calculateTotalAmount()" onkeyup="calculateTotalAmount()">
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
				
        <?php echo form_open(base_url() . 'accountant/student_payment/mass_invoice' , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
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
                <label class="col-md-12" for="example-text"><?php echo get_phrase('Fee Type');?></label>
                <div class="col-sm-12">
                    <select name="fee_type" class="form-control select2" required>
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
            </div>

			<div class="form-group">
                <label class="col-md-12" for="example-text"><?php echo get_phrase('select_date');?></label>
                <div class="col-sm-12">
                 	<input type="date" name="creation_timestamp" value="<?php echo date('Y-m-d');?>" class="form-control datepicker" id="example-date-input" required>
                </div>
            </div>

            <div class="form-group">
                 	<label class="col-md-12" for="example-text"><?php echo get_phrase('Payment Amount');?></label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" name="amount" / required>
                </div>
            </div>

            <div class="form-group">
                 	<label class="col-md-12" for="example-text"><?php echo get_phrase('Payment Discount Type');?></label>
                <div class="col-sm-12">
                    <select name="discount_type" class="form-control select2" onchange="calculateMassTotalAmount()">
                        <option value=""><?php echo get_phrase('select_discount_type');?></option>
                        <option value="no_discount"><?php echo get_phrase('no_discount');?></option>
                        <option value="sibling_discount"><?php echo get_phrase('sibling_discount');?></option>
                        <option value="parent_staff_discount"><?php echo get_phrase('parent_staff_discount');?></option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                 	<label class="col-md-12" for="example-text"><?php echo get_phrase('Payment Discount');?> %</label>
                <div class="col-sm-12">
                    <input type="number" class="form-control" name="discount" value="0" onchange="calculateMassTotalAmount()" onkeyup="calculateMassTotalAmount()">
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
            url: '<?php echo base_url();?>accountant/get_student_by_admission/' + admission_number,
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
</script>

<script type="text/javascript">
// Function to calculate total amount for single invoice
function calculateTotalAmount() {
    var feeAmount = parseFloat(document.querySelector('form[action*="single_invoice"] input[name="amount"]').value) || 0;
    // This feeAmount is already post-discount due to previous logic.
    // For remaining amount, we just need amount_paid.
    var amountPaid = parseFloat(document.querySelector('form[action*="single_invoice"] input[name="amount_paid"]').value) || 0;
    
    // The displayed 'amount' is already post-discount
    var discountedTotal = feeAmount; 

    var remainingAmount = discountedTotal - amountPaid;
    document.querySelector('form[action*="single_invoice"] input[id="remaining_amount"]').value = remainingAmount.toFixed(2);
}

// Function to calculate total amount for mass invoice
function calculateMassTotalAmount() {
    var feeAmount = parseFloat(document.querySelector('form[action*="mass_invoice"] input[name="amount"]').value) || 0;
    // This feeAmount is already post-discount.
    var amountPaid = parseFloat(document.querySelector('form[action*="mass_invoice"] input[name="amount_paid"]').value) || 0;

    // The displayed 'amount' is already post-discount
    var discountedTotal = feeAmount;

    var remainingAmount = discountedTotal - amountPaid;
    document.querySelector('form[action*="mass_invoice"] input[id="mass_remaining_amount"]').value = remainingAmount.toFixed(2);
}

document.addEventListener('DOMContentLoaded', function() {
    // Event listeners for single invoice form
    const singleInvoiceForm = document.querySelector('form[action*="single_invoice"]');
    if (singleInvoiceForm) {
        const amountInput = singleInvoiceForm.querySelector('input[name="amount"]');
        const discountTypeSelect = singleInvoiceForm.querySelector('select[name="discount_type"]');
        const discountInput = singleInvoiceForm.querySelector('input[name="discount"]');
        const amountPaidInput = singleInvoiceForm.querySelector('input[name="amount_paid"]');

        if (amountInput) amountInput.addEventListener('input', calculateTotalAmount);
        if (discountTypeSelect) discountTypeSelect.addEventListener('change', calculateTotalAmount);
        if (discountInput) discountInput.addEventListener('input', calculateTotalAmount);
        if (amountPaidInput) amountPaidInput.addEventListener('input', calculateTotalAmount); // Add listener
        calculateTotalAmount(); // Initial calculation
    }

    // Event listeners for mass invoice form
    const massInvoiceForm = document.querySelector('form[action*="mass_invoice"]');
    if (massInvoiceForm) {
        const massAmountInput = massInvoiceForm.querySelector('input[name="amount"]');
        const massDiscountTypeSelect = massInvoiceForm.querySelector('select[name="discount_type"]');
        const massDiscountInput = massInvoiceForm.querySelector('input[name="discount"]');
        const massAmountPaidInput = massInvoiceForm.querySelector('input[name="amount_paid"]');

        if (massAmountInput) massAmountInput.addEventListener('input', calculateMassTotalAmount);
        if (massDiscountTypeSelect) massDiscountTypeSelect.addEventListener('change', calculateMassTotalAmount);
        if (massDiscountInput) massDiscountInput.addEventListener('input', calculateMassTotalAmount);
        if (massAmountPaidInput) massAmountPaidInput.addEventListener('input', calculateMassTotalAmount); // Add listener
        calculateMassTotalAmount(); // Initial calculation
    }
});
</script>
</script>
</script>