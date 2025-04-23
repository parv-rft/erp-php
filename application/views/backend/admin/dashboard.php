 <!--row -->
            <div class="row">
                    <div class="col-md-3 col-sm-6">
                        <div class="white-box">
                            <div class="r-icon-stats">
                                <i class="ti-user bg-inverse"></i>
                                <div class="bodystate">
                                    <h4><?php echo $this->db->count_all_results('student');?></h4>
                                    <span class="text-muted"><?php echo get_phrase('Students');?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="white-box">
                            <div class="r-icon-stats">
                                <i class="ti-blackboard bg-inverse"></i>
                                <div class="bodystate">
                                    <h4><?php echo $this->db->count_all_results('teacher');?></h4>
                                    <span class="text-muted"><?php echo get_phrase('Teachers');?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="white-box">
                            <div class="r-icon-stats">
                                <i class="ti-home bg-inverse"></i>
                                <div class="bodystate">
                                    <h4><?php echo $this->db->count_all_results('parent');?></h4>
                                    <span class="text-muted"><?php echo get_phrase('parents');?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="white-box">
                            <div class="r-icon-stats">
                                <i class="ti-wallet bg-inverse"></i>
                                <div class="bodystate">
                                    <h4><?php echo $this->db->count_all_results('accountant');?></h4>
                                    <span class="text-muted"><?php echo get_phrase('Accontants');?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="white-box">
                            <div class="r-icon-stats">
                                <i class="bg-inverse">₹</i>
                                <div class="bodystate">
                                <?php 
                                $this->db->select_sum('amount');
                                $this->db->from('payment');
                                $this->db->where('payment_type', 'expense');
                                $query = $this->db->get();
                                $expense_amount = $query->row()->amount;
                                ?>
                                    <h4><?php echo $this->db->get_where('settings', array('type' => 'currency'))->row()->description;?> <?php echo $expense_amount;?></h4>
                                    <span class="text-muted"><?php echo get_phrase('Expense');?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="white-box">
                            <div class="r-icon-stats">
                                <i class=" bg-inverse"> ₹</i>
                                <div class="bodystate">

                                <?php 
                                $this->db->select_sum('amount');
                                $this->db->from('payment');
                                $this->db->where('payment_type', 'income');
                                $query = $this->db->get();
                                $income_amount = $query->row()->amount; ?>
                                    <h4>
                                    <?php echo $this->db->get_where('settings', array('type' => 'currency'))->row()->description;?> <?php echo $income_amount;?>
                                    </h4>
                                    <span class="text-muted"><?php echo get_phrase('Income');?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="white-box">
                            <div class="r-icon-stats">
                                <i class="ti-id-badge bg-inverse"></i>
                                <div class="bodystate">
                                    <h4><?php echo $this->db->count_all_results('admin');?></h4>
                                    <span class="text-muted"><?php echo get_phrase('Admin');?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="white-box">
                            <div class="r-icon-stats">
                                <i class="ti-bar-chart bg-inverse"></i>
                                <div class="bodystate">
                                    <h4>
                                    <?php 

                                    $check_daily_attendance = array('date' => date('Y-m-d'), 'status' => '1');
                                    $get_attendance_information = $this->db->get_where('attendance', $check_daily_attendance);
                                    $display_attendance_here = $get_attendance_information->num_rows();
                                    echo $display_attendance_here;
                                    ?>
                                    
                                    </h4>
                                    <span class="text-muted"><?php echo get_phrase('Attendance');?></span>
                                </div>
                            </div>
                        </div>
                    </div>

            </div>
                <!--/row -->

                <!-- ADD NEW FEES TABLE ROW START -->
                 <div class="row">
                    <div class="col-sm-12">
                        <div class="white-box">
                            <h3 class="box-title m-b-0" style="font-size: 2em; color: #7F7F7F;"><?php echo get_phrase('Recent Fee Payments');?></h3><br><br>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th><?php echo get_phrase('Student Name');?></th>
                                            <th><?php echo get_phrase('Class');?></th>
                                            <th><?php echo get_phrase('Title');?></th>
                                            <th><?php echo get_phrase('Total Amount');?></th>
                                            <th><?php echo get_phrase('Paid Amount');?></th>
                                            <th><?php echo get_phrase('Description');?></th>
                                            <th><?php echo get_phrase('Phone No');?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $currency_symbol = $this->db->get_where('settings', array('type' => 'currency'))->row()->description;
                                        
                                        // --- Modified Query with Joins (including invoice) ---
                                        $this->db->select('payment.*, student.name as student_name, student.phone, class.name as class_name, invoice.amount as total_invoice_amount');
                                        $this->db->from('payment');
                                        $this->db->join('student', 'student.student_id = payment.student_id', 'left');
                                        $this->db->join('class', 'class.class_id = student.class_id', 'left');
                                        $this->db->join('invoice', 'invoice.invoice_id = payment.invoice_id', 'left'); // Added join to invoice
                                        $this->db->where('payment.payment_type', 'income');
                                        $this->db->order_by('payment.timestamp', 'desc'); 
                                        $this->db->limit(5); 
                                        $recent_payments = $this->db->get()->result_array();
                                        // --- End Modified Query ---

                                        $payment_count = 0; // Counter for loop limit
                                        foreach ($recent_payments as $payment):
                                            if ($payment_count >= 3) break; // Limit to 3 entries
                                        ?>
                                        <tr>
                                            <td><?php echo isset($payment['student_name']) ? $payment['student_name'] : 'N/A';?></td>
                                            <td><?php echo isset($payment['class_name']) ? $payment['class_name'] : 'N/A';?></td>
                                            <td><?php echo $payment['title'];?></td>
                                            <td><?php echo $currency_symbol . (isset($payment['total_invoice_amount']) ? $payment['total_invoice_amount'] : 'N/A');?></td>
                                            <td><?php echo $currency_symbol . $payment['amount'];?></td>
                                            <td><?php echo $payment['description'];?></td>
                                            <td><?php echo isset($payment['phone']) ? $payment['phone'] : 'N/A';?></td>
                                        </tr>
                                        <?php 
                                            $payment_count++; // Increment counter
                                            endforeach;
                                        ?>
                                        <?php if (count($recent_payments) == 0): ?>
                                            <tr>
                                                <td colspan="7" style="text-align: center;"><?php echo get_phrase('No recent payments found');?></td>
                                            </tr>
                                        <?php endif; ?>
                                        <!-- View All Button Row -->
                                        <tr>
                                            <td colspan="7" style="text-align: right;">
                                                <a href="<?php echo base_url('admin/student_invoice');?>" class="btn btn-info"><?php echo get_phrase('View All Payments');?></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ADD NEW FEES TABLE ROW END -->

                <!-- ADD BUS & ATTENDANCE INFO ROW START -->
                <div class="row">
                    <!-- Bus Information Table -->
                    <div class="col-sm-12">
                        <div class="white-box">
                            <h3 class="box-title m-b-0" style="font-size: 2em; color: #7F7F7F;"><?php echo get_phrase('Bus Information');?></h3><br><br>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th><?php echo get_phrase('Bus Name');?></th>
                                            <th><?php echo get_phrase('Route');?></th>
                                            <th><?php echo get_phrase('Vehicle');?></th>
                                            <th><?php echo get_phrase('Route Fee');?></th>
                                            <th><?php echo get_phrase('Description');?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $currency_symbol = $this->db->get_where('settings', array('type' => 'currency'))->row()->description;
                                        
                                        // --- Modified Query with Joins ---
                                        $this->db->select('transport.*, transport_route.name as route_actual_name, vehicle.name as vehicle_actual_name, vehicle.vehicle_number');
                                        $this->db->from('transport');
                                        $this->db->join('transport_route', 'transport_route.transport_route_id = transport.transport_route_id', 'left');
                                        $this->db->join('vehicle', 'vehicle.vehicle_id = transport.vehicle_id', 'left');
                                        $transports = $this->db->get()->result_array();
                                        // --- End Modified Query ---

                                        $transport_count = 0; // Counter for loop limit
                                        foreach ($transports as $transport):
                                            if ($transport_count >= 3) break; // Limit to 3 entries
                                            $vehicle_display = isset($transport['vehicle_actual_name']) ? $transport['vehicle_actual_name'] . ' (' . $transport['vehicle_number'] . ')' : 'N/A';
                                            $route_display = isset($transport['route_actual_name']) ? $transport['route_actual_name'] : 'N/A';
                                        ?>
                                        <tr>
                                            <td><?php echo $transport['name'];?></td>
                                            <td><?php echo $route_display; ?></td>
                                            <td><?php echo $vehicle_display; ?></td>
                                            <td><?php echo $currency_symbol . $transport['route_fare'];?></td>
                                            <td><?php echo $transport['description'];?></td>
                                        </tr>
                                        <?php 
                                            $transport_count++; // Increment counter
                                            endforeach;
                                        ?>
                                        <?php if (count($transports) == 0): ?>
                                            <tr>
                                                <td colspan="5" style="text-align: center;"><?php echo get_phrase('No transport information found');?></td>
                                            </tr>
                                        <?php endif; ?>
                                        <!-- View All Button Row -->
                                        <tr>
                                            <td colspan="5" style="text-align: right;">
                                                <a href="<?php echo base_url('transportation/transport');?>" class="btn btn-info"><?php echo get_phrase('View All Transport');?></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- ADD BUS & ATTENDANCE INFO ROW END -->

                <!-- .row -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="white-box">
                            <h3 class="box-title m-b-0" style="font-size: 2em; color: #7F7F7F;"><?php echo get_phrase('Recently Added Teachers');?></h3><br><br>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th><?php echo get_phrase('Image');?></th>
                                            <th><?php echo get_phrase('Name');?></th>
                                            <th><?php echo get_phrase('Email');?></th>
                                            <th><?php echo get_phrase('Phone');?></th>
                                            <th><?php echo get_phrase('Role');?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    <tr>
                            <?php 
                            $teacher_count = 0; // Counter for loop limit
                            $get_teacher_from_model = $this->crud_model->list_all_teacher_and_order_with_teacher_id();
                                    foreach ($get_teacher_from_model as $key => $teacher):
                                            if ($teacher_count >= 3) break; // Limit to 3 entries
                                            // Basic role mapping (adjust if needed)
                                            $role_display = ($teacher['role'] == '1') ? get_phrase('Admin') : (($teacher['role'] == '2') ? get_phrase('Teacher') : get_phrase('Staff')); 
                                    ?>
                                            <td><img src="<?php echo $teacher['face_file'];?>" class="img-circle" width="40px"></td>
                                            <td><?php echo $teacher['name'];?></td>
                                            <td><?php echo $teacher['email'];?></td>
                                            <td><?php echo $teacher['phone'];?></td>
                                            <td><?php echo $role_display;?></td>
                                        </tr>
                                    <?php 
                                        $teacher_count++; // Increment counter
                                        endforeach;
                                    ?>
                                    <?php if (count($get_teacher_from_model) == 0): ?>
                                            <tr>
                                                <td colspan="5" style="text-align: center;"><?php echo get_phrase('No teachers found');?></td>
                                            </tr>
                                    <?php endif; ?> 
                                    <!-- View All Button Row -->
                                    <tr>
                                        <td colspan="5" style="text-align: right;">
                                            <a href="<?php echo base_url();?>admin/teacher" class="btn btn-info"><?php echo get_phrase('View All Teachers');?></a>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <h3 class="box-title m-b-0" style="font-size: 2em; color: #7F7F7F;"><?php echo get_phrase('Recently Added Students');?></h3><br><br>
                            <div class="table-responsive">
                            <table class="table">
                                    <thead>
                                        <tr>
                                            <th><?php echo get_phrase('Image');?></th>
                                            <th><?php echo get_phrase('Roll No');?></th>
                                            <th><?php echo get_phrase('Name');?></th>
                                            <th><?php echo get_phrase('Class');?></th>
                                            <th><?php echo get_phrase('Phone');?></th>
                                            <th><?php echo get_phrase('Email');?></th>
                                            <th><?php echo get_phrase('Parent');?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                            <?php 
                            $student_count = 0; // Counter for loop limit
                            $get_student_from_model = $this->crud_model->list_all_student_and_order_with_student_id();
                                    foreach ($get_student_from_model as $key => $student):
                                            if ($student_count >= 3) break; // Limit to 3 entries
                                            $parent_name = $this->crud_model->get_type_name_by_id('parent', $student['parent_id']);
                                    ?>
                                            <td><img src="<?php echo $student['face_file'];?>" class="img-circle" width="40px"></td>
                                            <td><?php echo $student['roll'];?></td>
                                            <td><?php echo $student['name'];?></td>
                                            <td><?php echo $this->crud_model->get_type_name_by_id('class', $student['class_id']);?></td>
                                            <td><?php echo $student['phone'];?></td>
                                            <td><?php echo $student['email'];?></td>
                                            <td><?php echo $parent_name ? $parent_name : get_phrase('N/A');?></td>
                                        </tr>
                                    <?php 
                                        $student_count++; // Increment counter
                                        endforeach;
                                    ?>
                                       <?php if (count($get_student_from_model) == 0): ?>
                                            <tr>
                                                <td colspan="7" style="text-align: center;"><?php echo get_phrase('No students found');?></td>
                                            </tr>
                                        <?php endif; ?> 
                                    <!-- View All Button Row -->
                                    <tr>
                                        <td colspan="7" style="text-align: right;">
                                            <a href="<?php echo base_url();?>admin/student_information" class="btn btn-info"><?php echo get_phrase('View All Students');?></a>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row -->