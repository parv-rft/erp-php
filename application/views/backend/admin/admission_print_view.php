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
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin: 15px 0 10px;
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
            padding: 5px;
            text-align: center;
            line-height: 140px;
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
        .label {
            font-weight: bold;
            background-color: #f9f9f9;
            width: 30%;
        }
        .signature-section {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            border: 1px solid #ddd;
            width: 30%;
            height: 80px;
            text-align: center;
            padding-top: 60px;
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
        
        <!-- BASIC INFORMATION -->
        <table>
            <tr>
                <td class="label">Admission No. :</td>
                <td><?php echo isset($student['admission_no']) ? $student['admission_no'] : ''; ?></td>
                <td class="label">Admission Date :</td>
                <td><?php echo isset($student['admission_date']) ? $student['admission_date'] : date('Y-m-d'); ?></td>
                <td rowspan="4" style="width:120px; vertical-align: top;">
                    <div class="photo-box">
                        <?php if (isset($student['student_id']) && file_exists('uploads/student_image/'.$student['student_id'].'.jpg')): ?>
                            <img src="<?php echo base_url('uploads/student_image/'.$student['student_id'].'.jpg'); ?>">
                        <?php else: ?>
                            Photo
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="label">Student Code :</td>
                <td><?php echo isset($student['student_code']) ? $student['student_code'] : ''; ?></td>
                <td class="label">Session :</td>
                <td><?php echo isset($student['session']) ? $student['session'] : ''; ?></td>
            </tr>
            <tr>
                <td class="label">Class Applied For :</td>
                <td><?php echo isset($class['name']) ? $class['name'] : ''; ?></td>
                <td class="label">Section :</td>
                <td>
                    <?php 
                    if (isset($student['section_id'])) {
                        $section = $this->db->get_where('section', array('section_id' => $student['section_id']))->row();
                        echo $section ? $section->name : '';
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td class="label">Student's Name :</td>
                <td colspan="3"><?php echo isset($student['name']) ? strtoupper($student['name']) : ''; ?></td>
            </tr>
        </table>
        
        <!-- PERSONAL INFORMATION -->
        <div class="section-title">PERSONAL INFORMATION</div>
        <table>
            <tr>
                <td class="label">Date of Birth :</td>
                <td><?php echo isset($student['birthday']) ? $student['birthday'] : ''; ?></td>
                <td class="label">Age :</td>
                <td><?php echo isset($student['age']) ? $student['age'] : ''; ?></td>
            </tr>
            <tr>
                <td class="label">Gender :</td>
                <td><?php echo isset($student['sex']) ? $student['sex'] : ''; ?></td>
                <td class="label">Blood Group :</td>
                <td><?php echo isset($student['blood_group']) ? $student['blood_group'] : ''; ?></td>
            </tr>
            <tr>
                <td class="label">Religion :</td>
                <td><?php echo isset($student['religion']) ? $student['religion'] : ''; ?></td>
                <td class="label">Caste :</td>
                <td><?php echo isset($student['caste']) ? $student['caste'] : ''; ?></td>
            </tr>
            <tr>
                <td class="label">Admission Category :</td>
                <td><?php echo isset($student['admission_category']) ? $student['admission_category'] : ''; ?></td>
                <td class="label">Phone Number :</td>
                <td><?php echo isset($student['phone']) ? $student['phone'] : ''; ?></td>
            </tr>
            <tr>
                <td class="label">Email Address :</td>
                <td colspan="3"><?php echo isset($student['student_email']) ? $student['student_email'] : ''; ?></td>
            </tr>
            <tr>
                <td class="label">APAAR ID :</td>
                <td><?php echo isset($student['apaar_id']) ? $student['apaar_id'] : ''; ?></td>
                <td class="label">Aadhaar Number :</td>
                <td><?php echo isset($student['adhar_no']) ? $student['adhar_no'] : ''; ?></td>
            </tr>
        </table>
        
        <!-- ADDRESS INFORMATION -->
        <div class="section-title">ADDRESS INFORMATION</div>
        <table>
            <tr>
                <td class="label">Present Address :</td>
                <td colspan="3"><?php echo isset($student['address']) ? $student['address'] : ''; ?></td>
            </tr>
            <tr>
                <td class="label">City :</td>
                <td><?php echo isset($student['city']) ? $student['city'] : ''; ?></td>
                <td class="label">State :</td>
                <td><?php echo isset($student['state']) ? $student['state'] : ''; ?></td>
            </tr>
            <tr>
                <td class="label">Pincode :</td>
                <td colspan="3"><?php echo isset($student['pincode']) ? $student['pincode'] : ''; ?></td>
            </tr>
            <tr>
                <td class="label">Permanent Address :</td>
                <td colspan="3"><?php echo isset($student['permanent_address']) ? $student['permanent_address'] : ''; ?></td>
            </tr>
            <tr>
                <td class="label">City :</td>
                <td><?php echo isset($student['permanent_city']) ? $student['permanent_city'] : ''; ?></td>
                <td class="label">State :</td>
                <td><?php echo isset($student['permanent_state']) ? $student['permanent_state'] : ''; ?></td>
            </tr>
            <tr>
                <td class="label">Pincode :</td>
                <td colspan="3"><?php echo isset($student['permanent_pincode']) ? $student['permanent_pincode'] : ''; ?></td>
            </tr>
        </table>
        
        <!-- PARENT INFORMATION -->
        <div class="section-title">PARENT INFORMATION</div>
        <table>
            <tr>
                <td class="label">Father's Name :</td>
                <td colspan="3"><?php echo isset($student['father_name']) ? strtoupper($student['father_name']) : ''; ?></td>
            </tr>
            <tr>
                <td class="label">Phone Number :</td>
                <td><?php echo isset($student['father_phone']) ? $student['father_phone'] : ''; ?></td>
                <td class="label">Email :</td>
                <td><?php echo isset($student['father_email']) ? $student['father_email'] : ''; ?></td>
            </tr>
            <tr>
                <td class="label">Qualification :</td>
                <td><?php echo isset($student['father_qualification']) ? $student['father_qualification'] : ''; ?></td>
                <td class="label">Occupation :</td>
                <td><?php echo isset($student['father_occupation']) ? $student['father_occupation'] : ''; ?></td>
            </tr>
            <tr>
                <td class="label">Designation :</td>
                <td><?php echo isset($student['father_designation']) ? $student['father_designation'] : ''; ?></td>
                <td class="label">Annual Income :</td>
                <td><?php echo isset($student['father_annual_income']) ? $student['father_annual_income'] : ''; ?></td>
            </tr>
            <tr>
                <td class="label">Aadhaar Number :</td>
                <td colspan="3"><?php echo isset($student['father_adhar']) ? $student['father_adhar'] : ''; ?></td>
            </tr>
            
            <tr>
                <td class="label">Mother's Name :</td>
                <td colspan="3"><?php echo isset($student['mother_name']) ? strtoupper($student['mother_name']) : ''; ?></td>
            </tr>
            <tr>
                <td class="label">Phone Number :</td>
                <td><?php echo isset($student['mother_phone']) ? $student['mother_phone'] : ''; ?></td>
                <td class="label">Email :</td>
                <td><?php echo isset($student['mother_email']) ? $student['mother_email'] : ''; ?></td>
            </tr>
            <tr>
                <td class="label">Qualification :</td>
                <td><?php echo isset($student['mother_qualification']) ? $student['mother_qualification'] : ''; ?></td>
                <td class="label">Occupation :</td>
                <td><?php echo isset($student['mother_occupation']) ? $student['mother_occupation'] : ''; ?></td>
            </tr>
            <tr>
                <td class="label">Designation :</td>
                <td><?php echo isset($student['mother_designation']) ? $student['mother_designation'] : ''; ?></td>
                <td class="label">Annual Income :</td>
                <td><?php echo isset($student['mother_annual_income']) ? $student['mother_annual_income'] : ''; ?></td>
            </tr>
            <tr>
                <td class="label">Aadhaar Number :</td>
                <td colspan="3"><?php echo isset($student['mother_adhar']) ? $student['mother_adhar'] : ''; ?></td>
            </tr>
            
            <tr>
                <td class="label">Guardian's Name :</td>
                <td><?php echo isset($student['guardian_name']) ? $student['guardian_name'] : ''; ?></td>
                <td class="label">Phone Number :</td>
                <td><?php echo isset($student['guardian_phone']) ? $student['guardian_phone'] : ''; ?></td>
            </tr>
            <tr>
                <td class="label">Email :</td>
                <td><?php echo isset($student['guardian_email']) ? $student['guardian_email'] : ''; ?></td>
                <td class="label">Relation :</td>
                <td><?php echo isset($student['guardian_relation']) ? $student['guardian_relation'] : ''; ?></td>
            </tr>
            <tr>
                <td class="label">Address :</td>
                <td colspan="3"><?php echo isset($student['guardian_address']) ? $student['guardian_address'] : ''; ?></td>
            </tr>
        </table>
        
        <!-- PREVIOUS SCHOOL DETAILS -->
        <div class="section-title">PREVIOUS SCHOOL DETAILS</div>
        <table>
            <tr>
                <td class="label">School Name :</td>
                <td colspan="3"><?php echo isset($student['ps_attended']) ? $student['ps_attended'] : ''; ?></td>
            </tr>
            <tr>
                <td class="label">Class Attended :</td>
                <td><?php echo isset($student['ps_class']) ? $student['ps_class'] : ''; ?></td>
                <td class="label">Board :</td>
                <td><?php echo isset($student['ps_board']) ? $student['ps_board'] : ''; ?></td>
            </tr>
            <tr>
                <td class="label">Result :</td>
                <td><?php echo isset($student['ps_result']) ? $student['ps_result'] : ''; ?></td>
                <td class="label">TC Number :</td>
                <td><?php echo isset($student['tc_no']) ? $student['tc_no'] : ''; ?></td>
            </tr>
            <tr>
                <td class="label">TC Issue Date :</td>
                <td><?php echo isset($student['tc_issue_date']) ? $student['tc_issue_date'] : ''; ?></td>
                <td class="label">Obtained Marks :</td>
                <td><?php echo isset($student['ps_marks']) ? $student['ps_marks'] : ''; ?></td>
            </tr>
            <tr>
                <td class="label">Max Marks :</td>
                <td><?php echo isset($student['ps_max_marks']) ? $student['ps_max_marks'] : ''; ?></td>
                <td class="label">CGPA/% :</td>
                <td><?php echo isset($student['ps_percentage']) ? $student['ps_percentage'] : ''; ?></td>
            </tr>
            <tr>
                <td class="label">Subjects :</td>
                <td colspan="3"><?php echo isset($student['ps_subjects']) ? $student['ps_subjects'] : ''; ?></td>
            </tr>
        </table>
        
        <!-- TRANSPORT DETAILS -->
        <div class="section-title">TRANSPORT DETAILS</div>
        <table>
            <tr>
                <td class="label">Transport Mode :</td>
                <td><?php echo isset($student['transport_mode']) ? $student['transport_mode'] : ''; ?></td>
                <td class="label">Transport ID :</td>
                <td><?php echo isset($student['transport_id']) ? $student['transport_id'] : ''; ?></td>
            </tr>
            <tr>
                <td class="label">Pick Area :</td>
                <td><?php echo isset($student['pick_area']) ? $student['pick_area'] : ''; ?></td>
                <td class="label">Pick Stand :</td>
                <td><?php echo isset($student['pick_stand']) ? $student['pick_stand'] : ''; ?></td>
            </tr>
            <tr>
                <td class="label">Drop Area :</td>
                <td><?php echo isset($student['drop_area']) ? $student['drop_area'] : ''; ?></td>
                <td class="label">Drop Stand :</td>
                <td><?php echo isset($student['drop_stand']) ? $student['drop_stand'] : ''; ?></td>
            </tr>
        </table>
        
        <!-- DECLARATION -->
        <div class="declaration">
            <strong>Declaration :</strong> I hereby declare that the above information including Name of the Candidate, Father's, Guardian's, Mother's and Date of Birth furnished by me is correct to the best of my knowledge and belief. I shall abide by the rules of the school.
        </div>
        
        <!-- SIGNATURE SECTION -->
        <div class="signature-section">
            <div class="signature-box">FATHER</div>
            <div class="signature-box">MOTHER</div>
            <div class="signature-box">GUARDIAN</div>
        </div>
        
        <div style="text-align:center; margin: 10px 0;">
            <strong>PARENT'S SIGNATURE</strong>
        </div>
        
        <!-- OFFICE USE SECTION -->
        <div class="office-section">
            <div class="office-title">(FOR OFFICE USE ONLY)</div>
            
            <table>
                <tr>
                    <td class="label">Admission No. :</td>
                    <td><?php echo isset($student['admin_admission_no']) ? $student['admin_admission_no'] : ''; ?></td>
                    <td class="label">Receipt No. :</td>
                    <td><?php echo isset($student['receipt_no']) ? $student['receipt_no'] : ''; ?></td>
                </tr>
                <tr>
                    <td class="label">Admission Date :</td>
                    <td><?php echo isset($student['admin_admission_date']) ? $student['admin_admission_date'] : ''; ?></td>
                    <td class="label">Payment Mode :</td>
                    <td><?php echo isset($student['payment_mode']) ? $student['payment_mode'] : ''; ?></td>
                </tr>
                <tr>
                    <td class="label">Admitted Class :</td>
                    <td><?php echo isset($student['admitted_class']) ? $student['admitted_class'] : ''; ?></td>
                    <td class="label">Paid Amount :</td>
                    <td><?php echo isset($student['paid_amount']) ? $student['paid_amount'] : ''; ?></td>
                </tr>
                <tr>
                    <td class="label">Checked By :</td>
                    <td><?php echo isset($student['checked_by']) ? $student['checked_by'] : ''; ?></td>
                    <td class="label">Verified By :</td>
                    <td><?php echo isset($student['verified_by']) ? $student['verified_by'] : ''; ?></td>
                </tr>
                <tr>
                    <td class="label">Approved By :</td>
                    <td colspan="3"><?php echo isset($student['approved_by']) ? $student['approved_by'] : ''; ?></td>
                </tr>
            </table>
        </div>
        
        <div class="footer">
            <p><?php echo get_phrase('generated_on'); ?>: <?php echo date('d-m-Y h:i A'); ?></p>
        </div>
    </div>
    
    <script>
        // Auto print when page loads
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html> 