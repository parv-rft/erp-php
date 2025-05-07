<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        header('Content-Type: application/json');
        
        // Load required models
        $this->load->model('login_model');
        $this->load->model('crud_model');
        $this->load->database();
        $this->load->library('session');
    }
    
    // Authentication API
    public function login() {
        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $user_type = $this->input->post('user_type');
        
        if (!$email || !$password) {
            echo json_encode(['status' => 'error', 'message' => 'Email and password required']);
            return;
        }
        
        $login_success = $this->login_model->loginFunctionForAllUsers($user_type);
        
        if ($login_success) {
            // Create a token (simple implementation - in production use JWT)
            $user_type = $this->session->userdata('login_type');
            $user_id = $this->session->userdata('login_user_id');
            $name = $this->session->userdata('name');
            
            $user_data = [
                'user_id' => $user_id,
                'type' => $user_type,
                'name' => $name,
                'email' => $email
            ];
            
            // Very simple token implementation. In production, use a proper JWT library
            $token = base64_encode(json_encode($user_data) . time());
            
            echo json_encode([
                'status' => 'success', 
                'message' => 'Login successful',
                'user' => $user_data,
                'token' => $token
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid credentials']);
        }
    }
    
    // Get dashboard data
    public function dashboard() {
        $user_type = $this->input->get('user_type');
        $user_id = $this->input->get('user_id');
        
        // Validation
        if (!$user_type || !$user_id) {
            echo json_encode(['status' => 'error', 'message' => 'User information required']);
            return;
        }
        
        $data = [];
        
        // Get different dashboard data based on user role
        if ($user_type == 'admin') {
            $data['total_students'] = $this->db->count_all('student');
            $data['total_teachers'] = $this->db->count_all('teacher');
            $data['total_classes'] = $this->db->count_all('class');
            $data['total_sections'] = $this->db->count_all('section');
        } else if ($user_type == 'teacher') {
            // Get teacher specific dashboard data
            $data['classes'] = $this->crud_model->get_classes_by_teacher($user_id);
            $data['subjects'] = $this->crud_model->get_subjects_by_teacher($user_id);
        } else if ($user_type == 'student') {
            // Get student specific dashboard data
            $student_info = $this->crud_model->get_student_info($user_id);
            $data['class'] = $this->crud_model->get_class_name($student_info['class_id']);
            $data['section'] = $this->crud_model->get_section_name($student_info['section_id']);
            $data['subjects'] = $this->crud_model->get_subjects_by_class($student_info['class_id']);
        }
        
        echo json_encode(['status' => 'success', 'data' => $data]);
    }
    
    // Get students list
    public function students() {
        $class_id = $this->input->get('class_id');
        
        if ($class_id) {
            $students = $this->crud_model->get_students_by_class($class_id);
        } else {
            $students = $this->crud_model->get_students();
        }
        
        // Transform data for the mobile app
        $student_data = [];
        foreach ($students as $student) {
            $student_data[] = [
                'id' => $student['student_id'],
                'name' => $student['name'],
                'email' => $student['email'],
                'phone' => $student['phone'],
                'class' => $this->crud_model->get_class_name($student['class_id']),
                'section' => $this->crud_model->get_section_name($student['section_id']),
                'image_url' => base_url() . 'uploads/student_image/' . $student['student_id'] . '.jpg'
            ];
        }
        
        echo json_encode(['status' => 'success', 'data' => $student_data]);
    }
    
    // Get timetable
    public function timetable() {
        $class_id = $this->input->get('class_id');
        $section_id = $this->input->get('section_id');
        $teacher_id = $this->input->get('teacher_id');
        
        if ($teacher_id) {
            // Teacher timetable
            $this->load->model('timetable_model');
            $timetable = $this->timetable_model->get_teacher_timetable($teacher_id);
        } else if ($class_id && $section_id) {
            // Class timetable
            $this->load->model('timetable_model');
            $timetable = $this->timetable_model->get_class_timetable($class_id, $section_id);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Required parameters missing']);
            return;
        }
        
        echo json_encode(['status' => 'success', 'data' => $timetable]);
    }
    
    // Logout
    public function logout() {
        $user_type = $this->input->post('user_type');
        $user_id = $this->input->post('user_id');
        
        if ($user_type == 'admin') {
            $this->login_model->logout_model_for_admin();
        } else if ($user_type == 'teacher') {
            $this->login_model->logout_model_for_teacher();
        } else if ($user_type == 'student') {
            $this->login_model->logout_model_for_student();
        } else if ($user_type == 'parent') {
            $this->login_model->logout_model_for_parent();
        } else if ($user_type == 'accountant') {
            $this->login_model->logout_model_for_accountant();
        } else if ($user_type == 'librarian') {
            $this->login_model->logout_model_for_librarian();
        } else if ($user_type == 'hostel') {
            $this->login_model->logout_model_for_hostel();
        } else if ($user_type == 'hrm') {
            $this->login_model->logout_model_for_hrm();
        }
        
        $this->session->sess_destroy();
        echo json_encode(['status' => 'success', 'message' => 'Logged out successfully']);
    }
} 