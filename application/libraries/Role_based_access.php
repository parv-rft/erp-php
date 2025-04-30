<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Role_based_access {
    protected $CI;

    public function __construct() {
        $this->CI =& get_instance();
    }

    public function check_access($required_role) {
        // Get the current user's role
        $user_role = $this->CI->session->userdata('login_type');
        
        // If no role is set, redirect to login
        if (!$user_role) {
            redirect(base_url() . 'login', 'refresh');
        }

        // Check if user has the required role
        if ($user_role !== $required_role) {
            // Set error message
            $this->CI->session->set_flashdata('error_message', 'Access Denied: You do not have permission to access this page.');
            
            // Redirect to appropriate dashboard based on user role
            switch ($user_role) {
                case 'admin':
                    redirect(base_url() . 'admin/dashboard', 'refresh');
                    break;
                case 'teacher':
                    redirect(base_url() . 'teacher/dashboard', 'refresh');
                    break;
                case 'student':
                    redirect(base_url() . 'student/dashboard', 'refresh');
                    break;
                case 'parent':
                    redirect(base_url() . 'parent/dashboard', 'refresh');
                    break;
                default:
                    redirect(base_url() . 'login', 'refresh');
            }
        }
    }
} 