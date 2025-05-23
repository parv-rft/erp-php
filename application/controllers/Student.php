<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Student extends CI_Controller { 

    function __construct() {
        parent::__construct();
        		$this->load->database();                                //Load Databse Class
                $this->load->library('session');					    //Load library for session
                $this->load->library('role_based_access');
                $this->load->model('timetable_model');                  //Load timetable model
  
        // Check if user is logged in and has student role
        $this->role_based_access->check_access('student');
    }

     /*student dashboard code to redirect to student page if successfull login** */
     function dashboard() {
        if ($this->session->userdata('student_login') != 1) redirect(base_url(), 'refresh');

        $student_id = $this->session->userdata('student_id');
        
        // Get today and the date 2 days prior
        $today_dt = new DateTime();
        $start_dt = new DateTime();
        $start_dt->modify('-2 days');

        $today_date = $today_dt->format('Y-m-d');
        $start_date = $start_dt->format('Y-m-d');

        // Fetch attendance for the last 3 days (inclusive)
        $this->db->select('date, status'); // Ensure we select status
        $this->db->where('student_id', $student_id);
        $this->db->where('date >=', $start_date);
        $this->db->where('date <=', $today_date);
        $this->db->order_by('date', 'DESC'); // Order descending to easily get last 3
        $attendance_query = $this->db->get('attendance');
        
        // Process attendance data into a more usable format (date => status)
        $attendance_data = array();
        if ($attendance_query !== false && $attendance_query->num_rows() > 0) {
            foreach ($attendance_query->result() as $row) {
                // Ensure date and status properties exist before accessing
                if (isset($row->date) && isset($row->status)) {
                    // Use Y-m-d format as key
                    $attendance_data[date('Y-m-d', strtotime($row->date))] = $row->status; 
                } else {
                    log_message('error', 'Attendance record missing date or status property for student_id: ' . $student_id);
                }
            }
        } elseif ($attendance_query === false) {
            log_message('error', 'Database error fetching attendance: ' . $this->db->error()['message']);
        }

        // Fetch all teachers for the dashboard view
        $this->db->select('teacher_id, name, role, email, phone, sex');
        $all_teachers = $this->db->get('teacher')->result_array();

        // Fetch last 4 payment records for the student
        $this->db->select('title, description, method, amount, timestamp');
        $this->db->where('student_id', $student_id);
        $this->db->order_by('timestamp', 'DESC');
        $this->db->limit(4);
        $recent_payments = $this->db->get('payment')->result_array();

       	$page_data['page_name'] = 'dashboard';
        $page_data['page_title'] = get_phrase('student Dashboard');
        $page_data['attendance_data'] = $attendance_data; // Pass attendance data to view
        $page_data['start_date'] = $start_date;
        $page_data['today_date'] = $today_date;
        $page_data['all_teachers'] = $all_teachers; // Pass all teachers data to view
        $page_data['recent_payments'] = $recent_payments; // Pass recent payments
        // These month/year vars are no longer needed for this display
        // $page_data['current_month'] = $current_month;
        // $page_data['current_year'] = $current_year;
        // $page_data['days_in_month'] = $days_in_month;

        $this->load->view('backend/index', $page_data);
    }
	/******************* / student dashboard code to redirect to student page if successfull login** */

    function manage_profile($param1 = null, $param2 = null, $param3 = null){
        if ($this->session->userdata('student_login') != 1) redirect(base_url(), 'refresh');
        if ($param1 == 'update') {
    
    
            $data['name']   =   $this->input->post('name');
            $data['email']  =   $this->input->post('email');
    
            $this->db->where('student_id', $this->session->userdata('student_id'));
            $this->db->update('student', $data);
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/student_image/' . $this->session->userdata('student_id') . '.jpg');
            $this->session->set_flashdata('flash_message', get_phrase('Info Updated'));
            redirect(base_url() . 'student/manage_profile', 'refresh');
           
        }
    
        if ($param1 == 'change_password') {
            $data['new_password']           =   sha1($this->input->post('new_password'));
            $data['confirm_new_password']   =   sha1($this->input->post('confirm_new_password'));
    
            if ($data['new_password'] == $data['confirm_new_password']) {
               
               $this->db->where('student_id', $this->session->userdata('student_id'));
               $this->db->update('student', array('password' => $data['new_password']));
               $this->session->set_flashdata('flash_message', get_phrase('Password Changed'));
            }
    
            else{
                $this->session->set_flashdata('error_message', get_phrase('Type the same password'));
            }
            redirect(base_url() . 'student/manage_profile', 'refresh');
        }
    
            $page_data['page_name']     = 'manage_profile';
            $page_data['page_title']    = get_phrase('Manage Profile');
            $page_data['edit_profile']  = $this->db->get_where('student', array('student_id' => $this->session->userdata('student_id')))->result_array();
            $this->load->view('backend/index', $page_data);
        }


        function subject (){

            $student_profile = $this->db->get_where('student', array('student_id' => $this->session->userdata('student_id')))->row();
            $select_student_class_id = $student_profile->class_id;

            $page_data['page_name']     = 'subject';
            $page_data['page_title']    = get_phrase('Class Subjects');
            $page_data['select_subject']  = $this->db->get_where('subject', array('class_id' => $select_student_class_id))->result_array();
            $this->load->view('backend/index', $page_data);
        }

        function teacher (){

            $student_profile = $this->db->get_where('student', array('student_id' => $this->session->userdata('student_id')))->row();
            $class_id = $student_profile->class_id;

            // Find all unique teacher IDs associated with subjects for this class
            $this->db->select('teacher_id');
            $this->db->distinct();
            $this->db->where('class_id', $class_id);
            $subject_query = $this->db->get('subject');
            $teacher_ids = array();
            if ($subject_query->num_rows() > 0) {
                foreach ($subject_query->result() as $row) {
                    if (!empty($row->teacher_id)) { // Ensure teacher_id is not empty
                        $teacher_ids[] = $row->teacher_id;
                    }
                }
            }
            
            $class_teachers = array();
            if (!empty($teacher_ids)) {
                 // Fetch details for these teachers
                $this->db->select('teacher_id, name, role, email, phone, sex');
                $this->db->where_in('teacher_id', $teacher_ids);
                $class_teachers = $this->db->get('teacher')->result_array();
            }

            $page_data['page_name']     = 'teacher';
            $page_data['page_title']    = get_phrase('Class Teachers');
            $page_data['teachers']  = $class_teachers; // Pass the array of teachers
            $this->load->view('backend/index', $page_data);
        }

        function class_mate (){

            $current_student_id = $this->session->userdata('student_id');
            $student_profile = $this->db->get_where('student', array('student_id' => $current_student_id))->row();
            
            if ($student_profile) {
                $class_id = $student_profile->class_id;

                // Fetch classmates (students in the same class, excluding the current student)
                $this->db->select('name, phone, email, sex'); // Select required columns
                $this->db->where('class_id', $class_id);
                $this->db->where('student_id !=', $current_student_id); // Exclude self
                $page_data['classmates'] = $this->db->get('student')->result_array();
            } else {
                // Handle case where student profile might not be found (optional, but good practice)
                $page_data['classmates'] = array(); 
            }

            // $page_data['select_student_class_id']  = $student_profile->class_id; // No longer needed in view
            $page_data['page_name']     = 'class_mate';
            $page_data['page_title']    = get_phrase('Class Mate');
            $this->load->view('backend/index', $page_data);
        }

        function class_routine(){
            if ($this->session->userdata('student_login') != 1)
                redirect(base_url(), 'refresh');
            
            $student_profile = $this->db->get_where('student', array('student_id' => $this->session->userdata('student_id')))->row();
            
            // Redirect to calendar timetable view
            redirect(base_url() . 'student/calendar_timetable', 'refresh');
        }

        function invoice($param1 = null, $param2 = null, $param3 = null){

            // --- TEMPORARY DEBUGGING --- 
            // Commenting out problematic sections to isolate the endless loading issue
            // Keep PayPal processing commented out for now
            /* 
            if($param1 == 'make_payment'){

                $invoice_id = $this->input->post('invoice_id');
                $payment_email = $this->db->get_where('settings', array('type' => 'paypal_email'))->row();
                $select_invoice = $this->db->get_where('invoice', array('invoice_id' => $invoice_id))->row();

                // SENDING USER TO PAYPAL TERMINAL.
                $this->paypal->add_field('rm', 2);
                $this->paypal->add_field('no_note', 0);
                $this->paypal->add_field('item_name', $select_invoice->title);
                $this->paypal->add_field('amount', $select_invoice->due);
                $this->paypal->add_field('custom', $select_invoice->invoice_id);
                $this->paypal->add_field('business', $payment_email->description);
                $this->paypal->add_field('notify_url', base_url('invoice/paypal_ipn'));
                $this->paypal->add_field('cancel_return', base_url('invoice/paypal_cancel'));
                $this->paypal->add_field('return', site_url('invoice/paypal_success'));

                $this->paypal->submit_paypal_post();
                //submitting info to the paypal teminal
            }


            if($param1 == 'paypal_ipn'){
                if($this->paypal->validate_ipn() == true){
                        $ipn_response = '';
                        foreach ($_POST as $key => $value){
                            $value = urlencode(stripslashes($value));
                            $ipn_response .= "\n$key=$value";
                        }

                    $page_data['payment_details']   = $ipn_response;
                    $page_data['payment_timestamp'] = strtotime(date("m/d/Y"));
                    $page_data['payment_method']    = '1';
                    $page_data['status']            = 'paid';
                    $invoice_id                = $_POST['custom'];
                    $this->db->where('invoice_id', $invoice_id);
                    $this->db->update('invoice', $page_data);

                    $data2['method']       =   '1';
                    $data2['invoice_id']   =   $_POST['custom'];
                    $data2['timestamp']    =   strtotime(date("m/d/Y"));
                    $data2['payment_type'] =   'income';
                    $data2['title']        =   $this->db->get_where('invoice' , array('invoice_id' => $data2['invoice_id']))->row()->title;
                    $data2['description']  =   $this->db->get_where('invoice' , array('invoice_id' => $data2['invoice_id']))->row()->description;
                    $data2['student_id']   =   $this->db->get_where('invoice' , array('invoice_id' => $data2['invoice_id']))->row()->student_id;
                    $data2['amount']       =   $this->db->get_where('invoice' , array('invoice_id' => $data2['invoice_id']))->row()->amount;
                    $this->db->insert('payment' , $data2);

                }
            }

            if($param1 == 'paypal_cancel'){
                $this->session->set_flashdata('error_message', get_phrase('Payment Cancelled'));
                redirect(base_url() . 'student/invoice', 'refresh');
                }
    
            if($param1 == 'paypal_success'){
                $this->session->set_flashdata('flash_message', get_phrase('Payment Successful'));
                redirect(base_url() . 'student/invoice', 'refresh');
            }
            */
            // --- END TEMPORARY DEBUGGING ---
           
            // Fetch student details once
            $student_info = $this->db->get_where('student', array('student_id' => $this->session->userdata('student_id')))->row();
            $student_id = $student_info->student_id; // Keep the ID
            $student_name = $student_info->name; // Get the name

            // Fetch the invoices for the student
            $page_data['invoices']     = $this->db->get_where('invoice', array('student_id' => $student_id))->result_array();
            $page_data['student_name'] = $student_name; // Pass student name to the view

            $page_data['page_name']     = 'invoice';
            $page_data['page_title']    = get_phrase('All Invoices');
            $this->load->view('backend/index', $page_data);
        }

        function payment_history(){

            $student_id = $this->session->userdata('student_id'); // Get student ID directly

            // Fetch payment records for the student
            $page_data['payments']     = $this->db->get_where('payment', array('student_id' => $student_id))->result_array(); 
            $page_data['page_name']     = 'payment_history';
            $page_data['page_title']    = get_phrase('Payment History'); // Corrected title
            $this->load->view('backend/index', $page_data);


        }

        function attendance_report($month = '', $year = '')
        {
            if ($this->session->userdata('student_login') != 1) {
                redirect(base_url(), 'refresh');
            }

            $student_id = $this->session->userdata('student_id');
            $active_sms_service = $this->db->get_where('settings' , array('type' => 'active_sms_service'))->row()->description;

            // If month or year is not provided, use the current month and year
            if ($month == '') {
                $month = date('m');
            }
            if ($year == '') {
                $year = date('Y');
            }
            
            // Recalculate days in month based on selected/current month and year
            $days_in_month = function_exists('cal_days_in_month') ? cal_days_in_month(CAL_GREGORIAN, $month, $year) : 31; 

            // Fetch attendance for the selected month and year
            $this->db->select('date, status');
            $this->db->where('student_id', $student_id);
            $this->db->where('MONTH(date)', $month);
            $this->db->where('YEAR(date)', $year);
            $this->db->order_by('date', 'ASC');
            $attendance_query = $this->db->get('attendance');

            // Process attendance data and calculate totals
            $attendance_data = array();
            $total_present = 0;
            $total_absent = 0;

            if ($attendance_query !== false && $attendance_query->num_rows() > 0) {
                foreach ($attendance_query->result() as $row) {
                    if (isset($row->date) && isset($row->status)) {
                        $date_key = date('Y-m-d', strtotime($row->date));
                        $attendance_data[$date_key] = $row->status;
                        if ($row->status == '1') { // Assuming 1 = Present
                            $total_present++;
                        } elseif ($row->status == '2') { // Assuming 2 = Absent
                            $total_absent++;
                        }
                        // Ignore status 3 (Holiday) and others for counts
                    } else {
                        log_message('error', 'Attendance record missing date or status property for student_id: ' . $student_id . ' on date ' . (isset($row->date) ? $row->date : 'unknown'));
                    }
                }
            } elseif ($attendance_query === false) {
                 log_message('error', 'Database error fetching attendance report: ' . print_r($this->db->error(), true));
            }

            $page_data['page_name']        = 'attendance_report';
            $page_data['page_title']       = get_phrase('Attendance Report');
            $page_data['attendance_data']  = $attendance_data;
            $page_data['month']            = $month;
            $page_data['year']             = $year;
            $page_data['days_in_month']    = $days_in_month;
            $page_data['total_present']    = $total_present;
            $page_data['total_absent']     = $total_absent;

            $this->load->view('backend/index', $page_data);
        }

        function class_timetable() {
            if ($this->session->userdata('student_login') != 1) {
                redirect(base_url(), 'refresh');
            }
            
            $student_id = $this->session->userdata('student_id');
            $class_id = $this->db->get_where('student', array('student_id' => $student_id))->row()->class_id;
            $section_id = $this->db->get_where('student', array('student_id' => $student_id))->row()->section_id;
            
            $page_data['class_id'] = $class_id;
            $page_data['section_id'] = $section_id;
            $page_data['page_name'] = 'class_timetable';
            $page_data['page_title'] = get_phrase('Class Timetable');
            $this->load->view('backend/index', $page_data);
        }

        function timetable() {
            if ($this->session->userdata('student_login') != 1) {
                redirect(base_url(), 'refresh');
            }
            
            $student_id = $this->session->userdata('student_id');
            $student = $this->db->get_where('student', array('student_id' => $student_id))->row();
            
            if (!$student) {
                $this->session->set_flashdata('error_message', get_phrase('student_not_found'));
                redirect(base_url() . 'student/dashboard', 'refresh');
            }
            
            $page_data['student_id'] = $student_id;
            $page_data['class_id'] = $student->class_id;
            $page_data['section_id'] = $student->section_id;
            $page_data['page_name'] = 'timetable';
            $page_data['page_title'] = get_phrase('Class Timetable');
            $this->load->view('backend/index', $page_data);
        }

        // Calendar Timetable View for Students
        function calendar_timetable() {
            if ($this->session->userdata('student_login') != 1) {
                redirect(base_url(), 'refresh');
            }
            
            $student_id = $this->session->userdata('student_id');
            $student = $this->db->get_where('student', array('student_id' => $student_id))->row();
            
            if (!$student) {
                $this->session->set_flashdata('error_message', get_phrase('student_not_found'));
                redirect(base_url() . 'student/dashboard', 'refresh');
            }
            
            // Get current student's class and section as default
            $page_data['default_class_id'] = $student->class_id;
            $page_data['default_section_id'] = $student->section_id;
            
            $page_data['page_name'] = 'calendar_timetable';
            $page_data['page_title'] = get_phrase('Class Calendar Timetable');
            $this->load->view('backend/index', $page_data);
        }

        /* Get class timetable data for AJAX request - usable by both students and parents */
        function get_class_timetable_data() {
            // Check if this is a valid AJAX request
            if (!$this->input->is_ajax_request()) {
                echo json_encode(array());
                return;
            }
            
            $class_id = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');
            
            if (empty($class_id) || empty($section_id)) {
                echo json_encode(array());
                return;
            }
            
            // Get calendar timetable data
            $this->db->select('ct.*, s.name as subject_name, t.name as teacher_name, c.name as class_name, sec.name as section_name');
            $this->db->from('calendar_timetable as ct');
            $this->db->join('subject as s', 's.subject_id = ct.subject_id', 'left');
            $this->db->join('teacher as t', 't.teacher_id = ct.teacher_id', 'left');
            $this->db->join('class as c', 'c.class_id = ct.class_id', 'left');
            $this->db->join('section as sec', 'sec.section_id = ct.section_id', 'left');
            $this->db->where('ct.class_id', $class_id);
            $this->db->where('ct.section_id', $section_id);
            // It's a good practice to order the results for consistency
            $this->db->order_by('ct.day_of_week', 'ASC');
            $this->db->order_by('ct.time_slot_start', 'ASC');
            $query = $this->db->get();
            $result = $query->result_array();
            
            echo json_encode($result);
        }

        // Print class timetable
        function print_timetable() {
            if ($this->session->userdata('student_login') != 1)
                redirect(base_url(), 'refresh');
            
            $student_id = $this->session->userdata('student_id');
            $student_details = $this->db->get_where('student', array('student_id' => $student_id))->row();
            
            if (!$student_details) {
                $this->session->set_flashdata('error_message', get_phrase('Student information not found'));
                redirect(base_url() . 'student/calendar_timetable', 'refresh');
            }
            
            $class_id = $student_details->class_id;
            $section_id = $student_details->section_id;
            
            $class_details = $this->db->get_where('class', array('class_id' => $class_id))->row();
            $section_details = $this->db->get_where('section', array('section_id' => $section_id))->row();
            
            if (!$class_details || !$section_details) {
                $this->session->set_flashdata('error_message', get_phrase('Class or section not found'));
                redirect(base_url() . 'student/calendar_timetable', 'refresh');
            }
            
            // Get timetable data from calendar_timetable
            $this->db->select('ct.*, s.name as subject_name, t.name as teacher_name, c.name as class_name, sec.name as section_name');
            $this->db->from('calendar_timetable as ct');
            $this->db->join('subject as s', 's.subject_id = ct.subject_id', 'left');
            $this->db->join('teacher as t', 't.teacher_id = ct.teacher_id', 'left');
            $this->db->join('class as c', 'c.class_id = ct.class_id', 'left');
            $this->db->join('section as sec', 'sec.section_id = ct.section_id', 'left');
            $this->db->where('ct.class_id', $class_id);
            $this->db->where('ct.section_id', $section_id);
            $this->db->order_by('ct.day_of_week', 'ASC');
            $this->db->order_by('ct.time_slot_start', 'ASC');
            
            $data['timetable_data'] = $this->db->get()->result_array();
            $data['class_name'] = $class_details->name;
            $data['section_name'] = $section_details->name;
            $data['page_title'] = get_phrase('Class Timetable');
            
            // Load the view directly without using index.php
            $this->load->view('backend/timetable_print_view', $data);
        }

}