<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Parent extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->library('role_based_access');
        
        // Check if user is logged in and has parent role
        $this->role_based_access->check_access('parent');
    }

    // ... existing code ...

    /* Class Routine for a specific child of a parent */
    function class_routine($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('parent_login') != 1)
            redirect(base_url(), 'refresh');
        
        $page_data['page_name']  = 'class_routine';
        $page_data['page_title'] = get_phrase('class_routine');
        $this->load->view('backend/index', $page_data);
    }

    /* Get class timetable data for AJAX request */
    function get_class_timetable_data() 
    {
        if ($this->session->userdata('parent_login') != 1)
            redirect(base_url(), 'refresh');
        
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
        
        // Get class routine data
        $this->db->select('cr.*, s.name as subject_name, t.name as teacher_name');
        $this->db->from('class_routine as cr');
        $this->db->join('subject as s', 's.subject_id = cr.subject_id', 'left');
        $this->db->join('teacher as t', 't.teacher_id = cr.teacher_id', 'left');
        $this->db->where('cr.class_id', $class_id);
        $this->db->where('cr.section_id', $section_id);
        $query = $this->db->get();
        $result = $query->result_array();
        
        echo json_encode($result);
    }

    /* Print timetable for a specific class/section */
    function print_timetable($class_id = '', $section_id = '')
    {
        if ($this->session->userdata('parent_login') != 1)
            redirect(base_url(), 'refresh');
        
        if (empty($class_id) || empty($section_id))
            redirect(site_url('parent/class_routine'), 'refresh');
        
        // Get class and section info
        $class_name = $this->db->get_where('class', array('class_id' => $class_id))->row()->name;
        $section_name = $this->db->get_where('section', array('section_id' => $section_id))->row()->name;
        
        // Get class routine data
        $this->db->select('cr.*, s.name as subject_name, t.name as teacher_name');
        $this->db->from('class_routine as cr');
        $this->db->join('subject as s', 's.subject_id = cr.subject_id', 'left');
        $this->db->join('teacher as t', 't.teacher_id = cr.teacher_id', 'left');
        $this->db->where('cr.class_id', $class_id);
        $this->db->where('cr.section_id', $section_id);
        $query = $this->db->get();
        $timetable_data = $query->result_array();
        
        $page_data['class_name'] = $class_name;
        $page_data['section_name'] = $section_name;
        $page_data['timetable_data'] = $timetable_data;
        $page_data['page_title'] = get_phrase('timetable') . ' - ' . $class_name . ' ' . $section_name;
        
        $this->load->view('backend/timetable_print_view', $page_data);
    }

    // ... existing code ...
} 