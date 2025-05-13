<?php 
$invoices	=	$this->db->get_where('invoice' , array('invoice_id' => $param2) )->result_array();
foreach($invoices as $key => $row):?>

 <div class="row">
                    <div class="col-sm-12">
				  	<div class="panel panel-info">
                            <div class="panel-heading"> <i class="fa fa-list"></i>&nbsp;&nbsp;<?php echo get_phrase('edit_invoice');?></div>
                                <div class="panel-body table-responsive">
       
        <?php echo form_open(base_url() . 'accountant/student_payment/update_invoice/'. $row['invoice_id'], array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
               
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
                    <label class="col-md-12" for="example-text"><?php echo get_phrase('Fee Type');?></label>
                    <div class="col-sm-12">
                        <select name="fee_type" class="form-control select2" style="width:100%">
                            <option value="REGISTRATION FEE" <?php if($row['fee_type']== 'REGISTRATION FEE')echo 'selected';?>>REGISTRATION FEE</option>
                            <option value="MONTHLY FEE" <?php if($row['fee_type']== 'MONTHLY FEE')echo 'selected';?>>MONTHLY FEE</option>
                            <option value="ADMISSION FEE" <?php if($row['fee_type']== 'ADMISSION FEE')echo 'selected';?>>ADMISSION FEE</option>
                            <option value="EXAMINATION FEES" <?php if($row['fee_type']== 'EXAMINATION FEES')echo 'selected';?>>EXAMINATION FEES</option>
                            <option value="ANNUAL CHARGE" <?php if($row['fee_type']== 'ANNUAL CHARGE')echo 'selected';?>>ANNUAL CHARGE</option>
                            <option value="DEVLOPMENT FUND" <?php if($row['fee_type']== 'DEVLOPMENT FUND')echo 'selected';?>>DEVELOPMENT FUND</option>
                            <option value="A.C. CHARGES" <?php if($row['fee_type']== 'A.C. CHARGES')echo 'selected';?>>A.C. CHARGES</option>
                            <option value="TUITION FEE" <?php if($row['fee_type']== 'TUITION FEE')echo 'selected';?>>TUITION FEE</option>
                            <option value="COMPUTER-CUM-SMART CLASS" <?php if($row['fee_type']== 'COMPUTER-CUM-SMART CLASS')echo 'selected';?>>COMPUTER-CUM-SMART CLASS</option>
                            <option value="READMIT CHARGE" <?php if($row['fee_type']== 'READMIT CHARGE')echo 'selected';?>>READMIT CHARGE</option>
                            <option value="LATE FEE" <?php if($row['fee_type']== 'LATE FEE')echo 'selected';?>>LATE FEE</option>
                            <option value="TRANSPORT FEE" <?php if($row['fee_type']== 'TRANSPORT FEE')echo 'selected';?>>TRANSPORT FEE</option>
                            <option value="PTA" <?php if($row['fee_type']== 'PTA')echo 'selected';?>>PTA</option>
                            <option value="SMART CLASS" <?php if($row['fee_type']== 'SMART CLASS')echo 'selected';?>>SMART CLASS</option>
                            <option value="COMPUTER CLASS" <?php if($row['fee_type']== 'COMPUTER CLASS')echo 'selected';?>>COMPUTER CLASS</option>
                            <option value="CHEQUE BOUNCE CHARGES" <?php if($row['fee_type']== 'CHEQUE BOUNCE CHARGES')echo 'selected';?>>CHEQUE BOUNCE CHARGES</option>
                            <option value="SECURITY AND SAFETY" <?php if($row['fee_type']== 'SECURITY AND SAFETY')echo 'selected';?>>SECURITY AND SAFETY</option>
                            <option value="PUPILS FUND" <?php if($row['fee_type']== 'PUPILS FUND')echo 'selected';?>>PUPILS FUND</option>
                            <option value="ACTIVITIES" <?php if($row['fee_type']== 'ACTIVITIES')echo 'selected';?>>ACTIVITIES</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                 	<label class="col-md-12" for="example-text"><?php echo get_phrase('total_amount');?></label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" name="amount" value="<?php echo $row['amount'];?>"/>
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
                <?php endforeach;?>