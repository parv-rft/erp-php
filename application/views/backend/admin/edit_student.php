<?php $students = $this->db->get_where('student', array('student_id' => $student_id))->result_array();
        foreach($students as $key => $student):

// Set active tab, defaulting to 'student' if not specified
$activeTab = isset($_GET['tab']) ? $_GET['tab'] : 'student';
?>

<style>
    /* Custom Navigation Tabs Styling */
    .admission-nav {
        background: #fff;
        padding: 15px 15px 0;
        border-radius: 8px 8px 0 0;
        border: 1px solid #e0e0e0;
        border-bottom: none;
    }

    .admission-nav .nav-tabs {
        border-bottom: none;
        display: flex;
        gap: 10px;
    }

    .admission-nav .nav-tabs > li {
        margin-bottom: 0;
        flex: 1;
        text-align: center;
    }

    .admission-nav .nav-tabs > li > a {
        margin: 0;
        padding: 15px;
        border-radius: 8px 8px 0 0;
        color: #555;
        font-size: 15px;
        font-weight: 500;
        border: 2px solid transparent;
        transition: all 0.3s ease;
        background: #f8f9fa;
        position: relative;
        overflow: hidden;
    }

    .admission-nav .nav-tabs > li > a i {
        margin-right: 8px;
        font-size: 18px;
        vertical-align: middle;
    }

    .admission-nav .nav-tabs > li > a:hover {
        background: #e9ecef;
        border-color: #e9ecef;
        color: #333;
    }

    .admission-nav .nav-tabs > li.active > a,
    .admission-nav .nav-tabs > li.active > a:focus,
    .admission-nav .nav-tabs > li.active > a:hover {
        border: 2px solid #2196F3;
        border-bottom: 2px solid #2196F3;
        background: #2196F3;
        color: #fff;
    }

    .admission-nav .nav-tabs > li > a::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 0;
        height: 3px;
        background: #2196F3;
        transition: width 0.3s ease;
    }

    .admission-nav .nav-tabs > li > a:hover::after {
        width: 100%;
    }

    /* Progress indicator */
    .progress-indicator {
        display: flex;
        justify-content: space-between;
        margin: 20px 0;
        position: relative;
        padding: 0 50px;
    }

    .progress-indicator::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 2px;
        background: #e0e0e0;
        transform: translateY(-50%);
        z-index: 1;
    }

    .progress-step {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #fff;
        border: 2px solid #e0e0e0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: #666;
        position: relative;
        z-index: 2;
    }

    .progress-step.active {
        background: #2196F3;
        border-color: #2196F3;
        color: #fff;
    }

    .progress-step.completed {
        background: #4CAF50;
        border-color: #4CAF50;
        color: #fff;
    }

    /* Form Section Styling */
    .form-section-title {
        background: #f5f5f5;
        padding: 10px 15px;
        margin: 30px 0 20px;
        border-left: 4px solid #2196F3;
        font-weight: 600;
        color: #333;
        border-radius: 0 4px 4px 0;
    }

    /* Navigation Button Container */
    .nav-buttons {
        margin-top: 30px;
        display: flex;
        gap: 15px;
        justify-content: flex-end;
    }

    /* Button Styling */
    .btn-next, .btn-prev, .btn-save, .btn-print {
        padding: 12px 25px;
        font-weight: 500;
        letter-spacing: 0.5px;
        border-radius: 4px;
        transition: all 0.3s ease;
        box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
        border: none;
    }

    .btn:hover {
        box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
        transform: translateY(-2px);
    }
    
    .btn:active {
        box-shadow: 0 1px 3px rgba(0,0,0,0.12);
        transform: translateY(1px);
    }

    /* Primary Button - Next */
    .btn-next {
        background: linear-gradient(45deg, #2196F3, #1976D2);
        color: white;
    }

    .btn-next:hover {
        background: linear-gradient(45deg, #1976D2, #0D47A1);
        color: white;
    }

    /* Secondary Button - Previous */
    .btn-prev {
        background: linear-gradient(45deg, #78909C, #546E7A);
        color: white;
    }

    .btn-prev:hover {
        background: linear-gradient(45deg, #546E7A, #37474F);
        color: white;
    }

    /* Success Button - Save */
    .btn-save {
        background: linear-gradient(45deg, #4CAF50, #388E3C);
        color: white;
    }

    .btn-save:hover {
        background: linear-gradient(45deg, #388E3C, #1B5E20);
        color: white;
    }

    /* Info Button - Print */
    .btn-print {
        background: linear-gradient(45deg, #9C27B0, #7B1FA2);
        color: white;
    }

    .btn-print:hover {
        background: linear-gradient(45deg, #7B1FA2, #4A148C);
        color: white;
    }

    .btn i {
        margin-right: 8px;
    }
    
    /* Content Panel Styling */
    .panel-info {
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .panel-info > .panel-body {
        padding: 0;
    }

    .tab-content {
        background: #fff;
        padding: 30px;
        border: 1px solid #e0e0e0;
        border-top: none;
        border-radius: 0 0 8px 8px;
    }

    /* Form Styling */
    .form-group {
        margin-bottom: 25px;
    }

    .form-control {
        height: 45px;
        border-radius: 4px;
        border: 1px solid #e0e0e0;
        padding: 10px 15px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #2196F3;
        box-shadow: 0 0 0 0.2rem rgba(33, 150, 243, 0.25);
    }

    textarea.form-control {
        height: auto;
        min-height: 100px;
    }
    
    /* Print Styles */
    @media print {
        body {
            background: white;
            font-size: 12pt;
            color: #000;
        }
        
        .admission-nav, 
        .nav-buttons,
        .header, 
        .sidebar,
        .page-wrapper,
        .no-print {
            display: none !important;
        }
        
        .tab-content > .tab-pane {
            display: block !important;
            opacity: 1 !important;
            visibility: visible !important;
        }
        
        .form-section-title {
            padding: 8px;
            margin: 15px 0;
            border-left: 3px solid #000;
            background: #f5f5f5;
            page-break-after: avoid;
        }
        
        .panel, .panel-body {
            border: none !important;
            box-shadow: none !important;
        }
        
        .form-group {
            margin-bottom: 10px;
        }
        
        input.form-control {
            border: none;
            border-bottom: 1px solid #ddd;
            box-shadow: none;
            border-radius: 0;
            height: auto;
            padding: 5px 0;
        }
        
        select.form-control {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            border: none;
            border-bottom: 1px solid #ddd;
            background: transparent;
            padding: 5px 0;
        }
        
        .row {
            page-break-inside: avoid;
        }
        
        .page-header {
            text-align: center;
            margin-bottom: 20px;
        }
        
        /* Page breaks */
        #parent {
            page-break-before: always;
        }
        
        #transport {
            page-break-before: always;
        }
        
        #documents {
            page-break-before: always;
        }
    }
</style>

<div class="row">
<div class="col-sm-12">
<div class="panel panel-info">
                            <div class="panel-wrapper collapse in" aria-expanded="true">
                <div class="panel-body">
								
                                <?php if($this->session->flashdata('error_message')): ?>
                                <div class="alert alert-danger">
                            <i class="fa fa-exclamation-circle"></i>
                                    <?php echo $this->session->flashdata('error_message'); ?>
                                </div>
                                <?php endif; ?>
								
                    <?php if($this->session->flashdata('flash_message')): ?>
                        <div class="alert alert-success">
                            <i class="fa fa-check-circle"></i>
                            <?php echo $this->session->flashdata('flash_message'); ?>
                        </div>
                    <?php endif; ?>

                    <?php echo form_open(base_url() . 'admin/new_student/update/' . $student_id, array('class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data')); ?>

                    <!-- Progress Indicator -->
                    <div class="progress-indicator">
                        <div class="progress-step <?php echo $activeTab == 'student' ? 'active' : ($activeTab == 'parent' || $activeTab == 'transport' || $activeTab == 'documents' ? 'completed' : ''); ?>">1</div>
                        <div class="progress-step <?php echo $activeTab == 'parent' ? 'active' : ($activeTab == 'transport' || $activeTab == 'documents' ? 'completed' : ''); ?>">2</div>
                        <div class="progress-step <?php echo $activeTab == 'transport' ? 'active' : ($activeTab == 'documents' ? 'completed' : ''); ?>">3</div>
                        <div class="progress-step <?php echo $activeTab == 'documents' ? 'active' : ''; ?>">4</div>
                    </div>

                    <!-- Navigation Tabs -->
                    <div class="admission-nav">
                        <ul class="nav nav-tabs">
                            <li class="<?php echo $activeTab == 'student' ? 'active' : ''; ?>">
                                <a href="#student" data-toggle="tab">
                                    <i class="fa fa-user"></i> <?php echo get_phrase('Student Information'); ?>
                                </a>
                            </li>
                            <li class="<?php echo $activeTab == 'parent' ? 'active' : ''; ?>">
                                <a href="#parent" data-toggle="tab">
                                    <i class="fa fa-users"></i> <?php echo get_phrase('Parent Information'); ?>
                                </a>
                            </li>
                            <li class="<?php echo $activeTab == 'transport' ? 'active' : ''; ?>">
                                <a href="#transport" data-toggle="tab">
                                    <i class="fa fa-bus"></i> <?php echo get_phrase('Transport & Facilities'); ?>
                                </a>
                            </li>
                            <li class="<?php echo $activeTab == 'documents' ? 'active' : ''; ?>">
                                <a href="#documents" data-toggle="tab">
                                    <i class="fa fa-file-text"></i> <?php echo get_phrase('Documents'); ?>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Tab Content -->
                    <div class="tab-content">
                        <!-- Student Information Tab -->
                        <div class="tab-pane <?php echo $activeTab == 'student' ? 'active' : ''; ?>" id="student">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-info">
                                        <i class="fa fa-info-circle"></i> 
                                        <strong>Required Fields:</strong> Admission Number, Student Name, Email, and Class are required. All other fields are optional.
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Student Photo Upload -->
                            <div class="form-section-title">
                                <i class="fa fa-image"></i> <?php echo get_phrase('Student Photo'); ?>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
				<div class="form-group"> 
                                        <label class="col-md-12"><?php echo get_phrase('student_photo'); ?></label>
					 <div class="col-sm-12">
                                            <input type="file" name="userfile" onChange="readURL(this);" class="form-control dropify" 
                                                  data-allowed-file-extensions="jpg jpeg png" 
                                                  data-max-file-size="5M" 
                                                  data-show-errors="true" 
                                                  data-errors-position="outside" 
                                                  data-height="200" 
                                                  data-width="150">
                                            <img id="blah" src="<?php echo base_url();?>uploads/student_image/<?php echo $student['student_id'].'.jpg';?>" 
                                                 alt="Student Photo" height="200" width="150" 
                                                 style="border:1px dotted #ccc; object-fit:cover; margin-top:10px;">
					 <small class="text-muted"><?php echo get_phrase('Passport size photo only. Dimensions: 3.5cm x 4.5cm'); ?></small>
                                        </div>
                                    </div>
					</div>
					</div>	
					
                            <!-- Basic Information -->
                            <div class="form-section-title">
                                <i class="fa fa-info-circle"></i> <?php echo get_phrase('Basic Information'); ?>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
					<div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('student_code');?></label>
                    <div class="col-sm-12">
                                            <input type="text" class="form-control" name="student_code" value="<?php echo isset($student['student_code']) ? $student['student_code'] : substr(md5(uniqid(rand(), true)), 0, 7); ?>">
                                            <small class="text-muted"><?php echo get_phrase('Unique student code (up to 6 digits)'); ?></small>
                                        </div>
						</div>
					</div>
					
                                <div class="col-md-4">
					<div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('admission_no');?> <span class="text-danger">*</span></label>
                    <div class="col-sm-12">
                                            <input type="text" class="form-control" name="admission_no" value="<?php echo $student['admission_no'];?>" required>
                                            <small class="text-muted"><?php echo get_phrase('Enter admission number (Required)'); ?></small>
                                        </div>
						</div>
					</div>
					
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('full_name');?> <span class="text-danger">*</span></label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" name="name" value="<?php echo $student['name'];?>" required>
                                            <small class="text-muted"><?php echo get_phrase('(Required) Enter student\'s full name'); ?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
						<div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('email');?> <span class="text-danger">*</span></label>
                    <div class="col-sm-12">
                                            <input type="email" class="form-control" name="email" value="<?php echo $student['email'];?>" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" required>
                                            <small class="text-muted"><?php echo get_phrase('(Required) Enter student\'s email address'); ?></small>
                                        </div>
						</div>
					</div>

                                <div class="col-md-4">
					<div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('password');?></label>
                    <div class="col-sm-12">
                                            <input type="password" class="form-control" name="password" id="student_password" onkeyup="CheckPasswordStrength(this.value)">
                                            <span id="password_strength"></span>
                                            <small class="text-muted"><?php echo get_phrase('Leave blank to keep current password'); ?></small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('phone');?></label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" name="phone" value="<?php echo $student['phone'];?>" pattern="[0-9]{10}" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                            <small class="text-muted"><?php echo get_phrase('(Optional) Enter 10-digit mobile number'); ?></small>
                                        </div>
                                    </div>
						</div> 
						</div>
					
                            <!-- Academic Information -->
                            <div class="form-section-title">
                                <i class="fa fa-graduation-cap"></i> <?php echo get_phrase('Academic Information'); ?>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
					<div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('class');?> <span class="text-danger">*</span></label>
                    <div class="col-sm-12">
                                            <select name="class_id" class="form-control select2" style="width:100%" id="class_id" 
								data-message-required="<?php echo get_phrase('value_required');?>"
                                                    onchange="return get_class_sections(this.value)" required>
                              <option value=""><?php echo get_phrase('select');?></option>
                              <?php 
								$classes = $this->db->get('class')->result_array();
								foreach($classes as $key => $class):
									?>
                            		<option value="<?php echo $class['class_id'];?>"<?php if($student['class_id'] == $class['class_id']) echo 'selected';?>>
											<?php echo $class['name'];?>
                                            </option>
                                                <?php endforeach; ?>
                          </select>
		<a href="<?php echo base_url();?>admin/classes/"><button type="button" class="btn btn-info btn-circle btn-xs"><i class="fa fa-plus"></i></button></a>
                                        </div>
						</div> 
					</div>

                                <div class="col-md-4">
					<div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('section');?></label>
                    <div class="col-sm-12">
		                        <select name="section_id" class="form-control select2" style="width:100%" id="section_selector_holder">
		                            <option value=""><?php echo get_phrase('select_class_first');?></option>
			                    </select>
	                            <a href="<?php echo base_url();?>admin/section/"><button type="button" class="btn btn-info btn-circle btn-xs"><i class="fa fa-plus"></i></button></a>
                                        </div>
			                </div>
					</div>						
					
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('roll');?></label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" name="roll" value="<?php echo $student['roll'];?>">
                                            <small class="text-muted"><?php echo get_phrase('Enter roll number'); ?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
					<div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('admission_date');?></label>
                    <div class="col-sm-12">
                                            <input type="date" class="form-control datepicker" name="am_date" value="<?php echo $student['am_date'];?>">
                                        </div>
						</div> 
					</div>
					
                                <div class="col-md-4">
					<div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('birthday');?></label>
                    <div class="col-sm-12">
                                            <input type="text" class="form-control" name="birthday" value="<?php echo $student['birthday'];?>" id="birthday">
                                        </div>
						</div> 
					</div>
					
                                <div class="col-md-4">
					<div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('age');?></label>
                    <div class="col-sm-12">
                                            <input type="text" class="form-control calculated-field" name="age" id="age" value="<?php echo $student['age'];?>" readonly>
                                        </div>
                                    </div>
						</div> 
					</div>
					
                            <!-- Personal Information -->
                            <div class="form-section-title">
                                <i class="fa fa-user"></i> <?php echo get_phrase('Personal Information'); ?>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
					<div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('gender');?></label>
                    <div class="col-sm-12">
							<select name="sex" class="form-control select2" style="width:100%">
                              <option value=""><?php echo get_phrase('select');?></option>
                                                <option value="male" <?php if($student['sex'] == 'male') echo 'selected';?>><?php echo get_phrase('male');?></option>
                                                <option value="female" <?php if($student['sex'] == 'female') echo 'selected';?>><?php echo get_phrase('female');?></option>
                          </select>
                                        </div>
						</div> 
					</div>
					
                                <div class="col-md-4">
					<div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('place_birth');?></label>
                    <div class="col-sm-12">
                                            <input type="text" class="form-control" name="place_birth" value="<?php echo $student['place_birth'];?>">
                                        </div>
						</div> 
					</div>
					
                                <div class="col-md-4">
					<div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('blood_group');?></label>
                    <div class="col-sm-12">
                                            <select name="blood_group" class="form-control select2" style="width:100%">
                                                <option value=""><?php echo get_phrase('select');?></option>
                                                <?php
                                                $blood_groups = array('A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-');
                                                foreach($blood_groups as $blood_group):
                                                ?>
                                                <option value="<?php echo $blood_group;?>" <?php if($student['blood_group'] == $blood_group) echo 'selected';?>><?php echo $blood_group;?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
						</div> 
					</div>
					
                            <div class="row">
                                <div class="col-md-4">
					<div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('nationality');?></label>
                    <div class="col-sm-12">
                                            <input type="text" class="form-control" name="nationality" value="<?php echo $student['nationality'];?>">
                                        </div>
						</div> 
					</div>
					
                                <div class="col-md-4">
					<div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('religion');?></label>
                    <div class="col-sm-12">
                                            <input type="text" class="form-control" name="religion" value="<?php echo $student['religion'];?>">
                                        </div>
						</div> 
					</div>
					
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('mother_tongue');?></label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" name="m_tongue" value="<?php echo $student['m_tongue'];?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
					<div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('apaar_ID');?></label>
                    <div class="col-sm-12">
                                            <input type="text" class="form-control" name="apaar_id" value="<?php echo isset($student['apaar_id']) ? $student['apaar_id'] : ''; ?>" pattern="\d{0,12}" maxlength="12" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                            <small class="text-muted"><?php echo get_phrase('(Optional) Enter 12-digit Apaar ID'); ?></small>
                                        </div>
						</div> 
					</div>
					
                                <div class="col-md-6">
					<div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('aadhar_card_number');?></label>
                    	<div class="col-sm-12">
                                            <input type="text" class="form-control" name="adhar_no" value="<?php echo isset($student['adhar_no']) ? $student['adhar_no'] : ''; ?>" pattern="[0-9]{12}" maxlength="12" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                            <small class="text-muted"><?php echo get_phrase('Enter 12-digit Aadhar Card number'); ?></small>
                                        </div>
                                    </div>
						</div> 
					</div>
						
                            <!-- Address Information -->
                            <div class="form-section-title">
                                <i class="fa fa-home"></i> <?php echo get_phrase('Address Information'); ?>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
				<div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('address');?></label>
                    <div class="col-sm-12">
                                            <textarea name="address" class="form-control" rows="4"><?php echo $student['address'];?></textarea>
                                            <small class="text-muted"><?php echo get_phrase('Enter present address'); ?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('city');?></label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" name="city" value="<?php echo $student['city'];?>">
						</div> 
					</div>
			</div>
					
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('state');?></label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" name="state" value="<?php echo $student['state'];?>">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('pincode');?></label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" name="pincode" value="<?php echo isset($student['pincode']) ? $student['pincode'] : ''; ?>" pattern="[0-9]{6}" maxlength="6" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                            <small class="text-muted"><?php echo get_phrase('Enter 6-digit pincode'); ?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-section-title">
                                <i class="fa fa-home"></i> <?php echo get_phrase('Permanent Address'); ?>
                                <div class="pull-right">
                                    <label>
                                        <input type="checkbox" id="same_as_present" name="same_as_present" <?php if(isset($student['same_as_present']) && $student['same_as_present'] == 1) echo 'checked'; ?>> 
                                        <?php echo get_phrase('Same as present address'); ?>
                                    </label>
                                </div>
                            </div>
                            
                            <div id="permanent_address_fields" <?php if(isset($student['same_as_present']) && $student['same_as_present'] == 1) echo 'style="display:none;"'; ?>>
                                <div class="row">
                                    <div class="col-md-12">
				<div class="form-group">
                                            <label class="col-md-12"><?php echo get_phrase('permanent_address');?></label>
                    <div class="col-sm-12">
                                                <textarea name="permanent_address" class="form-control" rows="4"><?php echo isset($student['permanent_address']) ? $student['permanent_address'] : ''; ?></textarea>
                                                <small class="text-muted"><?php echo get_phrase('Enter permanent address'); ?></small>
                                            </div>
                                        </div>
						</div> 
					</div>
					
                                <div class="row">
                                    <div class="col-md-4">
						<div class="form-group">
                                            <label class="col-md-12"><?php echo get_phrase('permanent_city');?></label>
                    <div class="col-sm-12">
                                                <input type="text" class="form-control" name="permanent_city" value="<?php echo isset($student['permanent_city']) ? $student['permanent_city'] : ''; ?>">
                                            </div>
						</div> 
					</div>
					
                                    <div class="col-md-4">
					<div class="form-group">
                                            <label class="col-md-12"><?php echo get_phrase('permanent_state');?></label>
                    <div class="col-sm-12">
                                                <input type="text" class="form-control" name="permanent_state" value="<?php echo isset($student['permanent_state']) ? $student['permanent_state'] : ''; ?>">
                                            </div>
						</div> 
					</div>
					
                                    <div class="col-md-4">
					<div class="form-group">
                                            <label class="col-md-12"><?php echo get_phrase('permanent_pincode');?></label>
                    <div class="col-sm-12">
                                                <input type="text" class="form-control" name="permanent_pincode" value="<?php echo isset($student['permanent_pincode']) ? $student['permanent_pincode'] : ''; ?>" pattern="[0-9]{6}" maxlength="6" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                                <small class="text-muted"><?php echo get_phrase('Enter 6-digit permanent pincode'); ?></small>
                                            </div>
                                        </div>
                                    </div>
						</div>
					</div>
					
                            <!-- Navigation Buttons -->
                            <div class="nav-buttons">
                                <button type="button" class="btn btn-next next-tab" data-next="parent">
                                    <i class="fa fa-arrow-right"></i> <?php echo get_phrase('Next: Parent Information'); ?>
                                </button>
                            </div>
                        </div>

                        <!-- Parent Information Tab -->
                        <div class="tab-pane <?php echo $activeTab == 'parent' ? 'active' : ''; ?>" id="parent">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-info">
                                        <i class="fa fa-info-circle"></i> 
                                        <strong>Parent Information:</strong> Fill in details about the student's parents or guardians.
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Father's Information -->
                            <div class="form-section-title">
                                <i class="fa fa-male"></i> <?php echo get_phrase('Father\'s Information'); ?>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
						<div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('father_name');?></label>
                    <div class="col-sm-12">
                                            <input type="text" class="form-control" name="father_name" value="<?php echo $student['father_name'];?>">
                                        </div>
						</div>
					</div>
					
                                <div class="col-md-4">
					<div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('father_phone');?></label>
                    <div class="col-sm-12">
                                            <input type="text" class="form-control" name="father_phone" value="<?php echo $student['father_phone'];?>" pattern="[0-9]{10}" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                            <small class="text-muted"><?php echo get_phrase('Enter 10-digit mobile number'); ?></small>
                                        </div>
						</div>
					</div>
					
                                <div class="col-md-4">
						<div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('father_email');?></label>
                    <div class="col-sm-12">
                                            <input type="email" class="form-control" name="father_email" value="<?php echo $student['father_email'];?>">
                                        </div>
                                    </div>
						</div>
					</div>

                            <div class="row">
                                <div class="col-md-4">
						<div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('father_occupation');?></label>
                    <div class="col-sm-12">
                                            <input type="text" class="form-control" name="father_occupation" value="<?php echo $student['father_occupation'];?>">
                                        </div>
						</div>
					</div>

                                <div class="col-md-4">
					<div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('father_designation');?></label>
                    <div class="col-sm-12">
                                            <input type="text" class="form-control" name="father_designation" value="<?php echo $student['father_designation'];?>">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('father_annual_income');?></label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" name="father_annual_income" value="<?php echo $student['father_annual_income'];?>">
                                        </div>
                                    </div>
						</div> 
					</div>
					
                            <div class="row">
                                <div class="col-md-4">
						<div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('father_adhar');?></label>
                    <div class="col-sm-12">
                                            <input type="text" class="form-control" name="father_adhar" value="<?php echo $student['father_adhar'];?>" pattern="[0-9]{12}" maxlength="12" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                            <small class="text-muted"><?php echo get_phrase('Enter 12-digit Aadhar Card number'); ?></small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('father_qualification');?></label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" name="father_qualification" value="<?php echo $student['father_qualification'];?>">
                                        </div>
						</div> 
					</div>

                                <div class="col-md-4">
				<div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('father_photo');?></label>
                    <div class="col-sm-12">
                                            <input type="file" class="form-control" name="father_image">
                                            <?php if(isset($student['father_photo']) && !empty($student['father_photo'])): ?>
                                                <img src="<?php echo base_url('uploads/parent_image/' . $student['father_photo']); ?>" alt="Father's Photo" height="100" class="mt-2">
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Mother's Information -->
                            <div class="form-section-title">
                                <i class="fa fa-female"></i> <?php echo get_phrase('Mother\'s Information'); ?>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('mother_name');?></label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" name="mother_name" value="<?php echo $student['mother_name'];?>">
                                        </div>
						</div> 
					</div>
					
                                <div class="col-md-4">
				<div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('mother_phone');?></label>
                    <div class="col-sm-12">
                                            <input type="text" class="form-control" name="mother_phone" value="<?php echo $student['mother_phone'];?>" pattern="[0-9]{10}" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                            <small class="text-muted"><?php echo get_phrase('Enter 10-digit mobile number'); ?></small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('mother_email');?></label>
                                        <div class="col-sm-12">
                                            <input type="email" class="form-control" name="mother_email" value="<?php echo $student['mother_email'];?>">
                                        </div>
                                    </div>
						</div> 
					</div>

                            <div class="row">
                                <div class="col-md-4">
				<div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('mother_occupation');?></label>
                    <div class="col-sm-12">
                                            <input type="text" class="form-control" name="mother_occupation" value="<?php echo $student['mother_occupation'];?>">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('mother_designation');?></label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" name="mother_designation" value="<?php echo $student['mother_designation'];?>">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('mother_annual_income');?></label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" name="mother_annual_income" value="<?php echo $student['mother_annual_income'];?>">
                                        </div>
                                    </div>
						</div> 
					</div>

                            <div class="row">
                                <div class="col-md-4">
						<div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('mother_adhar');?></label>
                    <div class="col-sm-12">
                                            <input type="text" class="form-control" name="mother_adhar" value="<?php echo $student['mother_adhar'];?>" pattern="[0-9]{12}" maxlength="12" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                            <small class="text-muted"><?php echo get_phrase('Enter 12-digit Aadhar Card number'); ?></small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('mother_qualification');?></label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" name="mother_qualification" value="<?php echo $student['mother_qualification'];?>">
                                        </div>
						</div> 
					</div>

                                <div class="col-md-4">
						<div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('mother_photo');?></label>
                    <div class="col-sm-12">
                                            <input type="file" class="form-control" name="mother_image">
                                            <?php if(isset($student['mother_photo']) && !empty($student['mother_photo'])): ?>
                                                <img src="<?php echo base_url('uploads/parent_image/' . $student['mother_photo']); ?>" alt="Mother's Photo" height="100" class="mt-2">
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Guardian Information -->
                            <div class="form-section-title">
                                <i class="fa fa-user-plus"></i> <?php echo get_phrase('Guardian Information'); ?>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('guardian_name');?></label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" name="guardian_name" value="<?php echo isset($student['guardian_name']) ? $student['guardian_name'] : ''; ?>">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('guardian_phone');?></label>
                                        <div class="col-sm-12">
                                            <input type="tel" class="form-control" name="guardian_phone" 
                                                value="<?php echo isset($student['guardian_phone']) ? $student['guardian_phone'] : ''; ?>"
                                                pattern="[0-9]{10}" 
                                                maxlength="10"
                                                title="Guardian phone must be a 10-digit number"
                                                oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                            <small class="text-muted"><?php echo get_phrase('Enter 10-digit mobile number'); ?></small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('guardian_email');?></label>
                                        <div class="col-sm-12">
                                            <input type="email" class="form-control" name="guardian_email" 
                                                value="<?php echo isset($student['guardian_email']) ? $student['guardian_email'] : ''; ?>"
                                                pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                                                title="Please enter a valid email address">
                                            <small class="text-muted"><?php echo get_phrase('Enter valid email address'); ?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('guardian_address');?></label>
                                        <div class="col-sm-12">
                                            <textarea class="form-control" name="guardian_address" rows="3"><?php echo isset($student['guardian_address']) ? $student['guardian_address'] : ''; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Navigation Buttons -->
                            <div class="nav-buttons">
                                <button type="button" class="btn btn-prev prev-tab" data-prev="student">
                                    <i class="fa fa-arrow-left"></i> <?php echo get_phrase('Previous: Student Information'); ?>
                                </button>
                                <button type="button" class="btn btn-next next-tab" data-next="transport">
                                    <i class="fa fa-arrow-right"></i> <?php echo get_phrase('Next: Transport & Facilities'); ?>
                                </button>
                            </div>
                        </div>

                        <!-- Transport & Facilities Tab -->
                        <div class="tab-pane <?php echo $activeTab == 'transport' ? 'active' : ''; ?>" id="transport">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-info">
                                        <i class="fa fa-info-circle"></i> 
                                        <strong>Transport & Facilities:</strong> Assign transport route, dormitory, club, and other facilities to the student.
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Transport Information -->
                            <div class="form-section-title">
                                <i class="fa fa-bus"></i> <?php echo get_phrase('Transport Information'); ?>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
	<div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('transport_route');?></label>
                    <div class="col-sm-12">
							<select name="transport_id" class="form-control select2" style="width:100%">
                              <option value=""><?php echo get_phrase('select');?></option>
	                              <?php 
	                              	$transports = $this->db->get('transport')->result_array();
	                              	foreach($transports as $transport):
	                              ?>
                                                <option value="<?php echo $transport['transport_id'];?>" <?php if($student['transport_id'] == $transport['transport_id']) echo 'selected';?>>
                                                    <?php echo $transport['name'];?>
                                                </option>
                                                <?php endforeach; ?>
                          </select>
	<a href="<?php echo base_url();?>admin/transport/"><button type="button" class="btn btn-info btn-circle btn-xs"><i class="fa fa-plus"></i></button></a>
                                            <small class="text-muted"><?php echo get_phrase('Select transport route if applicable'); ?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Dormitory Information -->
                            <div class="form-section-title">
                                <i class="fa fa-building"></i> <?php echo get_phrase('Dormitory Information'); ?>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('dormitory');?></label>
                                        <div class="col-sm-12">
                                            <select name="dormitory_id" class="form-control select2" style="width:100%">
                                                <option value=""><?php echo get_phrase('select');?></option>
                                                <?php 
                                                $dormitories = $this->db->get('dormitory')->result_array();
                                                foreach($dormitories as $dormitory):
                                                ?>
                                                <option value="<?php echo $dormitory['dormitory_id'];?>" <?php if($student['dormitory_id'] == $dormitory['dormitory_id']) echo 'selected';?>>
                                                    <?php echo $dormitory['name'];?>
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <a href="<?php echo base_url();?>admin/dormitory/"><button type="button" class="btn btn-info btn-circle btn-xs"><i class="fa fa-plus"></i></button></a>
                                            <small class="text-muted"><?php echo get_phrase('Select dormitory if applicable'); ?></small>
                                        </div>
                                    </div>
						</div> 
					</div>
					
                            <!-- Clubs and Activities -->
                            <div class="form-section-title">
                                <i class="fa fa-users"></i> <?php echo get_phrase('Clubs and Activities'); ?>
                            </div>
					
                            <div class="row">
                                <div class="col-md-6">
					<div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('Student Club');?></label>
                    <div class="col-sm-12">
                                            <select name="club_id" class="form-control select2" style="width:100%">
                              <option value=""><?php echo get_phrase('select');?></option>
                              <?php 
                                                $clubs = $this->db->get('club')->result_array();
                                                foreach($clubs as $club):
									?>
                                                <option value="<?php echo $club['club_id'];?>" <?php if($student['club_id'] == $club['club_id']) echo 'selected';?>>
                                                    <?php echo $club['club_name'];?>
                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <a href="<?php echo base_url();?>admin/club/"><button type="button" class="btn btn-info btn-circle btn-xs"><i class="fa fa-plus"></i></button></a>
                                            <small class="text-muted"><?php echo get_phrase('Select club if applicable'); ?></small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('Student House');?></label>
                                        <div class="col-sm-12">
                                            <select name="house_id" class="form-control select2" style="width:100%">
                                                <option value=""><?php echo get_phrase('select');?></option>
                                <?php
                                                $houses = $this->db->get('house')->result_array();
                                                foreach($houses as $house):
                                                ?>
                                                <option value="<?php echo $house['house_id'];?>" <?php if($student['house_id'] == $house['house_id']) echo 'selected';?>>
                                                    <?php echo $house['name'];?>
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <a href="<?php echo base_url();?>studenthouse/studentHouse/"><button type="button" class="btn btn-info btn-circle btn-xs"><i class="fa fa-plus"></i></button></a>
                                            <small class="text-muted"><?php echo get_phrase('Select house if applicable'); ?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Category Information -->
                            <div class="form-section-title">
                                <i class="fa fa-tag"></i> <?php echo get_phrase('Category Information'); ?>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('Student Category');?></label>
                                        <div class="col-sm-12">
                                            <select name="student_category_id" class="form-control select2" style="width:100%">
                                                <option value=""><?php echo get_phrase('select');?></option>
                                                <?php 
                                                $student_categories = $this->db->get('student_category')->result_array();
                                                foreach($student_categories as $category):
                                                ?>
                                                <option value="<?php echo $category['student_category_id'];?>" <?php if($student['student_category_id'] == $category['student_category_id']) echo 'selected';?>>
                                                    <?php echo $category['name'];?>
                                                </option>
                                                <?php endforeach; ?>
                          </select>
						 	<a href="<?php echo base_url();?>studentcategory/studentCategory/"><button type="button" class="btn btn-info btn-circle btn-xs"><i class="fa fa-plus"></i></button></a>
                                            <small class="text-muted"><?php echo get_phrase('Select student category'); ?></small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('Admission Category');?></label>
                                        <div class="col-sm-12">
                                            <select name="admission_category" class="form-control select2" style="width:100%">
                                                <option value=""><?php echo get_phrase('select');?></option>
                                                <option value="general" <?php if(isset($student['admission_category']) && $student['admission_category'] == 'general') echo 'selected';?>><?php echo get_phrase('general');?></option>
                                                <option value="disadvantaged" <?php if(isset($student['admission_category']) && $student['admission_category'] == 'disadvantaged') echo 'selected';?>><?php echo get_phrase('disadvantaged_group');?></option>
                                                <option value="ews" <?php if(isset($student['admission_category']) && $student['admission_category'] == 'ews') echo 'selected';?>><?php echo get_phrase('ews');?></option>
                                            </select>
                                            <small class="text-muted"><?php echo get_phrase('Select admission category'); ?></small>
                                        </div>
                                    </div>
						</div> 
						</div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('Caste');?></label>
                                        <div class="col-sm-12">
                                            <select name="caste" class="form-control select2" style="width:100%">
                                                <option value=""><?php echo get_phrase('select');?></option>
                                                <option value="general" <?php if(isset($student['caste']) && $student['caste'] == 'general') echo 'selected';?>><?php echo get_phrase('general');?></option>
                                                <option value="sc" <?php if(isset($student['caste']) && $student['caste'] == 'sc') echo 'selected';?>><?php echo get_phrase('sc');?></option>
                                                <option value="st" <?php if(isset($student['caste']) && $student['caste'] == 'st') echo 'selected';?>><?php echo get_phrase('st');?></option>
                                                <option value="obc" <?php if(isset($student['caste']) && $student['caste'] == 'obc') echo 'selected';?>><?php echo get_phrase('obc');?></option>
                                                <option value="other" <?php if(isset($student['caste']) && $student['caste'] == 'other') echo 'selected';?>><?php echo get_phrase('other');?></option>
                                            </select>
                                            <small class="text-muted"><?php echo get_phrase('Select caste'); ?></small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('physical_handicap');?></label>
                                        <div class="col-sm-12">
                                            <select name="physical_h" class="form-control select2" style="width:100%">
                                                <option value=""><?php echo get_phrase('select');?></option>
                                                <option value="Yes" <?php if($student['physical_h'] == 'Yes') echo 'selected';?>>Yes</option>
                                                <option value="No" <?php if($student['physical_h'] == 'No') echo 'selected';?>>No</option>
                                            </select>
                                        </div>
                                    </div>
					</div>
					</div>
					
                            <!-- Navigation Buttons -->
                            <div class="nav-buttons">
                                <button type="button" class="btn btn-prev prev-tab" data-prev="parent">
                                    <i class="fa fa-arrow-left"></i> <?php echo get_phrase('Previous: Parent Information'); ?>
                                </button>
                                <button type="button" class="btn btn-next next-tab" data-next="documents">
                                    <i class="fa fa-arrow-right"></i> <?php echo get_phrase('Next: Documents'); ?>
                                </button>
                            </div>
                        </div>

                        <!-- Documents Tab -->
                        <div class="tab-pane <?php echo $activeTab == 'documents' ? 'active' : ''; ?>" id="documents">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-info">
                                        <i class="fa fa-info-circle"></i> 
                                        <strong>Documents Information:</strong> Update information about student's documents and previous school details.
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Previous School Information -->
                            <div class="form-section-title">
                                <i class="fa fa-university"></i> <?php echo get_phrase('Previous School Information'); ?>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
 <div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('previous_school_name');?></label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" name="ps_attended" value="<?php echo $student['ps_attended'];?>">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('the_address');?></label>
                                        <div class="col-sm-12">
                                            <textarea name="ps_address" class="form-control" rows="4"><?php echo $student['ps_address'];?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('purpose_of_leaving');?></label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" name="ps_purpose" value="<?php echo $student['ps_purpose'];?>">
                                        </div>
                                    </div>
					</div>
					
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('class_in_which_was_studying');?></label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" name="class_study" value="<?php echo $student['class_study'];?>">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('date_of_leaving');?></label>
                                        <div class="col-sm-12">
                                            <input type="date" class="form-control datepicker" name="date_of_leaving" value="<?php echo $student['date_of_leaving'];?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Admission Information -->
                            <div class="form-section-title">
                                <i class="fa fa-calendar"></i> <?php echo get_phrase('Admission Information'); ?>
				</div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('admission_date');?></label>
                                        <div class="col-sm-12">
                                            <input type="date" class="form-control datepicker" name="admission_date" value="<?php echo $student['admission_date'];?>">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('date_of_joining');?></label>
                                        <div class="col-sm-12">
                                            <input type="date" class="form-control datepicker" name="date_of_joining" value="<?php echo isset($student['date_of_joining']) ? $student['date_of_joining'] : $student['am_date']; ?>">
			</div>		
		</div>	
    </div> 
</div>

                            <!-- Document Verification -->
                            <div class="form-section-title">
                                <i class="fa fa-file-text"></i> <?php echo get_phrase('Document Verification'); ?>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('transfer_certificate');?></label>
                                        <div class="col-sm-12">
                                            <select name="tran_cert" class="form-control select2" style="width:100%">
                                                <option value=""><?php echo get_phrase('select');?></option>
                                                <option value="Yes" <?php if($student['tran_cert'] == 'Yes') echo 'selected';?>>Yes</option>
                                                <option value="No" <?php if($student['tran_cert'] == 'No') echo 'selected';?>>No</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('birth_certificate');?></label>
                                        <div class="col-sm-12">
                                            <select name="dob_cert" class="form-control select2" style="width:100%">
                                                <option value=""><?php echo get_phrase('select');?></option>
                                                <option value="Yes" <?php if($student['dob_cert'] == 'Yes') echo 'selected';?>>Yes</option>
                                                <option value="No" <?php if($student['dob_cert'] == 'No') echo 'selected';?>>No</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('any_given_marksheet');?></label>
                                        <div class="col-sm-12">
                                            <select name="mark_join" class="form-control select2" style="width:100%">
                                                <option value=""><?php echo get_phrase('select');?></option>
                                                <option value="Yes" <?php if($student['mark_join'] == 'Yes') echo 'selected';?>>Yes</option>
                                                <option value="No" <?php if($student['mark_join'] == 'No') echo 'selected';?>>No</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="col-md-12"><?php echo get_phrase('session');?></label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" name="session" value="<?php echo $student['session'];?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Additional Documents and Files -->
                            <div class="form-section-title">
                                <i class="fa fa-paperclip"></i> <?php echo get_phrase('Additional Documents'); ?>
                                <small class="text-muted">(Upload in student details section)</small>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-warning">
                                        <i class="fa fa-info-circle"></i> 
                                        <strong>Note:</strong> Additional documents like ID cards, certificates, etc. can be uploaded in the student details section after saving this form.
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Navigation Buttons -->
                            <div class="nav-buttons">
                                <button type="button" class="btn btn-prev prev-tab" data-prev="transport">
                                    <i class="fa fa-arrow-left"></i> <?php echo get_phrase('Previous: Transport & Facilities'); ?>
                                </button>
                                <button type="submit" class="btn btn-save">
                                    <i class="fa fa-save"></i> <?php echo get_phrase('Update Student'); ?>
                                </button>
                                <button type="button" class="btn btn-print" onclick="printStudentForm()">
                                    <i class="fa fa-print"></i> <?php echo get_phrase('Print'); ?>
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
    // Tab navigation
    $(document).ready(function() {
        // Next tab button click
        $(document).on('click', '.next-tab', function() {
            var nextTab = $(this).data('next');
            $('.nav-tabs a[href="#' + nextTab + '"]').tab('show');
            
            // Update URL to maintain tab on refresh
            var url = window.location.href.split('?')[0];
            history.pushState({}, '', url + '?tab=' + nextTab);
            
            // Update progress steps
            updateProgressSteps(nextTab);
        });
        
        // Previous tab button click
        $(document).on('click', '.prev-tab', function() {
            var prevTab = $(this).data('prev');
            $('.nav-tabs a[href="#' + prevTab + '"]').tab('show');
            
            // Update URL to maintain tab on refresh
            var url = window.location.href.split('?')[0];
            history.pushState({}, '', url + '?tab=' + prevTab);
            
            // Update progress steps
            updateProgressSteps(prevTab);
        });
        
        // Tab click handler
        $('.nav-tabs a').click(function() {
            var tab = $(this).attr('href').replace('#', '');
            
            // Update URL to maintain tab on refresh
            var url = window.location.href.split('?')[0];
            history.pushState({}, '', url + '?tab=' + tab);
            
            // Update progress steps
            updateProgressSteps(tab);
        });
        
        function updateProgressSteps(activeTab) {
            // Reset all steps
            $('.progress-step').removeClass('active completed');
            
            if (activeTab == 'student') {
                $('.progress-step:eq(0)').addClass('active');
            } else if (activeTab == 'parent') {
                $('.progress-step:eq(0)').addClass('completed');
                $('.progress-step:eq(1)').addClass('active');
            } else if (activeTab == 'transport') {
                $('.progress-step:eq(0)').addClass('completed');
                $('.progress-step:eq(1)').addClass('completed');
                $('.progress-step:eq(2)').addClass('active');
            } else if (activeTab == 'documents') {
                $('.progress-step:eq(0)').addClass('completed');
                $('.progress-step:eq(1)').addClass('completed');
                $('.progress-step:eq(2)').addClass('completed');
                $('.progress-step:eq(3)').addClass('active');
            }
        }

        // Initialize sections when page loads for class_id
        var class_id = '<?php echo $student['class_id']; ?>';
        if (class_id) {
            get_class_sections(class_id);
        }
        
        // Initialize birthday datepicker with age calculation
        $('input[name="birthday"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            minYear: 1901,
            maxYear: parseInt(moment().format('YYYY'),10),
            locale: {
                format: 'YYYY-MM-DD'
            }
        }, function(start, end, label) {
            // Calculate age when date changes
            var years = moment().diff(start, 'years');
            $("#age").val(years);
        });
        
        // Trigger age calculation on page load
        if($('input[name="birthday"]').val()) {
            var birthDate = moment($('input[name="birthday"]').val());
            var years = moment().diff(birthDate, 'years');
            $("#age").val(years);
        }
        
        // Initialize other date pickers
        $('.datepicker').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            minYear: 1901,
            maxYear: parseInt(moment().format('YYYY'),10) + 1,
            locale: {
                format: 'YYYY-MM-DD'
            }
        });
    });
    
    // Print functionality
    function printStudentForm() {
        var printContents = document.querySelector('.tab-content').innerHTML;
        var originalContents = document.body.innerHTML;
        
        var printHeader = '<div class="page-header">' +
                          '<h2>Student Information: <?php echo $student["name"]; ?></h2>' +
                          '<p>Admission No: <?php echo $student["admission_no"]; ?> | Class: <?php echo $this->db->get_where("class", array("class_id" => $student["class_id"]))->row()->name; ?></p>' +
                          '</div>';
        
        document.body.innerHTML = '<div class="container">' + printHeader + printContents + '</div>';
        window.print();
        document.body.innerHTML = originalContents;
        location.reload();
    }

    // Get class sections
    function get_class_sections(class_id) {
        $.ajax({
            url: '<?php echo base_url();?>admin/get_class_section/' + class_id,
            success: function(response) {
                jQuery('#section_selector_holder').html(response);
                // Set the selected section if this is edit mode
                jQuery('#section_selector_holder').val('<?php echo $student['section_id']; ?>');
            }
        });
    }

    // Toggle same_as_present checkbox
    $(document).ready(function() {
        $('#same_as_present').change(function() {
            if(this.checked) {
                $('#permanent_address_fields').hide();
                // Copy values from present address to permanent address
                $('textarea[name="permanent_address"]').val($('textarea[name="address"]').val());
                $('input[name="permanent_city"]').val($('input[name="city"]').val());
                $('input[name="permanent_state"]').val($('input[name="state"]').val());
                $('input[name="permanent_pincode"]').val($('input[name="pincode"]').val());
            } else {
                $('#permanent_address_fields').show();
            }
        });
        
        // Trigger the change event on page load if the checkbox is checked
        if($('#same_as_present').is(':checked')) {
            $('#same_as_present').trigger('change');
        }
    });

    // Check password strength
	function CheckPasswordStrength(password) {
	var password_strength = document.getElementById("password_strength");

        //TextBox left blank.
        if (password.length == 0) {
            password_strength.innerHTML = "";
            return;
        }

        //Regular Expressions.
        var regex = new Array();
        regex.push("[A-Z]"); //Uppercase Alphabet.
        regex.push("[a-z]"); //Lowercase Alphabet.
        regex.push("[0-9]"); //Digit.
        regex.push("[$@$!%*#?&]"); //Special Character.

        var passed = 0;

        //Validate for each Regular Expression.
        for (var i = 0; i < regex.length; i++) {
            if (new RegExp(regex[i]).test(password)) {
                passed++;
            }
        }

        //Display status.
        var color = "";
        var strength = "";
        switch (passed) {
            case 0:
            case 1:
            case 2:
                strength = "Weak";
                color = "red";
                break;
            case 3:
                 strength = "Medium";
                color = "orange";
                break;
            case 4:
                 strength = "Strong";
                color = "green";
                break;
        }
        password_strength.innerHTML = strength;
        password_strength.style.color = color;
    }
    
    // Image preview for photo upload
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                $('#blah').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<?php endforeach; ?>