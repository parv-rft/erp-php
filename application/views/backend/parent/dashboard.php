 <!--row -->
 <div class="row">
                    <?php /* Gemini: Removing problematic and redundant PHP block
                    <div class="col-md-3 col-sm-6">
                        <div class="white-box">
                            <div class="r-icon-stats">
                                <i class="ti-user bg-megna"></i>
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
                                <i class="ti-user bg-info"></i>
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
                                <i class="ti-user bg-success"></i>
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
                                    <h4>
                                    <?php 
                                    // $parent_student_logic = $this->db->get_where('student', array('parent_id'=> $this->session->userdata('parent_id')))->row()->student_id;
                                    // $check_daily_attendance = array('date' => date('Y-m-d'), 'status' => '1');
                                    // $get_attendance_information = $this->db->get_where('attendance', $check_daily_attendance, 'student_id', $parent_student_logic);
                                    // $display_attendance_here = $get_attendance_information->num_rows();
                                    // echo $display_attendance_here;
                                    ?>
                                    </h4>
                                    <span class="text-muted"><?php echo get_phrase('Attendance');?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    */ ?>
          
                <!--/row -->
                <!-- .row -->
                <?php if (isset($student_info) && $student_info): // Check if student info exists ?>

                    <!-- Start Recent Attendance Panel -->
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="white-box">
                            <h3 class="box-title" style="font-size: 1.5em; color: #7F7F7F;"><?php echo get_phrase('Recent_Attendance');?> (<?php echo $student_info->name; ?>)</h3>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th><?php echo get_phrase('Date');?></th>
                                            <th><?php echo get_phrase('Day');?></th>
                                            <th><?php echo get_phrase('Status');?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        if ($start_date && $today_date):
                                            $current_dt = new DateTime($start_date);
                                            $end_dt = new DateTime($today_date);
                                            
                                            while ($current_dt <= $end_dt):
                                                $full_date = $current_dt->format('Y-m-d');
                                                $timestamp = $current_dt->getTimestamp();
                                                $day_name = date('l', $timestamp);
                                                $status = isset($attendance_data[$full_date]) ? $attendance_data[$full_date] : null; 
                                        ?>
                                        <tr>
                                            <td><?php echo date('d M Y', $timestamp); ?></td>
                                            <td><?php echo get_phrase(strtolower($day_name)); ?></td>
                                            <td>
                                                <?php if ($status == '1'): ?>
                                                    <span class="label label-success"><?php echo get_phrase('present');?></span>
                                                <?php elseif ($status == '2'): ?>
                                                    <span class="label label-danger"><?php echo get_phrase('absent');?></span>
                                                <?php elseif ($status == '3'): ?>
                                                    <span class="label label-warning"><?php echo get_phrase('holiday');?></span>
                                                <?php else: // No record found or other status ?>
                                                    <span class="label label-default"><?php echo get_phrase('undefined');?></span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php 
                                            $current_dt->modify('+1 day');
                                            endwhile;
                                        else: 
                                        ?>
                                         <tr>
                                            <td colspan="3" style="text-align: center;"><?php echo get_phrase('attendance_data_not_available');?></td>
                                        </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- End Recent Attendance Panel -->

                    <!-- Start Teacher List Panel -->
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="white-box">
                            <h3 class="box-title" style="font-size: 1.5em; color: #7F7F7F;"><?php echo get_phrase('School_Teachers');?></h3>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th width="80"><div><?php echo get_phrase('photo');?></div></th>
                                            <th><div><?php echo get_phrase('name');?></div></th>
                                            <th><div><?php echo get_phrase('role');?></div></th>
                                            <th><div><?php echo get_phrase('email');?></div></th>
                                            <th><div><?php echo get_phrase('phone');?></div></th>
                                            <th><div><?php echo get_phrase('gender');?></div></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($all_teachers as $row): ?>
                                        <tr>
                                            <td><img src="<?php echo $this->crud_model->get_image_url('teacher', $row['teacher_id']);?>" class="img-circle" width="30"></td>
                                            <td><?php echo $row['name'];?></td>
                                            <td>
                                                <?php 
                                                if($row['role'] == 1) echo get_phrase('class_teacher');
                                                elseif($row['role'] == 2) echo get_phrase('subject_teacher');
                                                else echo get_phrase('teacher'); 
                                                ?>
                                            </td>
                                            <td><?php echo $row['email'] ? $row['email'] : '-';?></td>
                                            <td><?php echo $row['phone'] ? $row['phone'] : '-';?></td>
                                            <td><?php echo $row['sex'] ? $row['sex'] : '-'; ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                        <?php if (empty($all_teachers)): ?>
                                            <tr>
                                                <td colspan="6" style="text-align: center;"><?php echo get_phrase('no_teachers_found');?></td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- End Teacher List Panel Col -->

                    <!-- Start Recent Payment History Panel -->
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="white-box">
                            <h3 class="box-title" style="font-size: 1.5em; color: #7F7F7F;"><?php echo get_phrase('Recent_Payment_History');?> (<?php echo $student_info->name; ?>)</h3>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th><div>#</div></th>
                                            <th><div><?php echo get_phrase('title');?></div></th>
                                            <th><div><?php echo get_phrase('method');?></div></th>
                                            <th><div><?php echo get_phrase('amount');?></div></th>
                                            <th><div><?php echo get_phrase('date');?></div></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                        $count = 1;
                                        $currency_symbol = $this->db->get_where('settings', array('type' => 'currency'))->row()->description; 
                                        foreach($recent_payments as $row): 
                                    ?>
                                        <tr>
                                            <td><?php echo $count++;?></td>
                                            <td><?php echo $row['title'];?></td>
                                            <td>
                                                <?php 
                                                    if($row['method'] == 1) echo get_phrase('card');
                                                    if($row['method'] == 2) echo get_phrase('cash');
                                                    if($row['method'] == 3) echo get_phrase('cheque');
                                                    if($row['method'] == 'paypal') echo get_phrase('paypal');
                                                ?>
                                            </td>
                                            <td><?php echo $currency_symbol; ?><?php echo $row['amount'];?></td>
                                            <td><?php echo date('d M, Y', $row['timestamp']);?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty($recent_payments)): ?>
                                        <tr>
                                            <td colspan="5" style="text-align: center;"><?php echo get_phrase('no_recent_payments_found');?></td>
                                        </tr>
                                    <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- End Recent Payment History Panel -->

                <?php else: ?>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                         <div class="alert alert-warning">
                            <?php echo get_phrase('no_student_linked_to_this_parent_account'); ?>
                         </div>
                    </div>
                <?php endif; // End check for student info ?>

</div>