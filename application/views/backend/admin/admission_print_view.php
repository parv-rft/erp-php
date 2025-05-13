<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo get_phrase('Admission Form'); ?></title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/font-awesome.min.css'); ?>">
    <style>
        body {
            font-size: 12px;
            font-family: Arial, sans-serif;
            line-height: 1.3;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .school-logo {
            max-height: 80px;
            margin-bottom: 10px;
        }
        .school-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .form-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            text-align: center;
            padding: 5px;
            background-color: #f5f5f5;
            border: 1px solid #ddd;
        }
        .info-section {
            margin-bottom: 15px;
            border: 1px solid #ddd;
            padding: 10px;
        }
        .info-row {
            display: flex;
            margin-bottom: 5px;
        }
        .info-label {
            font-weight: bold;
            width: 40%;
        }
        .info-value {
            width: 60%;
            border-bottom: 1px dotted #999;
        }
        .photo-box {
            border: 1px solid #ddd;
            height: 150px;
            width: 120px;
            margin-left: auto;
            float: right;
            text-align: center;
            line-height: 150px;
        }
        .photo-box img {
            max-width: 100%;
            max-height: 100%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            font-size: 12px;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .signature-section {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            border-top: 1px dotted #999;
            width: 32%;
            text-align: center;
            padding-top: 5px;
            font-weight: bold;
        }
        .declaration {
            border: 1px solid #ddd;
            padding: 10px;
            margin: 15px 0;
            font-style: italic;
            font-size: 11px;
        }
        .office-section {
            margin-top: 20px;
            border: 1px solid #ddd;
            padding: 10px;
        }
        .office-title {
            text-align: center;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
        }
        .column-section {
            display: flex;
            justify-content: space-between;
        }
        .column {
            width: 48%;
        }
        @media print {
            .no-print {
                display: none;
            }
            @page {
                size: A4 portrait;
                margin: 1cm;
            }
            body {
                margin: 0;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fa fa-print"></i> <?php echo get_phrase('print'); ?>
        </button>
        <button onclick="window.close()" class="btn btn-default">
            <i class="fa fa-times"></i> <?php echo get_phrase('close'); ?>
        </button>
    </div>
    
    <div class="container">
        <div class="header">
            <?php 
            $system_name = $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;
            $system_address = $this->db->get_where('settings', array('type' => 'address'))->row()->description;
            ?>
            <div class="school-name"><?php echo $system_name; ?></div>
            <div class="school-address"><?php echo $system_address; ?></div>
        </div>
        
        <div class="form-title">
            ADMISSION FORM
        </div>
        
        <!-- Basic Details Section -->
        <div class="info-section">
            <div class="row">
                <div class="col-xs-9">
                    <div class="info-row">
                        <div class="info-label">Admission No.:</div>
                        <div class="info-value"><?php echo $student['admission_number']; ?></div>
                    </div>
                    
                    <div class="info-row">
                        <div class="info-label">1. Class for which admission is sought:</div>
                        <div class="info-value"><?php echo $class['name']; ?></div>
                    </div>
                    
                    <div class="info-row">
                        <div class="info-label">2. Name Of Student (In Block Letters):</div>
                        <div class="info-value"><?php echo strtoupper($student['name']); ?></div>
                    </div>
                    
                    <div class="info-row">
                        <div class="info-label">3. Date Of Birth:</div>
                        <div class="info-value"><?php echo $student['birthday']; ?></div>
                    </div>
                    
                    <div class="info-row">
                        <div class="info-label">4. Gender:</div>
                        <div class="info-value"><?php echo $student['sex']; ?></div>
                    </div>
                </div>
                <div class="col-xs-3">
                    <div class="photo-box">
                        <?php if (file_exists('uploads/student_image/'.$student['student_id'].'.jpg')): ?>
                            <img src="<?php echo base_url('uploads/student_image/'.$student['student_id'].'.jpg'); ?>">
                        <?php else: ?>
                            Photo
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="info-row">
                <div class="info-label">5. Category:</div>
                <div class="info-value"><?php echo isset($student['caste']) ? $student['caste'] : ''; ?></div>
            </div>
            
            <div class="column-section">
                <div class="column">
                    <div class="info-row">
                        <div class="info-label">Belong to BPL:</div>
                        <div class="info-value"><?php echo isset($student['bpl']) && $student['bpl'] == 'yes' ? 'Yes' : 'No'; ?></div>
                    </div>
                    
                    <div class="info-row">
                        <div class="info-label">Height:</div>
                        <div class="info-value"><?php echo isset($student['height']) ? $student['height'] : ''; ?></div>
                    </div>
                </div>
                
                <div class="column">
                    <div class="info-row">
                        <div class="info-label">Blood Group:</div>
                        <div class="info-value"><?php echo isset($student['blood_group']) ? $student['blood_group'] : ''; ?></div>
                    </div>
                    
                    <div class="info-row">
                        <div class="info-label">Weight:</div>
                        <div class="info-value"><?php echo isset($student['weight']) ? $student['weight'] : ''; ?></div>
                    </div>
                </div>
            </div>
            
            <div class="info-row">
                <div class="info-label">6. Father's Name (In Block Letters):</div>
                <div class="info-value"><?php echo isset($student['father_name']) ? strtoupper($student['father_name']) : ''; ?></div>
            </div>
            
            <div class="column-section">
                <div class="column">
                    <div class="info-row">
                        <div class="info-label">Academic Qualification:</div>
                        <div class="info-value"><?php echo isset($student['father_qualification']) ? $student['father_qualification'] : ''; ?></div>
                    </div>
                </div>
                
                <div class="column">
                    <div class="info-row">
                        <div class="info-label">Occupation:</div>
                        <div class="info-value"><?php echo isset($student['father_occupation']) ? $student['father_occupation'] : ''; ?></div>
                    </div>
                </div>
            </div>
            
            <div class="column-section">
                <div class="column">
                    <div class="info-row">
                        <div class="info-label">Designation:</div>
                        <div class="info-value"><?php echo isset($student['father_designation']) ? $student['father_designation'] : ''; ?></div>
                    </div>
                </div>
                
                <div class="column">
                    <div class="info-row">
                        <div class="info-label">Mobile No.:</div>
                        <div class="info-value"><?php echo isset($student['father_phone']) ? $student['father_phone'] : ''; ?></div>
                    </div>
                </div>
            </div>
            
            <div class="info-row">
                <div class="info-label">7. Mother's Name (In Block Letters):</div>
                <div class="info-value"><?php echo isset($student['mother_name']) ? strtoupper($student['mother_name']) : ''; ?></div>
            </div>
            
            <div class="column-section">
                <div class="column">
                    <div class="info-row">
                        <div class="info-label">Academic Qualification:</div>
                        <div class="info-value"><?php echo isset($student['mother_qualification']) ? $student['mother_qualification'] : ''; ?></div>
                    </div>
                </div>
                
                <div class="column">
                    <div class="info-row">
                        <div class="info-label">Occupation:</div>
                        <div class="info-value"><?php echo isset($student['mother_occupation']) ? $student['mother_occupation'] : ''; ?></div>
                    </div>
                </div>
            </div>
            
            <div class="column-section">
                <div class="column">
                    <div class="info-row">
                        <div class="info-label">Designation:</div>
                        <div class="info-value"><?php echo isset($student['mother_designation']) ? $student['mother_designation'] : ''; ?></div>
                    </div>
                </div>
                
                <div class="column">
                    <div class="info-row">
                        <div class="info-label">Mobile No.:</div>
                        <div class="info-value"><?php echo isset($student['mother_phone']) ? $student['mother_phone'] : ''; ?></div>
                    </div>
                </div>
            </div>
            
            <div class="info-row">
                <div class="info-label">8. Guardian's Name:</div>
                <div class="info-value"><?php echo isset($student['guardian_name']) ? $student['guardian_name'] : ''; ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Mobile No.:</div>
                <div class="info-value"><?php echo isset($student['guardian_phone']) ? $student['guardian_phone'] : ''; ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">9. Present Address:</div>
                <div class="info-value"><?php echo isset($student['current_address']) ? $student['current_address'] : ''; ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">10. Permanent Address:</div>
                <div class="info-value"><?php echo isset($student['permanent_address']) ? $student['permanent_address'] : ''; ?></div>
            </div>
            
            <div class="column-section">
                <div class="column">
                    <div class="info-row">
                        <div class="info-label">11. Student Aadhaar No.:</div>
                        <div class="info-value"><?php echo isset($student['student_aadhaar']) ? $student['student_aadhaar'] : ''; ?></div>
                    </div>
                </div>
                
                <div class="column">
                    <div class="info-row">
                        <div class="info-label">Father:</div>
                        <div class="info-value"><?php echo isset($student['father_aadhaar']) ? $student['father_aadhaar'] : ''; ?></div>
                    </div>
                </div>
                
                <div class="column">
                    <div class="info-row">
                        <div class="info-label">Mother:</div>
                        <div class="info-value"><?php echo isset($student['mother_aadhaar']) ? $student['mother_aadhaar'] : ''; ?></div>
                    </div>
                </div>
            </div>
            
            <div class="info-row">
                <div class="info-label">12. Type of Disability:</div>
                <div class="info-value"><?php echo isset($student['disability']) ? $student['disability'] : ''; ?></div>
            </div>
        </div>
        
        <!-- Previous School Details -->
        <div class="info-section">
            <div style="font-weight: bold; margin-bottom: 10px;">Previous School Details:</div>
            
            <div class="info-row">
                <div class="info-label">Name:</div>
                <div class="info-value"><?php echo isset($student['ps_attended']) ? $student['ps_attended'] : ''; ?></div>
            </div>
            
            <div class="column-section">
                <div class="column">
                    <div class="info-row">
                        <div class="info-label">Class:</div>
                        <div class="info-value"><?php echo isset($student['ps_class']) ? $student['ps_class'] : ''; ?></div>
                    </div>
                </div>
                
                <div class="column">
                    <div class="info-row">
                        <div class="info-label">TC No.:</div>
                        <div class="info-value"><?php echo isset($student['tc_no']) ? $student['tc_no'] : ''; ?></div>
                    </div>
                </div>
                
                <div class="column">
                    <div class="info-row">
                        <div class="info-label">Issue Date:</div>
                        <div class="info-value"><?php echo isset($student['tc_issue_date']) ? $student['tc_issue_date'] : ''; ?></div>
                    </div>
                </div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Subjects:</div>
                <div class="info-value"><?php echo isset($student['ps_subjects']) ? $student['ps_subjects'] : ''; ?></div>
            </div>
            
            <div class="column-section">
                <div class="column">
                    <div class="info-row">
                        <div class="info-label">Board:</div>
                        <div class="info-value"><?php echo isset($student['ps_board']) ? $student['ps_board'] : ''; ?></div>
                    </div>
                </div>
                
                <div class="column">
                    <div class="info-row">
                        <div class="info-label">Result:</div>
                        <div class="info-value"><?php echo isset($student['ps_result']) ? $student['ps_result'] : ''; ?></div>
                    </div>
                </div>
                
                <div class="column">
                    <div class="info-row">
                        <div class="info-label">Obtained Marks:</div>
                        <div class="info-value"><?php echo isset($student['ps_marks']) ? $student['ps_marks'] : ''; ?></div>
                    </div>
                </div>
                
                <div class="column">
                    <div class="info-row">
                        <div class="info-label">Max Marks:</div>
                        <div class="info-value"><?php echo isset($student['ps_max_marks']) ? $student['ps_max_marks'] : ''; ?></div>
                    </div>
                </div>
                
                <div class="column">
                    <div class="info-row">
                        <div class="info-label">CGPA/%:</div>
                        <div class="info-value"><?php echo isset($student['ps_percentage']) ? $student['ps_percentage'] : ''; ?></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Siblings Details -->
        <div class="info-section">
            <div style="font-weight: bold; margin-bottom: 10px;">Details of siblings: Full Details (Admission No / Name / Class /Age)</div>
            <div class="info-value" style="width: 100%; height: 40px; border-bottom: 1px dotted #999;">
                <?php echo isset($student['siblings_details']) ? $student['siblings_details'] : ''; ?>
            </div>
        </div>
        
        <!-- Declaration -->
        <div class="declaration">
            <strong>Declaration:</strong> I hereby declare that the above information including Name of the Candidate, Father's, Guardian's, Mother's and Date of Birth furnished by me is correct to the best of my knowledge and belief. I shall abide by the rules of the school.
        </div>
        
        <!-- Signature Section -->
        <div class="signature-section">
            <div class="signature-box">FATHER</div>
            <div class="signature-box">MOTHER</div>
            <div class="signature-box">GUARDIAN</div>
        </div>
        
        <!-- Office Use Section -->
        <div class="office-section">
            <div class="office-title">(for Office Use Only)</div>
            
            <div class="row">
                <div class="col-xs-4">
                    <div class="info-row">
                        <div class="info-label">Admission No.:</div>
                        <div class="info-value"></div>
                    </div>
                </div>
                
                <div class="col-xs-4">
                    <div class="info-row">
                        <div class="info-label">Receipt No.:</div>
                        <div class="info-value"></div>
                    </div>
                </div>
                
                <div class="col-xs-4">
                    <div class="info-row">
                        <div class="info-label">Checked By:</div>
                        <div class="info-value"></div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-xs-4">
                    <div class="info-row">
                        <div class="info-label">Admission Date:</div>
                        <div class="info-value"></div>
                    </div>
                </div>
                
                <div class="col-xs-4">
                    <div class="info-row">
                        <div class="info-label">Payment Mode:</div>
                        <div class="info-value"></div>
                    </div>
                </div>
                
                <div class="col-xs-4">
                    <div class="info-row">
                        <div class="info-label">Verified By:</div>
                        <div class="info-value"></div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-xs-4">
                    <div class="info-row">
                        <div class="info-label">Admitted Class:</div>
                        <div class="info-value"></div>
                    </div>
                </div>
                
                <div class="col-xs-4">
                    <div class="info-row">
                        <div class="info-label">Paid Amount:</div>
                        <div class="info-value"></div>
                    </div>
                </div>
                
                <div class="col-xs-4">
                    <div class="info-row">
                        <div class="info-label">Approved By:</div>
                        <div class="info-value"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="footer">
            <p><?php echo get_phrase('generated_on'); ?>: <?php echo date('d-m-Y h:i A'); ?></p>
        </div>
    </div>
    
    <script>
        // Auto print when page loads
        window.onload = function() {
            setTimeout(function() {
                // window.print(); // Uncomment to auto-print
            }, 500);
        };
    </script>
</body>
</html> 