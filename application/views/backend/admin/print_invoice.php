<?php
// Add debugging
error_log('Loading print_invoice.php with invoice_id: ' . $invoice_id);

// Get invoice data with error handling
try {
    $invoices = $this->db->get_where('invoice', array('invoice_id' => $invoice_id))->result_array();
    
    if (empty($invoices)) {
        error_log('No invoice found with ID: ' . $invoice_id);
        echo '<div class="alert alert-danger">No invoice found with ID: ' . $invoice_id . '</div>';
        return;
    }
    
    foreach ($invoices as $key => $row):
        try {
            // Get student info with error handling
            $student_info = $this->db->get_where('student', array('student_id' => $row['student_id']))->row();
            if (!$student_info) {
                error_log('Student not found for invoice: ' . $invoice_id);
                $student_info = new stdClass();
                $student_info->name = 'N/A';
                $student_info->admission_number = 'N/A';
                $student_info->phone = 'N/A';
                $student_info->father_phone = 'N/A';
                $student_info->address = 'N/A';
                $student_info->class_id = 0;
                $student_info->section_id = 0;
                $student_info->student_code = 'N/A';
                
            }
            
            // Get parent info
            $parent_info = null;
            if (isset($student_info->parent_id)) {
                $parent_info = $this->db->get_where('parent', array('parent_id' => $student_info->parent_id))->row();
            }
            
            // Get class info with error handling
            $class_info = $this->db->get_where('class', array('class_id' => $student_info->class_id))->row();
            if (!$class_info) {
                error_log('Class not found for student: ' . $student_info->class_id);
                $class_info = new stdClass();
                $class_info->name = 'N/A';
            }
            
            // Get section info with error handling
            $section_info = null;
            if ($student_info->section_id) {
                $section_info = $this->db->get_where('section', array('section_id' => $student_info->section_id))->row();
            }
            
            // Get system info with error handling
            $system_name = $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;
            $system_address = $this->db->get_where('settings', array('type' => 'address'))->row()->description;
            $system_phone = $this->db->get_where('settings', array('type' => 'phone'))->row()->description;
            
            // These might not exist, so use default values if not found
            $system_email_query = $this->db->get_where('settings', array('type' => 'system_email'));
            $system_email = $system_email_query->num_rows() > 0 ? $system_email_query->row()->description : 'N/A';
            
            $system_website_query = $this->db->get_where('settings', array('type' => 'website'));
            $system_website = $system_website_query->num_rows() > 0 ? $system_website_query->row()->description : 'N/A';
            
            $currency = $this->db->get_where('settings', array('type' => 'currency'))->row()->description;
            
            // Get payment details
            $payment_history = $this->db->get_where('payment', array('invoice_id' => $row['invoice_id']))->result_array();
            
            // Get fee items
            $fee_items = $this->db->get_where('fee_items', array('invoice_id' => $row['invoice_id']))->result_array();
            
            // Convert amount to words
            function numberToWords($number) {
                $ones = array(
                    0 => "", 1 => "One", 2 => "Two", 3 => "Three", 4 => "Four", 5 => "Five", 
                    6 => "Six", 7 => "Seven", 8 => "Eight", 9 => "Nine", 10 => "Ten", 
                    11 => "Eleven", 12 => "Twelve", 13 => "Thirteen", 14 => "Fourteen", 
                    15 => "Fifteen", 16 => "Sixteen", 17 => "Seventeen", 18 => "Eighteen", 
                    19 => "Nineteen"
                );
                $tens = array(
                    0 => "", 2 => "Twenty", 3 => "Thirty", 4 => "Forty", 5 => "Fifty", 
                    6 => "Sixty", 7 => "Seventy", 8 => "Eighty", 9 => "Ninety"
                );
                $hundreds = array(
                    "Hundred", "Thousand", "Million", "Billion", "Trillion", "Quadrillion"
                );

                if ($number == 0) {
                    return "Zero";
                }

                $number = number_format($number, 2, ".", ",");
                $number_array = explode(".", $number);
                $number = $number_array[0];
                
                $words = "";
                
                // For numbers up to 999
                if ($number < 1000) {
                    if ($number < 20) {
                        $words .= $ones[$number];
                    } elseif ($number < 100) {
                        $words .= $tens[floor($number/10)];
                        $remainder = $number % 10;
                        if ($remainder != 0) {
                            $words .= " " . $ones[$remainder];
                        }
                    } else {
                        $words .= $ones[floor($number/100)] . " " . $hundreds[0];
                        $remainder = $number % 100;
                        if ($remainder != 0) {
                            if ($remainder < 20) {
                                $words .= " " . $ones[$remainder];
                            } else {
                                $words .= " " . $tens[floor($remainder/10)];
                                $remainder = $remainder % 10;
                                if ($remainder != 0) {
                                    $words .= " " . $ones[$remainder];
                                }
                            }
                        }
                    }
                } else {
                    $words = "Exceeds handling capacity";
                }
                
                return $words;
            }
            
            // Payment method logic
            $payment_method = "Cash";
            $bank_details = "";
            $instrument_date = "";
            $concession = 0;
            $advance = 0;
            $balance = 0;
            
            if (!empty($payment_history)) {
                $latest_payment = end($payment_history);
                if ($latest_payment['method'] == 1) $payment_method = "Cash";
                else if ($latest_payment['method'] == 2) $payment_method = "Cheque";
                else if ($latest_payment['method'] == 3) $payment_method = "Card";
                else if ($latest_payment['method'] == 'paypal') $payment_method = "PayPal";
                
                // Get additional payment details if available
                if (isset($latest_payment['bank_details'])) {
                    $bank_details = $latest_payment['bank_details'];
                }
                if (isset($latest_payment['instrument_date'])) {
                    $instrument_date = $latest_payment['instrument_date'];
                }
                if (isset($latest_payment['advance'])) {
                    $advance = $latest_payment['advance'];
                }
                if (isset($latest_payment['concession'])) {
                    $concession = $latest_payment['concession'];
                }
                if (isset($latest_payment['balance'])) {
                    $balance = $latest_payment['balance'];
                }
            }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fee Receipt</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }
        @media print {
            @page {
                size: portrait;
                margin: 5mm;
            }
            .no-print {
                display: none;
            }
        }
        #invoice_print {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #000;
            padding: 10px;
        }
        .school-logo {
            max-height: 80px;
        }
        .receipt-header {
            text-align: center;
        }
        .receipt-title {
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            padding: 5px 0;
            margin: 10px 0;
            font-weight: bold;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 5px;
        }
        .fees-table th, .fees-table td {
            border: 1px solid #000;
            text-align: center;
        }
        .fees-table th {
            background-color: #f5f5f5;
        }
        .label-cell {
            font-weight: bold;
            width: 35%;
        }
        .colon-cell {
            width: 5%;
        }
        .value-cell {
            width: 60%;
        }
        .totals-row {
            font-weight: bold;
            border-top: 2px solid #000;
        }
        .footer-section {
            margin-top: 15px;
        }
        .cashier-signature {
            text-align: right;
            margin-top: 30px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center; margin: 20px;">
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fa fa-print"></i> <?php echo get_phrase('print'); ?>
        </button>
        <button onclick="window.close()" class="btn btn-default">
            <i class="fa fa-times"></i> <?php echo get_phrase('close'); ?>
        </button>
    </div>

    <div id="invoice_print">
        <!-- Header Section -->
        <table border="0" width="100%">
            <tr>
                <td width="15%" align="center">
                    <img src="<?php echo base_url('uploads/logo.png'); ?>" class="school-logo" alt="School Logo">
                </td>
                <td width="85%" align="center">
                    <h2 style="margin: 0; font-weight: bold; text-transform: uppercase;"><?php echo $system_name; ?></h2>
                    <p style="margin: 0;"><?php echo $system_address; ?></p>
                    <p style="margin: 0;">Contact Nos.: <?php echo $system_phone; ?></p>
                    <p style="margin: 0;">Email: <?php echo $system_email; ?>, Website: <?php echo $system_website; ?></p>
                </td>
            </tr>
        </table>
        
        <!-- Receipt Title -->
        <div class="receipt-title">
            <h3 style="margin: 0;">FEE RECEIPT (<?php echo date('Y').'-'.(date('Y')+1); ?>)</h3>
        </div>
        
        <!-- Receipt Details -->
        <table border="0" width="100%">
            <tr>
                <td width="50%" valign="top">
                    <table border="0" width="100%">
                        <tr>
                            <td class="label-cell">Receipt No</td>
                            <td class="colon-cell">:</td>
                            <td class="value-cell"><strong><?php echo sprintf('%03d', $row['invoice_id']); ?></strong></td>
                        </tr>
                        <tr>
                            <td class="label-cell">Invoice ID</td>
                            <td class="colon-cell">:</td>
                            <td class="value-cell"><strong><?php echo $row['invoice_id']; ?></strong></td>
                        </tr>
                        <tr>
                            <td class="label-cell">Name</td>
                            <td class="colon-cell">:</td>
                            <td class="value-cell">
                                <?php 
                                    $parent_name = '';
                                    if ($parent_info) {
                                        if (!empty($parent_info->name)) {
                                            $parent_name = $parent_info->name;
                                        } else if (!empty($parent_info->father_name)) {
                                            $parent_name = $parent_info->father_name;
                                        }
                                    }
                                    
                                    echo $student_info->name;
                                    if (!empty($parent_name)) {
                                        echo " S/D/O " . $parent_name;
                                    }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-cell">Admn No</td>
                            <td class="colon-cell">:</td>
                            <td class="value-cell"><?php echo $student_info->admission_number; ?></td>
                        </tr>
                        <tr>
                            <td class="label-cell">Contact No</td>
                            <td class="colon-cell">:</td>
                            <td class="value-cell">
                                <?php 
                                    $contact_numbers = array();
                                    if (!empty($student_info->phone)) $contact_numbers[] = $student_info->phone;
                                    if (!empty($student_info->father_phone)) $contact_numbers[] = $student_info->father_phone;
                                    echo implode(', ', $contact_numbers);
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-cell">Address</td>
                            <td class="colon-cell">:</td>
                            <td class="value-cell"><?php echo $student_info->address; ?></td>
                        </tr>
                    </table>
                </td>
                <td width="50%" valign="top">
                    <table border="0" width="100%">
                        <tr>
                            <td class="label-cell">Date</td>
                            <td class="colon-cell">:</td>
                            <td class="value-cell"><?php echo date('d-M-Y', $row['creation_timestamp']); ?></td>
                        </tr>
                        <tr>
                            <td class="label-cell">Class</td>
                            <td class="colon-cell">:</td>
                            <td class="value-cell"><?php echo $class_info->name; ?> - <?php echo $section_info ? $section_info->name : 'N/A'; ?></td>
                        </tr>
                        <tr>
                            <td class="label-cell">Fee Month</td>
                            <td class="colon-cell">:</td>
                            <td class="value-cell"><?php echo date('F', $row['creation_timestamp']); ?></td>
                        </tr>
                        <tr>
                            <td class="label-cell">Fee Book No.</td>
                            <td class="colon-cell">:</td>
                            <td class="value-cell"><?php echo isset($student_info->student_code) ? $student_info->student_code : 'N/A'; ?></td>
                        </tr>
                        <tr>
                            <td class="label-cell">Payment Status</td>
                            <td class="colon-cell">:</td>
                            <td class="value-cell">
                                <strong style="color: <?php echo ($row['status'] == '1') ? 'green' : 'red'; ?>">
                                <?php 
                                    if ($row['status'] == '1') {
                                        echo 'PAID';
                                    } else if ($row['status'] == '2') {
                                        echo 'UNPAID';
                                    } else {
                                        echo 'PENDING';
                                    }
                                ?>
                                </strong>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        
        <!-- Payment Title -->
        <div class="payment-title">
            <h4 style="margin: 10px 0; text-align: center; background-color: #f5f5f5; padding: 5px; border: 1px solid #ddd;">PAYMENT DETAILS</h4>
        </div>
        
        <!-- Fee Items Table -->
        <table class="fees-table" border="1" style="margin-top: 5px;">
            <thead>
                <tr>
                    <th>Fee Description</th>
                    <th>Previous Due</th>
                    <th>Previous Adv</th>
                    <th>Fees</th>
                    <th>To Pay</th>
                    <th>Fee Paid</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total_amount = 0;
                $total_paid = 0;
                
                if (empty($fee_items)) {
                    // If no fee items in the new table, display the invoice's fee_type
                    $total_amount = $row['amount'];
                    $total_paid = $row['amount_paid'];
                    ?>
                    <tr>
                        <td><?php echo $row['fee_type']; ?></td>
                        <td>0</td>
                        <td>0</td>
                        <td><?php echo $currency; ?> <?php echo number_format($row['amount'],2,".",",");?></td>
                        <td><?php echo $currency; ?> <?php echo number_format($row['amount'],2,".",",");?></td>
                        <td><?php echo $currency; ?> <?php echo number_format($row['amount_paid'],2,".",",");?></td>
                    </tr>
                <?php
                } else {
                    // Show predefined fee types
                    $fee_types = array(
                        'A.C. CHARGES' => 0,
                        'TUITION FEE' => 0,
                        'COMPUTER-CUM-SMART CLASS' => 0,
                        'ACTIVITIES' => 0
                    );
                    
                    // Map fee items to predefined types where possible
                    foreach ($fee_items as $fee_item) {
                        $total_amount += $fee_item['amount'];
                        $total_paid += $fee_item['amount'];
                        
                        $fee_type = strtoupper($fee_item['fee_type']);
                        
                        // Try to match to predefined types
                        $matched = false;
                        foreach ($fee_types as $key => $value) {
                            if (strpos($fee_type, $key) !== false || strpos($key, $fee_type) !== false) {
                                $fee_types[$key] = $fee_item['amount'];
                                $matched = true;
                                break;
                            }
                        }
                        
                        // If not matched, add as is
                        if (!$matched) {
                            $fee_types[$fee_type] = $fee_item['amount'];
                        }
                    }
                    
                    // Display fee types
                    foreach ($fee_types as $fee_type => $amount) {
                        if ($amount > 0) {
                    ?>
                        <tr>
                            <td><?php echo $fee_type; ?></td>
                            <td>0</td>
                            <td>0</td>
                            <td><?php echo $currency; ?> <?php echo number_format($amount,2,".",",");?></td>
                            <td><?php echo $currency; ?> <?php echo number_format($amount,2,".",",");?></td>
                            <td><?php echo $currency; ?> <?php echo number_format($amount,2,".",",");?></td>
                        </tr>
                    <?php
                        }
                    }
                }
                
                // If no total paid is set, use the amount
                if ($total_paid == 0) {
                    $total_paid = $row['amount_paid'] > 0 ? $row['amount_paid'] : $total_amount;
                }
                
                // Calculate balance
                $balance = $total_amount - $total_paid;
                if ($balance < 0) $balance = 0;
                ?>
                <tr class="totals-row">
                    <td colspan="3" style="text-align: right;">Total :</td>
                    <td><?php echo $currency; ?> <?php echo number_format($total_amount,2,".",",");?></td>
                    <td><?php echo $currency; ?> <?php echo number_format($total_amount,2,".",",");?></td>
                    <td><?php echo $currency; ?> <?php echo number_format($total_paid,2,".",",");?></td>
                </tr>
            </tbody>
        </table>
        
        <!-- Payment Summary -->
        <table class="payment-summary" border="1" style="margin-top: 10px; width: 70%; margin-left: auto; margin-right: 0;">
            <tr style="background-color: #f5f5f5;">
                <th style="padding: 5px; text-align: left;">Total Amount</th>
                <td style="padding: 5px; text-align: right;"><strong><?php echo $currency; ?> <?php echo number_format($total_amount,2,".",",");?></strong></td>
            </tr>
            <tr>
                <th style="padding: 5px; text-align: left;">Amount Paid</th>
                <td style="padding: 5px; text-align: right;"><strong><?php echo $currency; ?> <?php echo number_format($total_paid,2,".",",");?></strong></td>
            </tr>
            <tr>
                <th style="padding: 5px; text-align: left;">Balance Due</th>
                <td style="padding: 5px; text-align: right;"><strong><?php echo $currency; ?> <?php echo number_format($balance,2,".",",");?></strong></td>
            </tr>
            <tr>
                <th style="padding: 5px; text-align: left;">Payment Mode</th>
                <td style="padding: 5px; text-align: right;"><strong><?php echo $payment_method; ?></strong></td>
            </tr>
        </table>
        
        <!-- Footer Section -->
        <div class="footer-section">
            <table border="0" width="100%">
                <tr>
                    <td width="70%" valign="top">
                        <p><strong>In Words: </strong><?php echo numberToWords($total_paid); ?></p>
                        <p><strong>Bank: </strong><?php echo $bank_details ? $bank_details : "_________________"; ?></p>
                        <p><strong>Cheque/CC/DB/DD & Inst. Date: </strong><?php echo $instrument_date ? $instrument_date : "_______________"; ?></p>
                        <?php if ($advance > 0): ?>
                        <p><strong>Advance: </strong><?php echo $currency; ?> <?php echo number_format($advance,2,".",","); ?></p>
                        <?php endif; ?>
                        <?php if ($concession > 0): ?>
                        <p><strong>Concession: </strong><?php echo $currency; ?> <?php echo number_format($concession,2,".",","); ?></p>
                        <?php endif; ?>
                        <p>** Subject to realization of cheque.</p>
                        <p>* Optional</p>
                    </td>
                    <td width="30%" valign="top">
                        <div class="cashier-signature">
                            <p>(CASHIER)</p>
                        </div>
                    </td>
                </tr>
            </table>
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
<?php
        } catch (Exception $e) {
            error_log('Error processing invoice detail: ' . $e->getMessage());
            echo '<div class="alert alert-danger">Error processing invoice: ' . $e->getMessage() . '</div>';
        }
    endforeach;
} catch (Exception $e) {
    error_log('Error retrieving invoice: ' . $e->getMessage());
    echo '<div class="alert alert-danger">Error retrieving invoice: ' . $e->getMessage() . '</div>';
}
?> 