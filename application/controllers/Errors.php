<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Errors extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
    }
    
    public function error_404() {
        // Check if user is logged in
        if ($this->session->userdata('admin_login') == 1) {
            $page_data['page_name'] = 'error_404';
            $page_data['page_title'] = 'Page Not Found';
            $this->load->view('backend/index', $page_data);
        } elseif ($this->session->userdata('teacher_login') == 1) {
            $page_data['page_name'] = 'error_404';
            $page_data['page_title'] = 'Page Not Found';
            $this->load->view('backend/index', $page_data);
        } elseif ($this->session->userdata('student_login') == 1) {
            $page_data['page_name'] = 'error_404';
            $page_data['page_title'] = 'Page Not Found';
            $this->load->view('backend/index', $page_data);
        } elseif ($this->session->userdata('parent_login') == 1) {
            $page_data['page_name'] = 'error_404';
            $page_data['page_title'] = 'Page Not Found';
            $this->load->view('backend/index', $page_data);
        } else {
            // Not logged in, redirect to login page
            redirect(base_url(), 'refresh');
        }
    }
} 