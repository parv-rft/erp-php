<div class="student-details-container">
    <!-- Student Basic Info Section -->
    <div class="student-info-section">
        <div class="student-info-header">
            <i class="fa fa-user"></i> Basic Information
        </div>
        
        <div class="row">
            <div class="col-md-3">
                <div class="student-photo">
                    <img src="<?php echo $this->crud_model->get_image_url('student', $student['student_id']); ?>" class="img-responsive">
                </div>
            </div>
            <div class="col-md-9">
                <div class="info-row">
                    <div class="info-label">Admission Number</div>
                    <div class="info-value"><?php echo $student['admission_number']; ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Student Code</div>
                    <div class="info-value"><?php echo isset($student['student_code']) ? $student['student_code'] : 'N/A'; ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Name</div>
                    <div class="info-value"><?php echo $student['name']; ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Gender</div>
                    <div class="info-value"><?php echo $student['sex']; ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Date of Birth</div>
                    <div class="info-value"><?php echo $student['birthday']; ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Blood Group</div>
                    <div class="info-value"><?php echo isset($student['blood_group']) ? $student['blood_group'] : 'N/A'; ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Email</div>
                    <div class="info-value"><?php echo $student['email']; ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Phone</div>
                    <div class="info-value"><?php echo $student['phone']; ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Religion</div>
                    <div class="info-value"><?php echo isset($student['religion']) ? $student['religion'] : 'N/A'; ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Admission Category</div>
                    <div class="info-value"><?php echo isset($student['admission_category']) ? $student['admission_category'] : 'N/A'; ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Admission Date</div>
                    <div class="info-value"><?php echo isset($student['admission_date']) ? $student['admission_date'] : 'N/A'; ?></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Academic Information -->
    <div class="student-info-section">
        <div class="student-info-header">
            <i class="fa fa-graduation-cap"></i> Academic Information
        </div>
        
        <div class="info-row">
            <div class="info-label">Class</div>
            <div class="info-value"><?php echo $class['name']; ?></div>
        </div>
        <div class="info-row">
            <div class="info-label">Session</div>
            <div class="info-value"><?php echo isset($student['session']) ? $student['session'] : 'N/A'; ?></div>
        </div>
        <div class="info-row">
            <div class="info-label">Previous School</div>
            <div class="info-value"><?php echo isset($student['ps_attended']) ? $student['ps_attended'] : 'N/A'; ?></div>
        </div>
    </div>
    
    <!-- Parent Information -->
    <div class="student-info-section">
        <div class="student-info-header">
            <i class="fa fa-users"></i> Parent Information
        </div>
        
        <div class="info-row">
            <div class="info-label">Father's Name</div>
            <div class="info-value"><?php echo isset($student['father_name']) ? $student['father_name'] : 'N/A'; ?></div>
        </div>
        <div class="info-row">
            <div class="info-label">Father's Phone</div>
            <div class="info-value"><?php echo isset($student['father_phone']) ? $student['father_phone'] : 'N/A'; ?></div>
        </div>
        <div class="info-row">
            <div class="info-label">Father's Occupation</div>
            <div class="info-value"><?php echo isset($student['father_occupation']) ? $student['father_occupation'] : 'N/A'; ?></div>
        </div>
        <div class="info-row">
            <div class="info-label">Father's Designation</div>
            <div class="info-value"><?php echo isset($student['father_designation']) ? $student['father_designation'] : 'N/A'; ?></div>
        </div>
        
        <div class="info-row">
            <div class="info-label">Mother's Name</div>
            <div class="info-value"><?php echo isset($student['mother_name']) ? $student['mother_name'] : 'N/A'; ?></div>
        </div>
        <div class="info-row">
            <div class="info-label">Mother's Phone</div>
            <div class="info-value"><?php echo isset($student['mother_phone']) ? $student['mother_phone'] : 'N/A'; ?></div>
        </div>
        <div class="info-row">
            <div class="info-label">Mother's Occupation</div>
            <div class="info-value"><?php echo isset($student['mother_occupation']) ? $student['mother_occupation'] : 'N/A'; ?></div>
        </div>
        <div class="info-row">
            <div class="info-label">Mother's Designation</div>
            <div class="info-value"><?php echo isset($student['mother_designation']) ? $student['mother_designation'] : 'N/A'; ?></div>
        </div>
    </div>
    
    <!-- Address Information -->
    <div class="student-info-section">
        <div class="student-info-header">
            <i class="fa fa-map-marker"></i> Address Information
        </div>
        
        <div class="info-row">
            <div class="info-label">Present Address</div>
            <div class="info-value"><?php echo isset($student['address']) ? $student['address'] : 'N/A'; ?></div>
        </div>
        <div class="info-row">
            <div class="info-label">City</div>
            <div class="info-value"><?php echo isset($student['city']) ? $student['city'] : 'N/A'; ?></div>
        </div>
        <div class="info-row">
            <div class="info-label">State</div>
            <div class="info-value"><?php echo isset($student['state']) ? $student['state'] : 'N/A'; ?></div>
        </div>
        <div class="info-row">
            <div class="info-label">Pincode</div>
            <div class="info-value"><?php echo isset($student['pincode']) ? $student['pincode'] : 'N/A'; ?></div>
        </div>
        <div class="info-row">
            <div class="info-label">Permanent Address</div>
            <div class="info-value"><?php echo isset($student['permanent_address']) ? $student['permanent_address'] : 'N/A'; ?></div>
        </div>
        <div class="info-row">
            <div class="info-label">Permanent City</div>
            <div class="info-value"><?php echo isset($student['permanent_city']) ? $student['permanent_city'] : 'N/A'; ?></div>
        </div>
        <div class="info-row">
            <div class="info-label">Permanent State</div>
            <div class="info-value"><?php echo isset($student['permanent_state']) ? $student['permanent_state'] : 'N/A'; ?></div>
        </div>
        <div class="info-row">
            <div class="info-label">Permanent Pincode</div>
            <div class="info-value"><?php echo isset($student['permanent_pincode']) ? $student['permanent_pincode'] : 'N/A'; ?></div>
        </div>
    </div>
    
    <div class="student-info-section">
        <div class="student-info-header">
            <i class="fa fa-file-text"></i> Other Information
        </div>
        
        <div class="info-row">
            <div class="info-label">Student Aadhaar</div>
            <div class="info-value"><?php echo isset($student['student_aadhaar']) ? $student['student_aadhaar'] : 'N/A'; ?></div>
        </div>
        <div class="info-row">
            <div class="info-label">Father Aadhaar</div>
            <div class="info-value"><?php echo isset($student['father_aadhaar']) ? $student['father_aadhaar'] : 'N/A'; ?></div>
        </div>
        <div class="info-row">
            <div class="info-label">Mother Aadhaar</div>
            <div class="info-value"><?php echo isset($student['mother_aadhaar']) ? $student['mother_aadhaar'] : 'N/A'; ?></div>
        </div>
    </div>
</div>

<style>
    .student-details-container {
        font-family: Arial, sans-serif;
    }
    .student-info-section {
        margin-bottom: 20px;
    }
    .student-info-header {
        background: #f5f5f5;
        padding: 8px;
        font-weight: bold;
        border-bottom: 2px solid #ddd;
    }
    .info-row {
        display: flex;
        border-bottom: 1px solid #eee;
    }
    .info-label {
        width: 40%;
        padding: 8px;
        font-weight: bold;
    }
    .info-value {
        width: 60%;
        padding: 8px;
    }
    .student-photo {
        text-align: center;
        margin-bottom: 15px;
    }
    .student-photo img {
        max-width: 150px;
        border: 1px solid #ddd;
        padding: 5px;
    }
</style> 