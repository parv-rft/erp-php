<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Login_model extends CI_Model { 
	
	function __construct()
    {
        parent::__construct();
    }

    function loginFunctionForAllUsers (){
        
        $email = html_escape($this->input->post('email'));			
        $password = $this->input->post('password');	
        $calculated_hash = sha1($password);
        $credential = array('email' => $email, 'password' => $calculated_hash);	

        // <<< --- REMOVE MORE DEBUGGING START --- >>>
        // echo "<pre>";
        // error_log("--- Login Attempt ---"); // Log to Docker logs as well
        // error_log("Email: " . $email);
        // error_log("Password: " . $password);
        // error_log("Calculated Hash: " . $calculated_hash);
        // echo "Email Submitted: " . $email . "\n";
        // echo "Password Submitted: " . $password . "\n";
        // echo "Calculated SHA1 Hash: " . $calculated_hash . "\n";

        // // Try fetching user by email only
        // $user_row_by_email = $this->db->get_where('admin', array('email' => $email))->row();
        // if ($user_row_by_email) {
        //     $db_stored_hash = $user_row_by_email->password;
        //     error_log("DB User Found By Email.");
        //     error_log("DB Stored Hash: " . $db_stored_hash);
        //     echo "DB User Found By Email.\n";
        //     echo "DB Stored Hash: " . $db_stored_hash . "\n";
            
        //     $hashes_match = ($calculated_hash === $db_stored_hash);
        //     error_log("Hash Comparison Result (===): " . ($hashes_match ? 'TRUE' : 'FALSE'));
        //     echo "Hash Comparison (Calculated === DB Stored): " . ($hashes_match ? 'TRUE' : 'FALSE') . "\n";

        //     // Also try loose comparison just in case
        //     $hashes_match_loose = ($calculated_hash == $db_stored_hash);
        //     error_log("Hash Comparison Result (==): " . ($hashes_match_loose ? 'TRUE' : 'FALSE'));
        //     echo "Hash Comparison (Calculated == DB Stored): " . ($hashes_match_loose ? 'TRUE' : 'FALSE') . "\n";

        // } else {
        //     error_log("DB User NOT Found By Email.");
        //     echo "DB User NOT Found By Email.\n";
        // }

        // // Run the actual query used for login
        // $query = $this->db->get_where('admin', $credential);
        // $last_query = $this->db->last_query();
        // error_log("Login Query SQL: " . $last_query);
        // error_log("Login Query Result Rows: " . $query->num_rows());
        // echo "Login Query SQL: " . $last_query . "\n";
        // echo "Login Query Result Rows: " . $query->num_rows() . "\n";
        // echo "</pre>";
        // <<< --- REMOVE MORE DEBUGGING END --- >>>

        // Use the original query line
        $query = $this->db->get_where('admin', $credential);

        if ($query->num_rows() > 0) {
            $row = $query->row();
  
            $this->session->set_userdata('login_type', 'admin');
            $this->session->set_userdata('admin_login', '1');
            $this->session->set_userdata('admin_id', $row->admin_id);
            $this->session->set_userdata('login_user_id', $row->admin_id);
            $this->session->set_userdata('name', $row->name);

            return  $this->db->set('login_status', ('1'))
                    ->where('admin_id', $this->session->userdata('admin_id'))
                    ->update('admin');
        }

        $query = $this->db->get_where('hrm', $credential);
        if ($query->num_rows() > 0) {
            $row = $query->row();
  
            $this->session->set_userdata('login_type', 'hrm');
            $this->session->set_userdata('hrm_login', '1');
            $this->session->set_userdata('hrm_id', $row->hrm_id);
            $this->session->set_userdata('login_user_id', $row->hrm_id);
            $this->session->set_userdata('name', $row->name);

            return  $this->db->set('login_status', ('1'))
                    ->where('hrm_id', $this->session->userdata('hrm_id'))
                    ->update('hrm');
        }

        $query = $this->db->get_where('hostel', $credential);
        if ($query->num_rows() > 0) {
            $row = $query->row();
  
            $this->session->set_userdata('login_type', 'hostel');
            $this->session->set_userdata('hostel_login', '1');
            $this->session->set_userdata('hostel_id', $row->hostel_id);
            $this->session->set_userdata('login_user_id', $row->hostel_id);
            $this->session->set_userdata('name', $row->name);

            return  $this->db->set('login_status', ('1'))
                    ->where('hostel_id', $this->session->userdata('hostel_id'))
                    ->update('hostel');
        }

        $query = $this->db->get_where('accountant', $credential);
        if ($query->num_rows() > 0) {
            $row = $query->row();
  
            $this->session->set_userdata('login_type', 'accountant');
            $this->session->set_userdata('accountant_login', '1');
            $this->session->set_userdata('accountant_id', $row->accountant_id);
            $this->session->set_userdata('login_user_id', $row->accountant_id);
            $this->session->set_userdata('name', $row->name);

            return  $this->db->set('login_status', ('1'))
                    ->where('accountant_id', $this->session->userdata('accountant_id'))
                    ->update('accountant');
        }

        $query = $this->db->get_where('librarian', $credential);
        if ($query->num_rows() > 0) {
            $row = $query->row();
  
            $this->session->set_userdata('login_type', 'librarian');
            $this->session->set_userdata('librarian_login', '1');
            $this->session->set_userdata('librarian_id', $row->librarian_id);
            $this->session->set_userdata('login_user_id', $row->librarian_id);
            $this->session->set_userdata('name', $row->name);

            return  $this->db->set('login_status', ('1'))
                    ->where('librarian_id', $this->session->userdata('librarian_id'))
                    ->update('librarian');
        }

        $query = $this->db->get_where('parent', $credential);
        if ($query->num_rows() > 0) {
            $row = $query->row();
  
            $this->session->set_userdata('login_type', 'parent');
            $this->session->set_userdata('parent_login', '1');
            $this->session->set_userdata('parent_id', $row->parent_id);
            $this->session->set_userdata('login_user_id', $row->parent_id);
            $this->session->set_userdata('name', $row->name);

            return  $this->db->set('login_status', ('1'))
                    ->where('parent_id', $this->session->userdata('parent_id'))
                    ->update('parent');
        }

        $query = $this->db->get_where('student', $credential);
        if ($query->num_rows() > 0) {
            $row = $query->row();
  
            $this->session->set_userdata('login_type', 'student');
            $this->session->set_userdata('student_login', '1');
            $this->session->set_userdata('student_id', $row->student_id);
            $this->session->set_userdata('login_user_id', $row->student_id);
            $this->session->set_userdata('name', $row->name);

            return  $this->db->set('login_status', ('1'))
                    ->where('student_id', $this->session->userdata('student_id'))
                    ->update('student');
        }

        $query = $this->db->get_where('teacher', $credential);
        if ($query->num_rows() > 0) {
            $row = $query->row();
  
            $this->session->set_userdata('login_type', 'teacher');
            $this->session->set_userdata('teacher_login', '1');
            $this->session->set_userdata('teacher_id', $row->teacher_id);
            $this->session->set_userdata('login_user_id', $row->teacher_id);
            $this->session->set_userdata('name', $row->name);

            return  $this->db->set('login_status', ('1'))
                    ->where('teacher_id', $this->session->userdata('teacher_id'))
                    ->update('teacher');
        }

    }


    function logout_model_for_admin(){
        return  $this->db->set('login_status', ('0'))
                    ->where('admin_id', $this->session->userdata('admin_id'))
                    ->update('admin');
    }

    function logout_model_for_hrm(){
        return  $this->db->set('login_status', ('0'))
                    ->where('hrm_id', $this->session->userdata('hrm_id'))
                    ->update('hrm');
    }

    function logout_model_for_hostel(){
        return  $this->db->set('login_status', ('0'))
                    ->where('hostel_id', $this->session->userdata('hostel_id'))
                    ->update('hostel');
    }

    function logout_model_for_accountant(){
        return  $this->db->set('login_status', ('0'))
                    ->where('accountant_id', $this->session->userdata('accountant_id'))
                    ->update('accountant');
    }

    function logout_model_for_librarian(){
        return  $this->db->set('login_status', ('0'))
                    ->where('librarian_id', $this->session->userdata('librarian_id'))
                    ->update('librarian');
    }

    function logout_model_for_parent(){
        return  $this->db->set('login_status', ('0'))
                    ->where('parent_id', $this->session->userdata('parent_id'))
                    ->update('parent');
    }

    function logout_model_for_teacher(){
        return  $this->db->set('login_status', ('0'))
                    ->where('teacher_id', $this->session->userdata('teacher_id'))
                    ->update('teacher');
    }

    function logout_model_for_student(){
        return  $this->db->set('login_status', ('0'))
                    ->where('student_id', $this->session->userdata('student_id'))
                    ->update('student');
    }
	
	
	


	
	
}
