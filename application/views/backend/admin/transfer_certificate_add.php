<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title">
                    <i class="fa fa-certificate"></i> <?php echo get_phrase('Add Transfer Certificate'); ?>
                </div>
            </div>
            <div class="panel-wrapper collapse in" aria-expanded="true">
                <div class="panel-body">
                    <?php echo form_open(base_url() . 'admin/transfer_certificate/create', array('class' => 'form-horizontal form-groups-bordered', 'id' => 'tc_form')); ?>

                    <div class="form-group">
                        <label class="col-md-3 control-label"><?php echo get_phrase('Student Admission Number'); ?></label>
                        <div class="col-md-7">
                            <input type="text" class="form-control" name="admission_number" id="admission_number" required />
                            <button type="button" class="btn btn-info btn-sm" onclick="searchStudent()" style="margin-top:10px;">
                                <i class="fa fa-search"></i> <?php echo get_phrase('Search Student'); ?>
                            </button>
                        </div>
                    </div>

                    <div id="student_details" style="display:none;">
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?php echo get_phrase('TC Number'); ?></label>
                            <div class="col-md-7">
                                <input type="text" class="form-control" name="tc_no" value="<?php echo $tc_no; ?>" readonly />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"><?php echo get_phrase('Student Name'); ?></label>
                            <div class="col-md-7">
                                <input type="text" class="form-control" name="student_name" id="student_name" required />
                                <input type="hidden" name="student_id" id="student_id" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"><?php echo get_phrase('Father Name'); ?></label>
                            <div class="col-md-7">
                                <input type="text" class="form-control" name="father_name" id="father_name" required />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"><?php echo get_phrase('Mother Name'); ?></label>
                            <div class="col-md-7">
                                <input type="text" class="form-control" name="mother_name" id="mother_name" required />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"><?php echo get_phrase('Date of Birth'); ?></label>
                            <div class="col-md-7">
                                <input type="date" class="form-control" name="date_of_birth" id="date_of_birth" required />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"><?php echo get_phrase('Nationality'); ?></label>
                            <div class="col-md-7">
                                <input type="text" class="form-control" name="nationality" id="nationality" value="Indian" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"><?php echo get_phrase('Category'); ?></label>
                            <div class="col-md-7">
                                <select class="form-control" name="category">
                                    <option value=""><?php echo get_phrase('Select'); ?></option>
                                    <option value="General">General</option>
                                    <option value="OBC">OBC</option>
                                    <option value="SC">SC</option>
                                    <option value="ST">ST</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"><?php echo get_phrase('Date of Admission'); ?></label>
                            <div class="col-md-7">
                                <input type="date" class="form-control" name="date_of_admission" id="date_of_admission" required />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"><?php echo get_phrase('Date of Leaving'); ?></label>
                            <div class="col-md-7">
                                <input type="date" class="form-control" name="date_of_leaving" value="<?php echo date('Y-m-d'); ?>" required />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"><?php echo get_phrase('Class (In Year of Leaving)'); ?></label>
                            <div class="col-md-7">
                                <input type="text" class="form-control" name="student_class" id="student_class" required />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"><?php echo get_phrase('Qualified for Promotion to Class'); ?></label>
                            <div class="col-md-7">
                                <input type="text" class="form-control" name="to_class" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"><?php echo get_phrase('Class in Words'); ?></label>
                            <div class="col-md-7">
                                <input type="text" class="form-control" name="class_in_words" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"><?php echo get_phrase('Class at the Time of Admission'); ?></label>
                            <div class="col-md-7">
                                <input type="text" class="form-control" name="admit_class" id="admit_class" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"><?php echo get_phrase('Roll Number'); ?></label>
                            <div class="col-md-7">
                                <input type="text" class="form-control" name="roll_no" id="roll_no" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"><?php echo get_phrase('Fees Paid Up To'); ?></label>
                            <div class="col-md-7">
                                <input type="date" class="form-control" name="fees_paid_up_to" value="<?php echo date('Y-m-d'); ?>" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"><?php echo get_phrase('Fees Concession'); ?></label>
                            <div class="col-md-7">
                                <select class="form-control" name="fees_concession_availed">
                                    <option value="No">No</option>
                                    <option value="Yes">Yes</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"><?php echo get_phrase('Working Days'); ?></label>
                            <div class="col-md-7">
                                <input type="text" class="form-control" name="max_attendance" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"><?php echo get_phrase('Days Present'); ?></label>
                            <div class="col-md-7">
                                <input type="text" class="form-control" name="obtained_attendance" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"><?php echo get_phrase('Last School Day'); ?></label>
                            <div class="col-md-7">
                                <input type="date" class="form-control" name="last_attendance_date" value="<?php echo date('Y-m-d'); ?>" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"><?php echo get_phrase('TC Charge Paid'); ?></label>
                            <div class="col-md-7">
                                <select class="form-control" name="tc_charge">
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"><?php echo get_phrase('Qualified for Higher Exam'); ?></label>
                            <div class="col-md-7">
                                <select class="form-control" name="qualified">
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"><?php echo get_phrase('Last Exam Appeared In'); ?></label>
                            <div class="col-md-7">
                                <input type="text" class="form-control" name="exam_in" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"><?php echo get_phrase('Failed in Class'); ?></label>
                            <div class="col-md-7">
                                <select class="form-control" name="whether_failed">
                                    <option value="No">No</option>
                                    <option value="Yes">Yes</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"><?php echo get_phrase('Subjects Studied'); ?></label>
                            <div class="col-md-7">
                                <textarea class="form-control" name="subject" rows="3"></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"><?php echo get_phrase('Games Played'); ?></label>
                            <div class="col-md-7">
                                <textarea class="form-control" name="games_played" rows="3"></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"><?php echo get_phrase('Extra-Curricular Activities'); ?></label>
                            <div class="col-md-7">
                                <textarea class="form-control" name="extra_activity" rows="3"></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"><?php echo get_phrase('General Conduct'); ?></label>
                            <div class="col-md-7">
                                <select class="form-control" name="general_conduct">
                                    <option value="Good">Good</option>
                                    <option value="Satisfactory">Satisfactory</option>
                                    <option value="Excellent">Excellent</option>
                                    <option value="Poor">Poor</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"><?php echo get_phrase('Other Remarks'); ?></label>
                            <div class="col-md-7">
                                <textarea class="form-control" name="behavior_remarks" rows="3"></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"><?php echo get_phrase('Reason for Leaving'); ?></label>
                            <div class="col-md-7">
                                <textarea class="form-control" name="reason" rows="3"></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"><?php echo get_phrase('Additional Remarks'); ?></label>
                            <div class="col-md-7">
                                <textarea class="form-control" name="remarks" rows="3"></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"><?php echo get_phrase('Date of Issue'); ?></label>
                            <div class="col-md-7">
                                <input type="date" class="form-control" name="date_of_issue" value="<?php echo date('Y-m-d'); ?>" required />
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-offset-3 col-md-7">
                                <button type="submit" class="btn btn-info btn-lg"><?php echo get_phrase('Create Transfer Certificate'); ?></button>
                            </div>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function searchStudent() {
        var admission_number = $('#admission_number').val();
        if (!admission_number) {
            toastr.error('Please enter admission number');
            return;
        }
        
        $.ajax({
            url: '<?php echo base_url(); ?>admin/transfer_certificate/search',
            type: 'POST',
            data: {admission_no: admission_number},
            dataType: 'json',
            success: function(response) {
                if (response.error) {
                    toastr.error(response.error);
                    return;
                }
                
                // Fill student details
                $('#student_id').val(response.student_id);
                $('#student_name').val(response.fullName);
                $('#father_name').val(response.fatherName);
                $('#mother_name').val(response.motherName);
                $('#date_of_birth').val(response.dateOfBirth);
                $('#nationality').val(response.nationality);
                $('#date_of_admission').val(response.dateOfAdmission);
                $('#student_class').val(response.currentClass);
                $('#admit_class').val(response.admitClass);
                $('#roll_no').val(response.rollNo);
                
                // Show the form
                $('#student_details').show();
            },
            error: function(xhr) {
                toastr.error('Error occurred while searching for student');
            }
        });
    }
</script> 