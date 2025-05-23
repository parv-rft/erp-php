<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Login_model extends CI_Model { 
	
	function __construct()
    {
        parent::__construct();
    }

    function loginFunctionForAllUsers ($user_type = null){
        error_log("Login_model: loginFunctionForAllUsers called. Requested user_type: " . ($user_type ? $user_type : 'none'));
        $email = html_escape($this->input->post('email'));			
        $password = $this->input->post('password');	
        error_log("Login_model: Email: " . $email);
        $calculated_hash = sha1($password);
        $credential = array('email' => $email, 'password' => $calculated_hash);	
        error_log("Login_model: Credentials for query: Email=" . $email . ", Hash=" . $calculated_hash);

        // If a specific user type is requested, try that first
        if ($user_type) {
            switch ($user_type) {
                case 'admin':
                    error_log("Login_model: Checking for user_type 'admin'");
                    $query = $this->db->get_where('admin', $credential);
                    error_log("Login_model: admin query executed. Num rows: " . ($query ? $query->num_rows() : 'Query failed or no result'));
                    if ($query && $query->num_rows() > 0) {
                        error_log("Login_model: Admin found. Calling setAdminSession.");
                        $this->setAdminSession($query->row());
                        return true;
                    }
                    error_log("Login_model: Admin not found or query failed for 'admin' type.");
                    break;
                case 'teacher':
                    error_log("Login_model: Checking for user_type 'teacher'");
                    $query = $this->db->get_where('teacher', $credential);
                    error_log("Login_model: teacher query executed. Num rows: " . ($query ? $query->num_rows() : 'Query failed or no result'));
                    if ($query && $query->num_rows() > 0) {
                        error_log("Login_model: Teacher found. Calling setTeacherSession.");
                        $this->setTeacherSession($query->row());
                        return true;
                    }
                    error_log("Login_model: Teacher not found or query failed for 'teacher' type.");
                    break;
                case 'student':
                    error_log("Login_model: Checking for user_type 'student'"); 
                    $query = $this->db->get_where('student', $credential);
                    error_log("Login_model: student query executed. Num rows: " . ($query ? $query->num_rows() : 'Query failed or no result')); 
                    if ($query && $query->num_rows() > 0) {
                        error_log("Login_model: Student found. Calling setStudentSession."); 
                        $this->setStudentSession($query->row());
                        return true;
                    }
                    error_log("Login_model: Student not found or query failed for 'student' type."); 
                    break;
                case 'parent':
                    error_log("Login_model: Attempting parent login via student table.");
                    $email = isset($credential['email']) ? $credential['email'] : null;
                    $password = isset($credential['password']) ? $credential['password'] : null;

                    if (!$email || !$password) {
                        error_log("Login_model: Parent login attempt with missing email or password.");
                        break; // Skip to general check or fail
                    }

                    // Try to login as father
                    $this->db->where('father_email', $email);
                    $this->db->where('father_password_hash', $password);
                    $query_father = $this->db->get('student');

                    if ($query_father && $query_father->num_rows() > 0) {
                        error_log("Login_model: Father found in student table. Calling setParentSession.");
                        $this->setParentSession($query_father->row(), 'father');
                        return true;
                    }

                    // Try to login as mother
                    $this->db->where('mother_email', $email);
                    $this->db->where('mother_password_hash', $password);
                    $query_mother = $this->db->get('student');

                    if ($query_mother && $query_mother->num_rows() > 0) {
                        error_log("Login_model: Mother found in student table. Calling setParentSession.");
                        $this->setParentSession($query_mother->row(), 'mother');
                        return true;
                    }
                    error_log("Login_model: Parent (father/mother) not found in student table with provided credentials.");
                    break;
            }
            
            error_log("Login_model: Credentials didn't match for the requested user_type: " . $user_type);
            return false;
        }

        // If no specific user type or the specific type failed, try all types
        error_log("Login_model: No specific user_type provided or initial check failed. Trying all types.");
        $query = $this->db->get_where('admin', $credential);
        error_log("Login_model: admin query (general check) executed. Num rows: " . ($query ? $query->num_rows() : 'Query failed or no result'));
        if ($query && $query->num_rows() > 0) {
            error_log("Login_model: Admin found (general check). Calling setAdminSession.");
            $this->setAdminSession($query->row());
            return true;
        }

        $query = $this->db->get_where('hrm', $credential);
        error_log("Login_model: hrm query (general check) executed. Num rows: " . ($query ? $query->num_rows() : 'Query failed or no result'));
        if ($query && $query->num_rows() > 0) {
            $row = $query->row();
            error_log("Login_model: HRM found (general check). Setting session and attempting DB update. HRM ID: " . (isset($row->hrm_id) ? $row->hrm_id : 'N/A'));
            $this->session->set_userdata('login_type', 'hrm');
            $this->session->set_userdata('hrm_login', '1');
            $this->session->set_userdata('hrm_id', $row->hrm_id);
            $this->session->set_userdata('login_user_id', $row->hrm_id);
            $this->session->set_userdata('name', $row->name);
            try {
                $update_result = $this->db->set('login_status', ('1'))
                        ->where('hrm_id', $row->hrm_id)
                        ->update('hrm');
                error_log("Login_model: DB update for hrm login_status result: " . ($update_result ? 'Success' : 'Failed'));
                return $update_result; 
            } catch (Throwable $t) {
                error_log("Login_model: CRITICAL ERROR during hrm login_status update: " . $t->getMessage());
                return true; // Session set, but DB update failed, allow login
            }
        }

        $query = $this->db->get_where('hostel', $credential);
        error_log("Login_model: hostel query (general check) executed. Num rows: " . ($query ? $query->num_rows() : 'Query failed or no result'));
        if ($query && $query->num_rows() > 0) {
            $row = $query->row();
            error_log("Login_model: Hostel found (general check). Setting session and attempting DB update. Hostel ID: " . (isset($row->hostel_id) ? $row->hostel_id : 'N/A'));
            $this->session->set_userdata('login_type', 'hostel');
            $this->session->set_userdata('hostel_login', '1');
            $this->session->set_userdata('hostel_id', $row->hostel_id);
            $this->session->set_userdata('login_user_id', $row->hostel_id);
            $this->session->set_userdata('name', $row->name);
            try {
                $update_result = $this->db->set('login_status', ('1'))
                        ->where('hostel_id', $row->hostel_id)
                        ->update('hostel');
                error_log("Login_model: DB update for hostel login_status result: " . ($update_result ? 'Success' : 'Failed'));
                return $update_result;
            } catch (Throwable $t) {
                error_log("Login_model: CRITICAL ERROR during hostel login_status update: " . $t->getMessage());
                return true; 
            }
        }

        $query = $this->db->get_where('accountant', $credential);
        error_log("Login_model: accountant query (general check) executed. Num rows: " . ($query ? $query->num_rows() : 'Query failed or no result'));
        if ($query && $query->num_rows() > 0) {
            $row = $query->row();
            error_log("Login_model: Accountant found (general check). Setting session and attempting DB update. Accountant ID: " . (isset($row->accountant_id) ? $row->accountant_id : 'N/A'));
            $this->session->set_userdata('login_type', 'accountant');
            $this->session->set_userdata('accountant_login', '1');
            $this->session->set_userdata('accountant_id', $row->accountant_id);
            $this->session->set_userdata('login_user_id', $row->accountant_id);
            $this->session->set_userdata('name', $row->name);
            try {
                $update_result = $this->db->set('login_status', ('1'))
                        ->where('accountant_id', $row->accountant_id)
                        ->update('accountant');
                error_log("Login_model: DB update for accountant login_status result: " . ($update_result ? 'Success' : 'Failed'));
                return $update_result;
            } catch (Throwable $t) {
                error_log("Login_model: CRITICAL ERROR during accountant login_status update: " . $t->getMessage());
                return true; 
            }
        }

        $query = $this->db->get_where('librarian', $credential);
        error_log("Login_model: librarian query (general check) executed. Num rows: " . ($query ? $query->num_rows() : 'Query failed or no result'));
        if ($query && $query->num_rows() > 0) {
            $row = $query->row();
            error_log("Login_model: Librarian found (general check). Setting session and attempting DB update. Librarian ID: " . (isset($row->librarian_id) ? $row->librarian_id : 'N/A'));
            $this->session->set_userdata('login_type', 'librarian');
            $this->session->set_userdata('librarian_login', '1');
            $this->session->set_userdata('librarian_id', $row->librarian_id);
            $this->session->set_userdata('login_user_id', $row->librarian_id);
            $this->session->set_userdata('name', $row->name);
            try {
                $update_result = $this->db->set('login_status', ('1'))
                        ->where('librarian_id', $row->librarian_id)
                        ->update('librarian');
                error_log("Login_model: DB update for librarian login_status result: " . ($update_result ? 'Success' : 'Failed'));
                return $update_result;
            } catch (Throwable $t) {
                error_log("Login_model: CRITICAL ERROR during librarian login_status update: " . $t->getMessage());
                return true; 
            }
        }

        error_log("Login_model: Checking for 'student' (general check)"); 
        $query = $this->db->get_where('student', $credential);
        error_log("Login_model: student query (general check) executed. Num rows: " . ($query ? $query->num_rows() : 'Query failed or no result')); 
        if ($query && $query->num_rows() > 0) {
            error_log("Login_model: Student found (general check). Calling setStudentSession."); 
            $this->setStudentSession($query->row());
            return true;
        }
        error_log("Login_model: Student not found (general check)."); 

        error_log("Login_model: Checking for 'teacher' (general check)");
        $query = $this->db->get_where('teacher', $credential);
        error_log("Login_model: teacher query (general check) executed. Num rows: " . ($query ? $query->num_rows() : 'Query failed or no result'));
        if ($query && $query->num_rows() > 0) {
            error_log("Login_model: Teacher found (general check). Calling setTeacherSession.");
            $this->setTeacherSession($query->row());
            return true;
        }
        error_log("Login_model: Teacher not found (general check).");
        
        // Gemini: Added parent check to general login flow
        error_log("Login_model: Checking for 'parent' (general check via student table).");
        $parent_email_for_check = $credential['email'];
        $parent_password_hash_for_check = $credential['password'];

        // Try to login as father (general check)
        $this->db->where('father_email', $parent_email_for_check);
        $this->db->where('father_password_hash', $parent_password_hash_for_check);
        $query_father_general = $this->db->get('student');

        if ($query_father_general && $query_father_general->num_rows() > 0) {
            error_log("Login_model: Father found (general check). Calling setParentSession.");
            $this->setParentSession($query_father_general->row(), 'father');
            return true;
        }
        // error_log("Login_model: Father not found (general check)."); // Optional: reduce verbosity

        // Try to login as mother (general check)
        $this->db->where('mother_email', $parent_email_for_check);
        $this->db->where('mother_password_hash', $parent_password_hash_for_check);
        $query_mother_general = $this->db->get('student');

        if ($query_mother_general && $query_mother_general->num_rows() > 0) {
            error_log("Login_model: Mother found (general check). Calling setParentSession.");
            $this->setParentSession($query_mother_general->row(), 'mother');
            return true;
        }
        // error_log("Login_model: Mother not found (general check)."); // Optional: reduce verbosity
        // End Gemini: Added parent check

        error_log("Login_model: loginFunctionForAllUsers returning false (no user matched).");
        return false;
    }
    
    // Helper functions to set session data
    private function setAdminSession($row) {
        error_log("Login_model: setAdminSession called.");
        if (!$row) {
            error_log("Login_model: setAdminSession received null row. Aborting session set.");
            return;
        }
        if (!isset($row->admin_id) || !isset($row->name)) {
            error_log("Login_model: setAdminSession - row object does not have expected properties (admin_id or name). Row data: " . print_r($row, true));
            return; 
        }
        error_log("Login_model: Setting admin session data. Admin ID: " . $row->admin_id . ", Name: " . $row->name);

        $this->session->set_userdata('login_type', 'admin');
        $this->session->set_userdata('admin_login', '1');
        $this->session->set_userdata('admin_id', $row->admin_id);
        $this->session->set_userdata('login_user_id', $row->admin_id);
        $this->session->set_userdata('name', $row->name);

        error_log("Login_model: Updating admin login_status in DB for admin_id: " . $row->admin_id);
        try {
            $update_result = $this->db->set('login_status', ('1'))
                    ->where('admin_id', $row->admin_id)
                    ->update('admin');
            error_log("Login_model: DB update for admin login_status result: " . ($update_result ? 'Success' : 'Failed'));
        } catch (Throwable $t) {
            error_log("Login_model: CRITICAL ERROR during admin login_status update: " . $t->getMessage());
        }
    }
    
    private function setTeacherSession($row) {
        error_log("Login_model: setTeacherSession called.");
        if (!$row) {
            error_log("Login_model: setTeacherSession received null row. Aborting session set.");
            return;
        }
        if (!isset($row->teacher_id) || !isset($row->name)) {
            error_log("Login_model: setTeacherSession - row object does not have expected properties (teacher_id or name). Row data: " . print_r($row, true));
            return; 
        }
        error_log("Login_model: Setting teacher session data. Teacher ID: " . $row->teacher_id . ", Name: " . $row->name);

        $this->session->set_userdata('login_type', 'teacher');
        $this->session->set_userdata('teacher_login', '1');
        $this->session->set_userdata('teacher_id', $row->teacher_id);
        $this->session->set_userdata('login_user_id', $row->teacher_id);
        $this->session->set_userdata('name', $row->name);

        error_log("Login_model: Updating teacher login_status in DB for teacher_id: " . $row->teacher_id);
        try {
            $update_result = $this->db->set('login_status', ('1'))
                    ->where('teacher_id', $row->teacher_id)
                    ->update('teacher');
            error_log("Login_model: DB update for teacher login_status result: " . ($update_result ? 'Success' : 'Failed'));
        } catch (Throwable $t) {
            error_log("Login_model: CRITICAL ERROR during teacher login_status update: " . $t->getMessage());
        }
    }
    
    private function setStudentSession($row) {
        error_log("Login_model: setStudentSession called."); 
        if (!$row) {
            error_log("Login_model: setStudentSession received null row. Aborting session set."); 
            return;
        }
        if (!isset($row->student_id) || !isset($row->name)) {
            error_log("Login_model: setStudentSession - row object does not have expected properties (student_id or name). Row data: " . print_r($row, true)); 
            return; 
        }
        error_log("Login_model: Setting student session data. Student ID: " . $row->student_id . ", Name: " . $row->name); 

        $this->session->set_userdata('login_type', 'student');
        $this->session->set_userdata('student_login', '1');
        $this->session->set_userdata('student_id', $row->student_id);
        $this->session->set_userdata('login_user_id', $row->student_id);
        $this->session->set_userdata('name', $row->name);

        error_log("Login_model: Attempting to update student login_status in DB for student_id: " . $row->student_id); 
        try {
            $update_result = $this->db->set('login_status', ('1'))
                    ->where('student_id', $row->student_id) 
                    ->update('student');
            error_log("Login_model: DB update for student login_status result: " . ($update_result ? 'Success' : 'Failed')); 
        } catch (Throwable $t) {
            error_log("Login_model: CRITICAL ERROR during student login_status update: " . $t->getMessage() . " on line " . $t->getLine() . " in file " . $t->getFile());
            error_log("Login_model: Stack Trace: " . $t->getTraceAsString());
        }
    }
    
    private function setParentSession($row, $parent_type) {
        error_log("Login_model: setParentSession called for type: " . $parent_type);
        if (!$row || !isset($row->student_id)) {
            error_log("Login_model: setParentSession received null or invalid row (missing student_id). Aborting session set.");
            return;
        }

        $this->session->set_userdata('login_type', 'parent');
        $this->session->set_userdata('parent_login', '1');
        $this->session->set_userdata('student_id', $row->student_id);
        $this->session->set_userdata('logged_in_parent_type', $parent_type);

        if ($parent_type == 'father') {
            if (!isset($row->father_name)) {
                 error_log("Login_model: setParentSession - father row object does not have expected property (father_name). Row data: " . print_r($row, true));
            }
            error_log("Login_model: Setting father session data. Student ID: " . $row->student_id . ", Father Name: " . (isset($row->father_name) ? $row->father_name : 'N/A'));
            $this->session->set_userdata('parent_name', $row->father_name);
            $this->session->set_userdata('parent_email', $row->father_email);
            $this->session->set_userdata('parent_photo', isset($row->father_photo) ? $row->father_photo : null);
            $this->session->set_userdata('login_user_id', $row->student_id . '_father');
        } elseif ($parent_type == 'mother') {
            if (!isset($row->mother_name)) { 
                 error_log("Login_model: setParentSession - mother row object does not have expected property (mother_name). Row data: " . print_r($row, true));
            }
            error_log("Login_model: Setting mother session data. Student ID: " . $row->student_id . ", Mother Name: " . (isset($row->mother_name) ? $row->mother_name : 'N/A'));
            $this->session->set_userdata('parent_name', $row->mother_name);
            $this->session->set_userdata('parent_email', $row->mother_email);
            $this->session->set_userdata('parent_photo', isset($row->mother_photo) ? $row->mother_photo : null);
            $this->session->set_userdata('login_user_id', $row->student_id . '_mother');
        } else {
            error_log("Login_model: setParentSession called with invalid parent_type: " . $parent_type);
            return;
        }
    }

    function logout_model_for_admin(){
        error_log("Login_model: logout_model_for_admin for admin_id: " . $this->session->userdata('admin_id'));
        try {
            return $this->db->set('login_status', ('0'))
                        ->where('admin_id', $this->session->userdata('admin_id'))
                        ->update('admin');
        } catch (Throwable $t) {
            error_log("Login_model: CRITICAL ERROR during admin logout_status update: " . $t->getMessage());
            return false; // Indicate failure but don't crash
        }
    }

    function logout_model_for_hrm(){
        error_log("Login_model: logout_model_for_hrm for hrm_id: " . $this->session->userdata('hrm_id'));
        try {
            return $this->db->set('login_status', ('0'))
                        ->where('hrm_id', $this->session->userdata('hrm_id'))
                        ->update('hrm');
        } catch (Throwable $t) {
            error_log("Login_model: CRITICAL ERROR during hrm logout_status update: " . $t->getMessage());
            return false; 
        }
    }

    function logout_model_for_hostel(){
        error_log("Login_model: logout_model_for_hostel for hostel_id: " . $this->session->userdata('hostel_id'));
        try {
            return $this->db->set('login_status', ('0'))
                        ->where('hostel_id', $this->session->userdata('hostel_id'))
                        ->update('hostel');
        } catch (Throwable $t) {
            error_log("Login_model: CRITICAL ERROR during hostel logout_status update: " . $t->getMessage());
            return false; 
        }
    }

    function logout_model_for_accountant(){
        error_log("Login_model: logout_model_for_accountant for accountant_id: " . $this->session->userdata('accountant_id'));
        try {
            return $this->db->set('login_status', ('0'))
                        ->where('accountant_id', $this->session->userdata('accountant_id'))
                        ->update('accountant');
        } catch (Throwable $t) {
            error_log("Login_model: CRITICAL ERROR during accountant logout_status update: " . $t->getMessage());
            return false; 
        }
    }

    function logout_model_for_librarian(){
        error_log("Login_model: logout_model_for_librarian for librarian_id: " . $this->session->userdata('librarian_id'));
        try {
            return $this->db->set('login_status', ('0'))
                        ->where('librarian_id', $this->session->userdata('librarian_id'))
                        ->update('librarian');
        } catch (Throwable $t) {
            error_log("Login_model: CRITICAL ERROR during librarian logout_status update: " . $t->getMessage());
            return false; 
        }
    }

    function logout_model_for_parent(){
        error_log("Login_model: logout_model_for_parent. Clearing parent session data.");
        $this->session->unset_userdata('parent_login');
        $this->session->unset_userdata('student_id');
        $this->session->unset_userdata('parent_name');
        $this->session->unset_userdata('parent_email');
        $this->session->unset_userdata('parent_photo');
        $this->session->unset_userdata('logged_in_parent_type');
        return true;
    }

    function logout_model_for_teacher(){
        error_log("Login_model: logout_model_for_teacher for teacher_id: " . $this->session->userdata('teacher_id'));
        try {
            return $this->db->set('login_status', ('0'))
                        ->where('teacher_id', $this->session->userdata('teacher_id'))
                        ->update('teacher');
        } catch (Throwable $t) {
            error_log("Login_model: CRITICAL ERROR during teacher logout_status update: " . $t->getMessage());
            return false; 
        }
    }

    function logout_model_for_student(){
        error_log("Login_model: logout_model_for_student for student_id: " . $this->session->userdata('student_id'));
        try {
            return $this->db->set('login_status', ('0'))
                        ->where('student_id', $this->session->userdata('student_id'))
                        ->update('student');
        } catch (Throwable $t) {
            error_log("Login_model: CRITICAL ERROR during student logout_status update: " . $t->getMessage());
            return false; 
        }
    }
	
    function get_user_details_by_id($user_type = '', $user_id = '') {
        // ... existing code ...
    }
	
	
	


	
	
}
