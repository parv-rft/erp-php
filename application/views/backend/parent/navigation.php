    <!-- Left navbar-header -->
<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse slimscrollsidebar">
        <ul class="nav" id="side-menu">
            <li class="sidebar-search hidden-sm hidden-md hidden-lg">
                <!-- input-group -->
                <div class="input-group custom-search-form">
                    <input type="text" class="form-control" placeholder="Search..."> <span class="input-group-btn">
                        <button class="btn btn-default" type="button"> <i class="fa fa-search"></i> </button>
                            </span> </div>
                        <!-- /input-group -->
            </li>
            
            <li class="user-pro">
                        <?php
                            $student_id = $this->session->userdata('student_id');
                            $logged_in_parent_type = $this->session->userdata('logged_in_parent_type');
                            $parent_photo_filename = $this->session->userdata('parent_photo'); // Assuming you set this in Login_model

                            $face_file = 'uploads/defaults/user-default.png'; // Default image

                            if ($parent_photo_filename && file_exists('uploads/parent_image/' . $parent_photo_filename)) {
                                $face_file = 'uploads/parent_image/' . $parent_photo_filename;
                            } elseif ($student_id && $logged_in_parent_type) {
                                // Fallback construction if parent_photo not in session but student_id and type are
                                $potential_face_file = 'uploads/parent_image/' . $student_id . '_' . $logged_in_parent_type . '.jpg';
                                if (file_exists($potential_face_file)) {
                                    $face_file = $potential_face_file;
                                }
                            }
                        ?>

                    <a href="#" class="waves-effect"><img src="<?php echo base_url() . $face_file;?>" alt="user-img" class="img-circle"> <span class="hide-menu">

                       <?php 
                                echo $this->session->userdata('parent_name'); // Directly use parent_name from session
                        ?>
                        <span class="fa arrow"></span></span>
                    </a>
                        <ul class="nav nav-second-level">
                            <li><a href="<?php echo base_url();?>parents/manage_profile"><i class="ti-user"></i> My Profile</a></li>
                            <li><a href="javascript:void(0)"><i class="ti-email"></i> Inbox</a></li>
                            <li><a href="javascript:void(0)"><i class="ti-settings"></i> Account Setting</a></li>
                            <li><a href="<?php echo base_url();?>login/logout"><i class="fa fa-power-off"></i> Logout</a></li>
                        </ul>
                </li>



    <li> <a href="<?php echo base_url();?>parents/dashboard" class="waves-effect"><i class="ti-dashboard p-r-10"></i> <span class="hide-menu"><?php echo get_phrase('Dashboard') ;?></span></a> </li>

    

    <li> <a href="#" class="waves-effect"><i data-icon="&#xe006;" class="fa fa-book p-r-10"></i> <span class="hide-menu"><?php echo get_phrase('Academics');?><span class="fa arrow"></span></span></a>
        
        <ul class=" nav nav-second-level<?php
            if ($page_name == 'subject' ||
                    $page_name == 'teacher' ||
                    $page_name == 'class_mate' ||
                    $page_name == 'assignment' || $page_name == 'study_material' )
                echo 'opened active';
            ?>">


            
                <li class="<?php if ($page_name == 'subject') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>parents/subject">
                        <i class="fa fa-angle-double-right p-r-10"></i>
                        <span class="hide-menu"><?php echo get_phrase('Subject'); ?></span>
                    </a>
                </li>


                <li class="<?php if ($page_name == 'teacher') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>parents/teacher">
                        <i class="fa fa-angle-double-right p-r-10"></i>
                        <span class="hide-menu"><?php echo get_phrase('Teacher'); ?></span>
                    </a>
                </li>

                    
                <li class="<?php if ($page_name == 'class_mate') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>parents/class_mate">
                        <i class="fa fa-angle-double-right p-r-10"></i>
                            <span class="hide-menu"><?php echo get_phrase('Class Mate'); ?></span>
                    </a>
                </li>

                    
                <li class="<?php if ($page_name == 'assignment') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>assignment/assignment">
                        <i class="fa fa-angle-double-right p-r-10"></i>
                            <span class="hide-menu"><?php echo get_phrase('Assignment'); ?></span>
                    </a>
                </li>

                <li class="<?php if ($page_name == 'study_material') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>studymaterial/study_material">
                        <i class="fa fa-angle-double-right p-r-10"></i>
                            <span class="hide-menu"><?php echo get_phrase('Study Material'); ?></span>
                    </a>
                </li>

                <li class="<?php if ($page_name == 'class_routine') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>parents/class_routine">
                        <i class="fa fa-angle-double-right p-r-10"></i>
                            <span class="hide-menu"><?php echo get_phrase('Class Timetable'); ?></span>
                    </a>
                </li>
 
 
         </ul>
    </li>

    <!-- 
             <li class="<?php if ($page_name == 'invoice') echo 'active'; ?> ">
                 <a href="<?php echo base_url(); ?>parents/invoice">
                     <i class="fa fa-paypal p-r-10"></i>
                         <span class="hide-menu"><?php echo get_phrase('Invoice'); ?></span>
                 </a>
             </li> 
     -->

        <li class="<?php if ($page_name == 'payment_history') echo 'active'; ?> ">
                <a href="<?php echo base_url(); ?>parents/payment_history">
                    <i class="fa fa-credit-card p-r-10"></i>
                        <span class="hide-menu"><?php echo get_phrase('Payment History'); ?></span>
                </a>
        </li>               
                                
            <li class="<?php if ($page_name == 'manage_profile') echo 'active'; ?> ">
                <a href="<?php echo base_url(); ?>parents/manage_profile">
                    <i class="fa fa-gears p-r-10"></i>
                        <span class="hide-menu"><?php echo get_phrase('manage_profile'); ?></span>
                </a>
            </li>

            <li class="">
                <a href="<?php echo base_url(); ?>login/logout">
                    <i class="fa fa-sign-out p-r-10"></i>
                        <span class="hide-menu"><?php echo get_phrase('Logout'); ?></span>
                </a>
            </li>
                  
                  
        </ul>
    </div>
</div>
<!-- Left navbar-header end -->