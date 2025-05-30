<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Parents extends CI_Controller { 

    function __construct() {
        parent::__construct();
        		$this->load->database();                                //Load Databse Class
                $this->load->library('session');					    //Load library for session
  
    }

     /*parent dashboard code to redirect to parent page if successfull login** */
     function dashboard() {
        if ($this->session->userdata('parent_login') != 1) redirect(base_url(), 'refresh');

        // Get the student_id of the child associated with the logged-in parent
        $student_id = $this->session->userdata('student_id');
        $student = null;
        $class_id = null;

        if ($student_id) {
            $student_query = $this->db->get_where('student', array('student_id' => $student_id));
            if ($student_query->num_rows() > 0) {
                $student = $student_query->row();
                $class_id = $student->class_id;
            } else {
                // Handle case where student_id from session does not exist in student table
                log_message('error', 'Parent dashboard: Student ID ' . $student_id . ' from session not found in student table.');
                $this->session->set_flashdata('error_message', get_phrase('Student record not found. Please contact support.'));
                // Optionally redirect to login or an error page
                // redirect(base_url('login'), 'refresh'); 
                // return;
            }
        } else {
            // Handle case where student_id is not in session (should not happen if login was successful)
            log_message('error', 'Parent dashboard: student_id not found in session.');
            $this->session->set_flashdata('error_message', get_phrase('Session error. Please login again.'));
            redirect(base_url('login'), 'refresh');
            return;
        }

        // Initialize arrays for data
        $attendance_data = array();
        $all_teachers = array();
        $recent_payments = array();
        $start_date = null;
        $today_date = null;

        if ($student_id) {
            // Get today and the date 2 days prior
            $today_dt = new DateTime();
            $start_dt = new DateTime();
            $start_dt->modify('-2 days');

            $today_date = $today_dt->format('Y-m-d');
            $start_date = $start_dt->format('Y-m-d');

            // Fetch student's attendance for the last 3 days (inclusive)
            $this->db->select('date, status'); 
            $this->db->where('student_id', $student_id);
            $this->db->where('date >=', $start_date);
            $this->db->where('date <=', $today_date);
            $this->db->order_by('date', 'DESC');
            $attendance_query = $this->db->get('attendance');
            
            if ($attendance_query !== false && $attendance_query->num_rows() > 0) {
                foreach ($attendance_query->result() as $row) {
                    if (isset($row->date) && isset($row->status)) {
                        $attendance_data[date('Y-m-d', strtotime($row->date))] = $row->status; 
                    } else {
                        log_message('error', 'Attendance record missing date or status property for student_id: ' . $student_id);
                    }
                }
            } elseif ($attendance_query === false) {
                log_message('error', 'Database error fetching attendance for parent dashboard: ' . $this->db->error()['message']);
            }

            // Fetch all teachers (or just class teachers if needed)
            // Using the same logic as student dashboard for simplicity, fetches all teachers
            $this->db->select('teacher_id, name, role, email, phone, sex');
            $all_teachers = $this->db->get('teacher')->result_array();

            // Fetch last 4 payment records for the student
            $this->db->select('title, description, method, amount, timestamp');
            $this->db->where('student_id', $student_id);
            $this->db->order_by('timestamp', 'DESC');
            $this->db->limit(4);
            $recent_payments = $this->db->get('payment')->result_array();
        }

       	$page_data['page_name'] = 'dashboard';
        $page_data['page_title'] = get_phrase('parent Dashboard');
        $page_data['student_info'] = $student; // Pass student info
        $page_data['attendance_data'] = $attendance_data; // Pass attendance data
        $page_data['start_date'] = $start_date; // Pass start date for attendance loop
        $page_data['today_date'] = $today_date; // Pass end date for attendance loop
        $page_data['all_teachers'] = $all_teachers; // Pass teachers data
        $page_data['recent_payments'] = $recent_payments; // Pass recent payments

        $this->load->view('backend/index', $page_data);
    }
	/******************* / parent dashboard code to redirect to parent page if successfull login** */

    function manage_profile($param1 = null, $param2 = null, $param3 = null){
        if ($this->session->userdata('parent_login') != 1) redirect(base_url(), 'refresh');
        
        $student_id = $this->session->userdata('student_id');
        $logged_in_parent_type = $this->session->userdata('logged_in_parent_type'); // 'father' or 'mother'

        if (!$student_id || !$logged_in_parent_type) {
            $this->session->set_flashdata('error_message', get_phrase('Session error. Please login again.'));
            redirect(base_url('login'), 'refresh');
            return;
        }

        if ($param1 == 'update') {
            $data_to_update = array();
            if ($logged_in_parent_type == 'father') {
                $data_to_update['father_name'] = $this->input->post('name');
                $data_to_update['father_email'] = $this->input->post('email');
                // Add other father-specific fields if they are part of the profile form
                // e.g., $data_to_update['father_phone'] = $this->input->post('phone');
                // e.g., $data_to_update['father_occupation'] = $this->input->post('occupation');
            } elseif ($logged_in_parent_type == 'mother') {
                $data_to_update['mother_name'] = $this->input->post('name');
                $data_to_update['mother_email'] = $this->input->post('email');
                // Add other mother-specific fields if they are part of the profile form
                // e.g., $data_to_update['mother_phone'] = $this->input->post('phone');
                // e.g., $data_to_update['mother_occupation'] = $this->input->post('occupation');
            }
    
            if (!empty($data_to_update)) {
                $this->db->where('student_id', $student_id);
                $this->db->update('student', $data_to_update);
            }
            
            // Handle photo upload
            if (isset($_FILES['userfile']['name']) && $_FILES['userfile']['name'] != '') {
                $photo_field_name = ($logged_in_parent_type == 'father') ? 'father_photo' : 'mother_photo';
                $upload_path = 'uploads/parent_image/';
                $file_name = $student_id . '_' . $logged_in_parent_type . '.jpg';
                
                // Create directory if it doesn't exist
                if (!is_dir($upload_path)) {
                    mkdir($upload_path, 0777, true);
                }

                if (move_uploaded_file($_FILES['userfile']['tmp_name'], $upload_path . $file_name)) {
                    $this->db->where('student_id', $student_id);
                    $this->db->update('student', array($photo_field_name => $file_name));
                } else {
                    $this->session->set_flashdata('error_message', get_phrase('Photo upload failed.'));
                }
            }

            $this->session->set_flashdata('flash_message', get_phrase('Info Updated'));
            redirect(base_url() . 'parents/manage_profile', 'refresh');
        }
    
        if ($param1 == 'change_password') {
            $new_password = $this->input->post('new_password');
            $confirm_new_password = $this->input->post('confirm_new_password');
    
            if (!empty($new_password) && $new_password == $confirm_new_password) {
                $hashed_password = sha1($new_password);
                $password_field_name = ($logged_in_parent_type == 'father') ? 'father_password_hash' : 'mother_password_hash';
                
                $this->db->where('student_id', $student_id);
                $this->db->update('student', array($password_field_name => $hashed_password));
                $this->session->set_flashdata('flash_message', get_phrase('Password Changed'));
            } else if (empty($new_password)) {
                 $this->session->set_flashdata('error_message', get_phrase('Password cannot be empty.'));
            } else {
                $this->session->set_flashdata('error_message', get_phrase('Type the same password'));
            }
            redirect(base_url() . 'parents/manage_profile', 'refresh');
        }
    
        // Fetch student record to get parent details for the profile view
        $student_details = $this->db->get_where('student', array('student_id' => $student_id))->row_array();
        $profile_data = array();

        if ($student_details) {
            if ($logged_in_parent_type == 'father') {
                $profile_data['name'] = $student_details['father_name'];
                $profile_data['email'] = $student_details['father_email'];
                $profile_data['photo'] = $student_details['father_photo'];
                // Add other father fields as needed by the view, e.g., phone, occupation
                $profile_data['phone'] = isset($student_details['father_phone']) ? $student_details['father_phone'] : '';
                $profile_data['occupation'] = isset($student_details['father_occupation']) ? $student_details['father_occupation'] : '';
                $profile_data['address'] = isset($student_details['father_address']) ? $student_details['father_address'] : '';
            } elseif ($logged_in_parent_type == 'mother') {
                $profile_data['name'] = $student_details['mother_name'];
                $profile_data['email'] = $student_details['mother_email'];
                $profile_data['photo'] = $student_details['mother_photo'];
                // Add other mother fields as needed by the view
                $profile_data['phone'] = isset($student_details['mother_phone']) ? $student_details['mother_phone'] : '';
                $profile_data['occupation'] = isset($student_details['mother_occupation']) ? $student_details['mother_occupation'] : '';
                $profile_data['address'] = isset($student_details['mother_address']) ? $student_details['mother_address'] : '';
            }
        }

        $page_data['page_name']     = 'manage_profile';
        $page_data['page_title']    = get_phrase('Manage Profile');
        $page_data['edit_profile']  = array($profile_data); // Pass as an array of one item to match original structure if view expects it
        $this->load->view('backend/index', $page_data);
    }


    function subject (){
        if ($this->session->userdata('parent_login') != 1) redirect(base_url(), 'refresh');
        $student_id = $this->session->userdata('student_id');
        if (!$student_id) {
            $this->session->set_flashdata('error_message', get_phrase('Session error or student not found.'));
            redirect(base_url('login'), 'refresh');
            return;
        }

        $student_profile = $this->db->get_where('student', array('student_id' => $student_id))->row();
        if (!$student_profile) {
            $this->session->set_flashdata('error_message', get_phrase('Student record not found.'));
            redirect(base_url('parents/dashboard'), 'refresh');
            return;
        }
        $select_student_class_id = $student_profile->class_id;

        $page_data['page_name']     = 'subject';
        $page_data['page_title']    = get_phrase('Class Subjects');
        $page_data['select_subject']  = $this->db->get_where('subject', array('class_id' => $select_student_class_id))->result_array();
        $this->load->view('backend/index', $page_data);
    }

    function teacher (){
        if ($this->session->userdata('parent_login') != 1) redirect(base_url(), 'refresh');
        $student_id = $this->session->userdata('student_id');
        if (!$student_id) {
            $this->session->set_flashdata('error_message', get_phrase('Session error or student not found.'));
            redirect(base_url('login'), 'refresh');
            return;
        }

        $student_profile = $this->db->get_where('student', array('student_id' => $student_id))->row();
        if (!$student_profile) {
            $this->session->set_flashdata('error_message', get_phrase('Student record not found.'));
            redirect(base_url('parents/dashboard'), 'refresh');
            return;
        }
        $select_student_class_id = $student_profile->class_id;

        // Note: The original logic to get a single teacher_id from subject might be too simplistic.
        // This assumes one teacher per subject, or it might fetch the teacher of the first subject found for the class.
        // Consider if the view expects all teachers for the class or a specific one.
        // For now, keeping original logic but it might need review based on application requirements.
        $subject_entry = $this->db->get_where('subject', array('class_id' => $select_student_class_id))->row();
        $return_teacher_id = $subject_entry ? $subject_entry->teacher_id : null;

        $page_data['page_name']     = 'teacher';
        $page_data['page_title']    = get_phrase('Class Teachers');
        if($return_teacher_id){
            $page_data['select_teacher']  = $this->db->get_where('teacher', array('teacher_id' => $return_teacher_id))->result_array();
        } else {
            $page_data['select_teacher'] = array(); // No teacher found or no subjects for class
        }
        $this->load->view('backend/index', $page_data);
    }

    function class_mate() {
        if ($this->session->userdata('parent_login') != 1) redirect(base_url(), 'refresh');

        $student_id = $this->session->userdata('student_id');
        if (!$student_id) {
            $this->session->set_flashdata('error_message', get_phrase('Session error or student not found.'));
            redirect(base_url('login'), 'refresh');
            return;
        }
        
        $student = $this->db->get_where('student', array('student_id' => $student_id))->row();

        if (!$student) {
             $this->session->set_flashdata('error_message', get_phrase('No student associated with this parent account.'));
             redirect(base_url() . 'parents/dashboard', 'refresh');
             return; 
        }

        $class_id = $student->class_id;

        $this->db->select('student_id, name, phone, email, sex'); 
        $this->db->where('class_id', $class_id);
        $this->db->where('student_id !=', $student_id); 
        $this->db->order_by('name', 'asc'); 
        $classmates = $this->db->get('student')->result_array();

        $page_data['classmates']    = $classmates; 
        $page_data['page_name']     = 'class_mate';
        $page_data['page_title']    = get_phrase('Classmates'); 
        $this->load->view('backend/index', $page_data);
    }

    function class_routine($param1 = null, $param2 = null, $param3 = null) {
        if ($this->session->userdata('parent_login') != 1) {
            redirect(base_url(), 'refresh');
        }

        $parent_email = $this->session->userdata('parent_email'); // Assuming parent_email is set at login
        $logged_in_parent_type = $this->session->userdata('logged_in_parent_type'); // 'father' or 'mother'

        if (!$parent_email || !$logged_in_parent_type) {
            $this->session->set_flashdata('error_message', get_phrase('Session error. Please login again.'));
            redirect(base_url('login'), 'refresh');
            return;
        }

        $children_list = array();
        $this->db->group_start();
        $this->db->where('father_email', $parent_email);
        $this->db->or_where('mother_email', $parent_email);
        $this->db->group_end();
        $query_children = $this->db->get('student');

        if ($query_children->num_rows() > 0) {
            foreach ($query_children->result_array() as $child_row) {
                $class_id = $child_row['class_id'];
                $section_id = $child_row['section_id'];
                $class_name = 'Unknown Class';
                $section_name = 'Unknown Section';

                if ($class_id) {
                    $class_data = $this->db->get_where('class', array('class_id' => $class_id))->row();
                    if ($class_data) {
                        $class_name = $class_data->name;
                    }

                    // Ensure section_id is valid, if not, try to assign the first available one for the class
                    if (empty($section_id) || $section_id == 0) {
                        $first_section = $this->db->order_by('name', 'ASC')->get_where('section', array('class_id' => $class_id))->row();
                        if ($first_section) {
                            $section_id = $first_section->section_id;
                            // Update student's section_id in DB
                            $this->db->where('student_id', $child_row['student_id']);
                            $this->db->update('student', array('section_id' => $section_id));
                            $child_row['section_id'] = $section_id; // Update for current use
                        }
                    }
                }
                
                if ($section_id) {
                     $section_data = $this->db->get_where('section', array('section_id' => $section_id))->row();
                     if ($section_data) {
                         $section_name = $section_data->name;
                     }
                }


                $children_list[] = array(
                    'student_id' => $child_row['student_id'],
                    'name' => $child_row['name'],
                    'class_id' => $class_id,
                    'section_id' => $section_id, // Use potentially updated section_id
                    'class_name' => $class_name,
                    'section_name' => $section_name
                );
            }
        }

        $page_data['children_list'] = $children_list;
        $page_data['page_name'] = 'class_routine';
        $page_data['page_title'] = get_phrase('class_routine');
        $this->load->view('backend/index', $page_data);
    }

    function invoice($param1 = null, $param2 = null, $param3 = null){
        if ($this->session->userdata('parent_login') != 1) redirect(base_url(), 'refresh');
        $student_id = $this->session->userdata('student_id');
        if (!$student_id) {
            $this->session->set_flashdata('error_message', get_phrase('Session error or student not found.'));
            redirect(base_url('login'), 'refresh');
            return;
        }

        if($param1 == 'make_payment'){
            $invoice_id = $this->input->post('invoice_id');
            // Ensure the invoice belongs to the logged-in parent's child
            $invoice_check = $this->db->get_where('invoice', array('invoice_id' => $invoice_id, 'student_id' => $student_id))->row();
            if(!$invoice_check){
                $this->session->set_flashdata('error_message', get_phrase('Invalid invoice or access denied.'));
                redirect(base_url() . 'parents/invoice', 'refresh');
                return;
            }

            $payment_email = $this->db->get_where('settings', array('type' => 'paypal_email'))->row();
            $select_invoice = $this->db->get_where('invoice', array('invoice_id' => $invoice_id))->row();

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
        }

        $page_data['page_name']     = 'invoice';
        $page_data['page_title']    = get_phrase('Payment Invoice');
        $page_data['select_invoice'] = $this->db->get_where('invoice', array('student_id' => $student_id))->result_array();
        $this->load->view('backend/index', $page_data);
    }

    function payment_history() {
        if ($this->session->userdata('parent_login') != 1) redirect(base_url(), 'refresh');
        $student_id = $this->session->userdata('student_id');
        if (!$student_id) {
            $this->session->set_flashdata('error_message', get_phrase('Session error or student not found.'));
            redirect(base_url('login'), 'refresh');
            return;
        }

        $page_data['page_name']  = 'payment_history';
        $page_data['page_title'] = get_phrase('Payment History');
        // Fetch payments for the specific student
        $this->db->where('student_id', $student_id);
        $this->db->order_by('timestamp', 'desc');
        $page_data['invoice_details'] = $this->db->get('payment')->result_array();
        $this->load->view('backend/index', $page_data);
    }

    function assignment() {
        // ... existing code ...
    }

    /* Get class timetable data for AJAX request */
    function get_class_timetable_data() {
        if ($this->session->userdata('parent_login') != 1) {
            echo json_encode(array('error' => 'Not logged in'));
            return;
        }

        $student_id = $this->input->get('student_id');
        $class_id = $this->input->get('class_id');
        $section_id = $this->input->get('section_id');

        if (!$student_id || !$class_id || !$section_id) {
            echo json_encode(array('error' => 'Missing parameters: student_id, class_id, or section_id'));
            return;
        }
        
        // First, verify this student belongs to the logged-in parent
        $parent_email = $this->session->userdata('parent_email');
        $this->db->group_start();
        $this->db->where('father_email', $parent_email);
        $this->db->or_where('mother_email', $parent_email);
        $this->db->group_end();
        $this->db->where('student_id', $student_id);
        $student_check = $this->db->get('student');

        if($student_check->num_rows() == 0){
            echo json_encode(array('error' => 'Student not associated with this parent.'));
            return;
        }

        // Fetch timetable data from the 'class_routine' table
        $this->db->where('class_id', $class_id);
        $this->db->where('section_id', $section_id);
        $this->db->order_by('time_start', 'ASC'); // Order by time
        $timetable_query = $this->db->get('class_routine');
        
        $timetable = array();
        if ($timetable_query->num_rows() > 0) {
            foreach ($timetable_query->result_array() as $row) {
                // Optional: Fetch subject name and teacher name if IDs are stored
                if (isset($row['subject_id'])) {
                    $subject_info = $this->db->get_where('subject', array('subject_id' => $row['subject_id']))->row();
                    $row['subject_name'] = $subject_info ? $subject_info->name : 'N/A';
                }
                if (isset($row['teacher_id'])) {
                     $teacher_info = $this->db->get_where('teacher', array('teacher_id' => $row['teacher_id']))->row();
                     $row['teacher_name'] = $teacher_info ? $teacher_info->name : 'N/A';
                }
                $timetable[] = $row;
            }
        }

        // This structure depends on how your JavaScript expects the data.
        // The view seems to build rows by time slots and then iterate through days.
        // You might need to re-structure this $timetable array into a format
        // that's easy for the JavaScript to render (e.g., indexed by time_slot then by day_of_week)
        
        echo json_encode(array('timetable' => $timetable, 'class_id' => $class_id, 'section_id' => $section_id));
    }

    /* Fetch all classes and sections */
    function get_all_classes_sections() 
    {
        if ($this->session->userdata('parent_login') != 1) {
            echo json_encode(array('status' => 'error', 'message' => 'Access denied'));
            return;
        }
        
        try {
            // Get all classes
            $this->db->select('*');
            $this->db->order_by('name', 'asc');
            $classes = $this->db->get('class')->result_array();
            
            // Get all sections
            $this->db->select('*');
            $this->db->order_by('name', 'asc');
            $sections = $this->db->get('section')->result_array();
            
            // Organize sections by class_id for easier frontend handling
            $sections_by_class = array();
            foreach ($sections as $section) {
                if (!isset($sections_by_class[$section['class_id']])) {
                    $sections_by_class[$section['class_id']] = array();
                }
                $sections_by_class[$section['class_id']][] = $section;
            }
            
            $result = array(
                'status' => 'success',
                'classes' => $classes,
                'sections' => $sections,
                'sections_by_class' => $sections_by_class
            );
            
            echo json_encode($result);
        } catch (Exception $e) {
            log_message('error', 'Error in get_all_classes_sections: ' . $e->getMessage());
            echo json_encode(array('status' => 'error', 'message' => 'Database error'));
        }
    }

}