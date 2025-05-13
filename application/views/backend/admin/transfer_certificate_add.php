<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title">
                    <i class="fa fa-certificate"></i> <?php echo isset($edit_mode) ? get_phrase('Edit Transfer Certificate') : get_phrase('Add Transfer Certificate'); ?>
                </div>
            </div>
            <div class="panel-wrapper collapse in" aria-expanded="true">
                <div class="panel-body">
                    <?php 
                    $form_action = isset($edit_mode) ? base_url() . 'admin/transfer_certificate/update/' . $certificate['tc_id'] : base_url() . 'admin/transfer_certificate/create';
                    echo form_open($form_action, array('class' => 'form-horizontal form-groups-bordered', 'id' => 'tc_form')); 
                    ?>

                    <div class="form-group">
                        <label class="col-md-3 control-label"><?php echo get_phrase('Student Admission Number'); ?></label>
                        <div class="col-md-7">
                            <input type="number" class="form-control" name="admission_number" id="admission_number" value="<?php echo isset($certificate) ? $certificate['admission_number'] : ''; ?>" <?php echo isset($edit_mode) ? 'readonly' : 'required'; ?> />
                            <?php if(!isset($edit_mode)): ?>
                            <button type="button" class="btn btn-info btn-sm" onclick="searchStudent()" style="margin-top:10px;">
                                <i class="fa fa-search"></i> <?php echo get_phrase('Search Student'); ?>
                            </button>
                            <div id="search_status" class="alert mt-2" style="display:none; margin-top:10px;"></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div id="student_details" style="<?php echo isset($edit_mode) ? 'display:block' : 'display:none'; ?>">
                        <!-- Basic Info Section -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-5 control-label"><?php echo get_phrase('TC Number'); ?></label>
                                    <div class="col-md-7">
                                        <input type="text" class="form-control" name="tc_no" value="<?php echo isset($certificate) ? $certificate['tc_no'] : $tc_no; ?>" readonly />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-5 control-label"><?php echo get_phrase('Student Name'); ?></label>
                                    <div class="col-md-7">
                                        <input type="text" class="form-control" name="student_name" id="student_name" value="<?php echo isset($certificate) ? $certificate['student_name'] : ''; ?>" required />
                                        <input type="hidden" name="student_id" id="student_id" value="<?php echo isset($certificate) ? $certificate['student_id'] : ''; ?>" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-5 control-label"><?php echo get_phrase('Father Name'); ?></label>
                                    <div class="col-md-7">
                                        <input type="text" class="form-control" name="father_name" id="father_name" value="<?php echo isset($certificate) ? $certificate['father_name'] : ''; ?>" required />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-5 control-label"><?php echo get_phrase('Date of Birth'); ?></label>
                                    <div class="col-md-7">
                                        <input type="date" class="form-control" name="date_of_birth" id="date_of_birth" value="<?php echo isset($certificate) ? $certificate['date_of_birth'] : ''; ?>" required />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-5 control-label"><?php echo get_phrase('Date of Admission'); ?></label>
                                    <div class="col-md-7">
                                        <input type="date" class="form-control" name="date_of_admission" id="date_of_admission" value="<?php echo isset($certificate) ? $certificate['date_of_admission'] : ''; ?>" required />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-5 control-label"><?php echo get_phrase('Class (In Year of Leaving)'); ?></label>
                                    <div class="col-md-7">
                                        <input type="text" class="form-control" name="student_class" id="student_class" value="<?php echo isset($certificate) ? $certificate['student_class'] : ''; ?>" required />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-5 control-label"><?php echo get_phrase('Roll Number'); ?></label>
                                    <div class="col-md-7">
                                        <input type="text" class="form-control" name="roll_no" id="roll_no" value="<?php echo isset($certificate) ? $certificate['roll_no'] : ''; ?>" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-5 control-label"><?php echo get_phrase('Working Days'); ?></label>
                                    <div class="col-md-7">
                                        <input type="text" class="form-control" name="max_attendance" value="<?php echo isset($certificate) ? $certificate['max_attendance'] : ''; ?>" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-5 control-label"><?php echo get_phrase('TC Charge Paid'); ?></label>
                                    <div class="col-md-7">
                                        <select class="form-control" name="tc_charge">
                                            <option value="Yes" <?php echo (isset($certificate) && $certificate['tc_charge'] == 'Yes') ? 'selected' : ''; ?>>Yes</option>
                                            <option value="No" <?php echo (isset($certificate) && $certificate['tc_charge'] == 'No') ? 'selected' : ''; ?>>No</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-5 control-label"><?php echo get_phrase('Subjects Studied'); ?></label>
                                    <div class="col-md-7">
                                        <textarea class="form-control" name="subject" rows="3"><?php echo isset($certificate) ? $certificate['subject'] : ''; ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-5 control-label"><?php echo get_phrase('Mother Name'); ?></label>
                                    <div class="col-md-7">
                                        <input type="text" class="form-control" name="mother_name" id="mother_name" value="<?php echo isset($certificate) ? $certificate['mother_name'] : ''; ?>" required />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-5 control-label"><?php echo get_phrase('Nationality'); ?></label>
                                    <div class="col-md-7">
                                        <input type="text" class="form-control" name="nationality" id="nationality" value="<?php echo isset($certificate) ? $certificate['nationality'] : 'Indian'; ?>" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-5 control-label"><?php echo get_phrase('Category'); ?></label>
                                    <div class="col-md-7">
                                        <select class="form-control" name="category">
                                            <option value=""><?php echo get_phrase('Select'); ?></option>
                                            <option value="General" <?php echo (isset($certificate) && $certificate['category'] == 'General') ? 'selected' : ''; ?>>General</option>
                                            <option value="OBC" <?php echo (isset($certificate) && $certificate['category'] == 'OBC') ? 'selected' : ''; ?>>OBC</option>
                                            <option value="SC" <?php echo (isset($certificate) && $certificate['category'] == 'SC') ? 'selected' : ''; ?>>SC</option>
                                            <option value="ST" <?php echo (isset($certificate) && $certificate['category'] == 'ST') ? 'selected' : ''; ?>>ST</option>
                                            <option value="Other" <?php echo (isset($certificate) && $certificate['category'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-5 control-label"><?php echo get_phrase('Date of Leaving'); ?></label>
                                    <div class="col-md-7">
                                        <input type="date" class="form-control" name="date_of_leaving" value="<?php echo isset($certificate) ? $certificate['date_of_leaving'] : date('Y-m-d'); ?>" required />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-5 control-label"><?php echo get_phrase('Qualified for Promotion to Class'); ?></label>
                                    <div class="col-md-7">
                                        <input type="text" class="form-control" name="to_class" value="<?php echo isset($certificate) ? $certificate['to_class'] : ''; ?>" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-5 control-label"><?php echo get_phrase('Days Present'); ?></label>
                                    <div class="col-md-7">
                                        <input type="text" class="form-control" name="obtained_attendance" value="<?php echo isset($certificate) ? $certificate['obtained_attendance'] : ''; ?>" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-5 control-label"><?php echo get_phrase('General Conduct'); ?></label>
                                    <div class="col-md-7">
                                        <select class="form-control" name="general_conduct">
                                            <option value="Good" <?php echo (isset($certificate) && $certificate['general_conduct'] == 'Good') ? 'selected' : ''; ?>>Good</option>
                                            <option value="Satisfactory" <?php echo (isset($certificate) && $certificate['general_conduct'] == 'Satisfactory') ? 'selected' : ''; ?>>Satisfactory</option>
                                            <option value="Excellent" <?php echo (isset($certificate) && $certificate['general_conduct'] == 'Excellent') ? 'selected' : ''; ?>>Excellent</option>
                                            <option value="Poor" <?php echo (isset($certificate) && $certificate['general_conduct'] == 'Poor') ? 'selected' : ''; ?>>Poor</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-5 control-label"><?php echo get_phrase('Games Played'); ?></label>
                                    <div class="col-md-7">
                                        <textarea class="form-control" name="games_played" rows="3"><?php echo isset($certificate) ? $certificate['games_played'] : ''; ?></textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-5 control-label"><?php echo get_phrase('Reason for Leaving'); ?></label>
                                    <div class="col-md-7">
                                        <textarea class="form-control" name="reason" rows="3"><?php echo isset($certificate) ? $certificate['reason'] : ''; ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"><?php echo get_phrase('Date of Issue'); ?></label>
                            <div class="col-md-7">
                                <input type="date" class="form-control" name="date_of_issue" value="<?php echo isset($certificate) ? $certificate['date_of_issue'] : date('Y-m-d'); ?>" required />
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-offset-3 col-md-7">
                                <button type="submit" class="btn btn-info btn-lg">
                                    <i class="fa fa-save"></i> <?php echo isset($edit_mode) ? get_phrase('Update Transfer Certificate') : get_phrase('Save Transfer Certificate'); ?>
                                </button>
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
<?php if(!isset($edit_mode)): ?>
function searchStudent() {
    var admissionNumber = $('#admission_number').val();
    
    if (!admissionNumber) {
        $('#search_status').removeClass('alert-success').addClass('alert-danger').html('Please enter admission number').show();
        return;
    }
    
    $('#search_status').html('Searching...').show();
    
    $.ajax({
        url: '<?php echo base_url();?>admin/get_student_for_certificate',
        type: 'POST',
        data: {
            admission_number: admissionNumber
        },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                $('#student_id').val(response.data.student_id);
                $('#student_name').val(response.data.student_name);
                $('#father_name').val(response.data.father_name);
                $('#mother_name').val(response.data.mother_name);
                $('#date_of_birth').val(response.data.date_of_birth);
                $('#date_of_admission').val(response.data.date_of_admission);
                $('#student_class').val(response.data.class_name);
                $('#search_status').removeClass('alert-danger').addClass('alert-success').html('Student found!').show();
                $('#student_details').slideDown();
            } else {
                $('#search_status').removeClass('alert-success').addClass('alert-danger').html(response.message).show();
            }
        },
        error: function() {
            $('#search_status').removeClass('alert-success').addClass('alert-danger').html('Error connecting to server').show();
        }
    });
}
<?php endif; ?>
</script> 