<!DOCTYPE html>
<html>
<head>
    <title>Transfer Certificate</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 12pt;
            line-height: 1.4;
        }
        .container {
            width: 90%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .school-name {
            font-size: 24pt;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .school-address {
            font-size: 12pt;
            margin-bottom: 5px;
        }
        .certificate-title {
            font-size: 18pt;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
            text-decoration: underline;
        }
        .certificate-number {
            text-align: right;
            margin-bottom: 20px;
        }
        .details {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .details td {
            padding: 8px 0;
            vertical-align: top;
        }
        .details td:first-child {
            width: 200px;
            font-weight: bold;
        }
        .footer {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }
        .signature {
            text-align: center;
            width: 30%;
        }
        .stamp {
            text-align: center;
            width: 30%;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 50px;
            padding-top: 5px;
        }
        .print-btn {
            text-align: center;
            margin: 20px 0;
        }
        .print-btn button {
            padding: 10px 20px;
            background-color: #3498db;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        @media print {
            .print-btn {
                display: none;
            }
            @page {
                size: A4;
                margin: 1cm;
            }
        }
    </style>
</head>
<body>
    <div class="print-btn">
        <button onclick="window.print();">Print Certificate</button>
    </div>
    
    <div class="container">
        <div class="header">
            <div class="school-name"><?php echo $this->db->get_where('settings', array('type' => 'system_name'))->row()->description; ?></div>
            <div class="school-address"><?php echo $this->db->get_where('settings', array('type' => 'address'))->row()->description; ?></div>
            <div class="school-contact">
                <?php 
                $phone = $this->db->get_where('settings', array('type' => 'phone'))->row()->description;
                $email = $this->db->get_where('settings', array('type' => 'system_email'))->row()->description;
                echo "Phone: $phone | Email: $email"; 
                ?>
            </div>
        </div>
        
        <div class="certificate-title">TRANSFER CERTIFICATE</div>
        
        <div class="certificate-number">
            <strong>TC No:</strong> <?php echo $certificate['tc_no']; ?>
        </div>
        
        <table class="details">
            <tr>
                <td>1. Student's Name</td>
                <td>: <?php echo $certificate['student_name']; ?></td>
            </tr>
            <tr>
                <td>2. Father's Name</td>
                <td>: <?php echo $certificate['father_name']; ?></td>
            </tr>
            <tr>
                <td>3. Mother's Name</td>
                <td>: <?php echo $certificate['mother_name']; ?></td>
            </tr>
            <tr>
                <td>4. Nationality</td>
                <td>: <?php echo $certificate['nationality']; ?></td>
            </tr>
            <tr>
                <td>5. Category</td>
                <td>: <?php echo $certificate['category']; ?></td>
            </tr>
            <tr>
                <td>6. Date of Birth</td>
                <td>: <?php echo date('d-m-Y', strtotime($certificate['date_of_birth'])); ?></td>
            </tr>
            <tr>
                <td>7. Admission Number</td>
                <td>: <?php echo $certificate['admission_number']; ?></td>
            </tr>
            <tr>
                <td>8. Date of Admission</td>
                <td>: <?php echo date('d-m-Y', strtotime($certificate['date_of_admission'])); ?></td>
            </tr>
            <tr>
                <td>9. Class in which studying</td>
                <td>: <?php echo $certificate['student_class']; ?></td>
            </tr>
            <tr>
                <td>10. Date of Leaving</td>
                <td>: <?php echo date('d-m-Y', strtotime($certificate['date_of_leaving'])); ?></td>
            </tr>
            <tr>
                <td>11. Class to which promoted</td>
                <td>: <?php echo $certificate['to_class'] ? $certificate['to_class'] : 'N/A'; ?></td>
            </tr>
            <tr>
                <td>12. Whether qualified for promotion to higher class</td>
                <td>: <?php echo $certificate['qualified'] ? $certificate['qualified'] : 'N/A'; ?></td>
            </tr>
            <tr>
                <td>13. Subjects studied</td>
                <td>: <?php echo $certificate['subject'] ? $certificate['subject'] : 'N/A'; ?></td>
            </tr>
            <tr>
                <td>14. Total working days</td>
                <td>: <?php echo $certificate['max_attendance'] ? $certificate['max_attendance'] : 'N/A'; ?></td>
            </tr>
            <tr>
                <td>15. Total days present</td>
                <td>: <?php echo $certificate['obtained_attendance'] ? $certificate['obtained_attendance'] : 'N/A'; ?></td>
            </tr>
            <tr>
                <td>16. Fees paid up to</td>
                <td>: <?php echo $certificate['fees_paid_up_to'] ? date('d-m-Y', strtotime($certificate['fees_paid_up_to'])) : 'N/A'; ?></td>
            </tr>
            <tr>
                <td>17. Fees concession</td>
                <td>: <?php echo $certificate['fees_concession_availed'] ? $certificate['fees_concession_availed'] : 'No'; ?></td>
            </tr>
            <tr>
                <td>18. Games played / Activities</td>
                <td>: <?php echo $certificate['games_played'] ? $certificate['games_played'] : 'N/A'; ?></td>
            </tr>
            <tr>
                <td>19. General conduct</td>
                <td>: <?php echo $certificate['general_conduct'] ? $certificate['general_conduct'] : 'Good'; ?></td>
            </tr>
            <tr>
                <td>20. Reason for leaving</td>
                <td>: <?php echo $certificate['reason'] ? $certificate['reason'] : 'On parent\'s request'; ?></td>
            </tr>
            <tr>
                <td>21. Remarks</td>
                <td>: <?php echo $certificate['remarks'] ? $certificate['remarks'] : 'N/A'; ?></td>
            </tr>
            <tr>
                <td>22. Date of Issue</td>
                <td>: <?php echo date('d-m-Y', strtotime($certificate['date_of_issue'])); ?></td>
            </tr>
        </table>
        
        <div class="footer">
            <div class="signature">
                <div class="signature-line">Class Teacher</div>
            </div>
            
            <div class="stamp">
                <div class="signature-line">School Stamp</div>
            </div>
            
            <div class="signature">
                <div class="signature-line">Principal</div>
            </div>
        </div>
    </div>
</body>
</html> 