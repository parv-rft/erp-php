<div class="certificate-details-container">
    <div class="certificate-title" style="text-align:center; margin-bottom:20px; font-size:18px; font-weight:bold;">
        TRANSFER CERTIFICATE
    </div>
    
    <div class="certificate-number" style="text-align:right; margin-bottom:15px;">
        <strong>TC No:</strong> <?php echo $certificate['tc_no']; ?>
    </div>
    
    <table class="details" style="width:100%; margin-bottom:20px;">
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
            <td>: <?php echo !empty($certificate['to_class']) ? $certificate['to_class'] : 'N/A'; ?></td>
        </tr>
        <tr>
            <td>12. Whether qualified for promotion to higher class</td>
            <td>: <?php echo !empty($certificate['qualified']) ? $certificate['qualified'] : 'N/A'; ?></td>
        </tr>
        <tr>
            <td>13. Subjects studied</td>
            <td>: <?php echo !empty($certificate['subject']) ? $certificate['subject'] : 'N/A'; ?></td>
        </tr>
        <tr>
            <td>14. Total working days</td>
            <td>: <?php echo !empty($certificate['max_attendance']) ? $certificate['max_attendance'] : 'N/A'; ?></td>
        </tr>
        <tr>
            <td>15. Total days present</td>
            <td>: <?php echo !empty($certificate['obtained_attendance']) ? $certificate['obtained_attendance'] : 'N/A'; ?></td>
        </tr>
        <tr>
            <td>16. Fees paid up to</td>
            <td>: <?php echo !empty($certificate['fees_paid_up_to']) ? date('d-m-Y', strtotime($certificate['fees_paid_up_to'])) : 'N/A'; ?></td>
        </tr>
        <tr>
            <td>17. Fees concession</td>
            <td>: <?php echo !empty($certificate['fees_concession_availed']) ? $certificate['fees_concession_availed'] : 'No'; ?></td>
        </tr>
        <tr>
            <td>18. Games played / Activities</td>
            <td>: <?php echo !empty($certificate['games_played']) ? $certificate['games_played'] : 'N/A'; ?></td>
        </tr>
        <tr>
            <td>19. General conduct</td>
            <td>: <?php echo !empty($certificate['general_conduct']) ? $certificate['general_conduct'] : 'Good'; ?></td>
        </tr>
        <tr>
            <td>20. Reason for leaving</td>
            <td>: <?php echo !empty($certificate['reason']) ? $certificate['reason'] : 'On parent\'s request'; ?></td>
        </tr>
        <tr>
            <td>21. Remarks</td>
            <td>: <?php echo !empty($certificate['behavior_remarks']) ? $certificate['behavior_remarks'] : 'N/A'; ?></td>
        </tr>
        <tr>
            <td>22. Date of Issue</td>
            <td>: <?php echo date('d-m-Y', strtotime($certificate['date_of_issue'])); ?></td>
        </tr>
    </table>
    

<style>
    .certificate-details-container {
        font-family: Arial, sans-serif;
        padding: 15px;
    }
    .details td {
        padding: 6px 0;
        vertical-align: top;
    }
    .details td:first-child {
        width: 200px;
        font-weight: bold;
    }
</style> 