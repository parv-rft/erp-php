<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Transfer_certificate_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
    }
    
    /**
     * Get student details for certificate
     */
    function get_student_details($admission_number) {
        // Get student info
        $student = $this->db->get_where('student', array('student_id' => $admission_number))->row_array();
        
        if (empty($student)) {
            // Try searching by admission number
            $student = $this->db->get_where('student', array('admission_no' => $admission_number))->row_array();
            
            if (empty($student)) {
                return false;
            }
        }
        
        // Get class info
        $class = $this->db->get_where('class', array('class_id' => $student['class_id']))->row_array();
        $class_name = $class ? $class['name'] : '';
        
        // Get section info
        $section = $this->db->get_where('section', array('section_id' => $student['section_id']))->row_array();
        $section_name = $section ? $section['name'] : '';
        
        // Get school info
        $school_info = $this->db->get('settings')->row_array();
        
        // Format the data for frontend
        $data = array(
            'student_id' => $student['student_id'],
            'admissionNumber' => $student['admission_no'],
            'fullName' => $student['name'],
            'fatherName' => $student['father_name'],
            'motherName' => $student['mother_name'],
            'nationality' => $student['nationality'] ?? 'Indian',
            'category' => $student['caste'] ?? '',
            'dateOfBirth' => date('Y-m-d', strtotime($student['birthday'])),
            'dateOfAdmission' => $student['date_of_joining'] ?? date('Y-m-d'),
            'currentClass' => $class_name . ' ' . $section_name,
            'admitClass' => $class_name,
            'feesUpToDate' => date('Y-m-d'),
            'rollNo' => $student['roll'],
            'conduct' => 'Good',
            'remarks' => '',
            'schoolDetails' => array(
                'schoolName' => $school_info['system_name'],
                'address' => $school_info['address'],
                'recognitionId' => $school_info['recognition_id'] ?? '',
                'affiliationNo' => $school_info['affiliation_no'] ?? '',
                'contact' => $school_info['phone'],
                'email' => $school_info['system_email'],
                'website' => $school_info['website'] ?? ''
            )
        );
        
        return $data;
    }
    
    /**
     * Create new transfer certificate
     */
    function create_certificate() {
        $data = array(
            'tc_no' => $this->input->post('tc_no'),
            'student_id' => $this->db->get_where('student', array('admission_no' => $this->input->post('admission_number')))->row()->student_id,
            'student_name' => $this->input->post('student_name'),
            'admission_number' => $this->input->post('admission_number'),
            'father_name' => $this->input->post('father_name'),
            'mother_name' => $this->input->post('mother_name'),
            'nationality' => $this->input->post('nationality'),
            'category' => $this->input->post('category'),
            'date_of_birth' => date('Y-m-d', strtotime($this->input->post('date_of_birth'))),
            'qualified' => $this->input->post('qualified'),
            'date_of_admission' => date('Y-m-d', strtotime($this->input->post('date_of_admission'))),
            'date_of_leaving' => date('Y-m-d', strtotime($this->input->post('date_of_leaving'))),
            'student_class' => $this->input->post('student_class'),
            'to_class' => $this->input->post('to_class'),
            'class_in_words' => $this->input->post('class_in_words'),
            'admit_class' => $this->input->post('admit_class'),
            'fees_paid_up_to' => date('Y-m-d', strtotime($this->input->post('fees_paid_up_to'))),
            'fees_concession_availed' => $this->input->post('fees_concession_availed'),
            'max_attendance' => $this->input->post('max_attendance'),
            'obtained_attendance' => $this->input->post('obtained_attendance'),
            'last_attendance_date' => date('Y-m-d', strtotime($this->input->post('last_attendance_date'))),
            'tc_charge' => $this->input->post('tc_charge'),
            'exam_in' => $this->input->post('exam_in'),
            'whether_failed' => $this->input->post('whether_failed'),
            'subject' => $this->input->post('subject'),
            'games_played' => $this->input->post('games_played'),
            'extra_activity' => $this->input->post('extra_activity'),
            'general_conduct' => $this->input->post('general_conduct'),
            'behavior_remarks' => $this->input->post('behavior_remarks'),
            'reason' => $this->input->post('reason'),
            'remarks' => $this->input->post('remarks'),
            'date_of_issue' => date('Y-m-d', strtotime($this->input->post('date_of_issue'))),
            'roll_no' => $this->input->post('roll_no')
        );
        
        $this->db->insert('transfer_certificate', $data);
        return $this->db->insert_id();
    }
    
    /**
     * Update transfer certificate
     */
    function update_certificate($tc_id) {
        $data = array(
            'tc_no' => $this->input->post('tc_no'),
            'student_name' => $this->input->post('student_name'),
            'father_name' => $this->input->post('father_name'),
            'mother_name' => $this->input->post('mother_name'),
            'nationality' => $this->input->post('nationality'),
            'category' => $this->input->post('category'),
            'date_of_birth' => date('Y-m-d', strtotime($this->input->post('date_of_birth'))),
            'qualified' => $this->input->post('qualified'),
            'date_of_admission' => date('Y-m-d', strtotime($this->input->post('date_of_admission'))),
            'date_of_leaving' => date('Y-m-d', strtotime($this->input->post('date_of_leaving'))),
            'student_class' => $this->input->post('student_class'),
            'to_class' => $this->input->post('to_class'),
            'class_in_words' => $this->input->post('class_in_words'),
            'admit_class' => $this->input->post('admit_class'),
            'fees_paid_up_to' => date('Y-m-d', strtotime($this->input->post('fees_paid_up_to'))),
            'fees_concession_availed' => $this->input->post('fees_concession_availed'),
            'max_attendance' => $this->input->post('max_attendance'),
            'obtained_attendance' => $this->input->post('obtained_attendance'),
            'last_attendance_date' => date('Y-m-d', strtotime($this->input->post('last_attendance_date'))),
            'tc_charge' => $this->input->post('tc_charge'),
            'exam_in' => $this->input->post('exam_in'),
            'whether_failed' => $this->input->post('whether_failed'),
            'subject' => $this->input->post('subject'),
            'games_played' => $this->input->post('games_played'),
            'extra_activity' => $this->input->post('extra_activity'),
            'general_conduct' => $this->input->post('general_conduct'),
            'behavior_remarks' => $this->input->post('behavior_remarks'),
            'reason' => $this->input->post('reason'),
            'remarks' => $this->input->post('remarks'),
            'date_of_issue' => date('Y-m-d', strtotime($this->input->post('date_of_issue'))),
            'roll_no' => $this->input->post('roll_no')
        );
        
        $this->db->where('tc_id', $tc_id);
        return $this->db->update('transfer_certificate', $data);
    }
    
    /**
     * Delete transfer certificate
     */
    function delete_certificate($tc_id) {
        $this->db->where('tc_id', $tc_id);
        return $this->db->delete('transfer_certificate');
    }
    
    /**
     * Get transfer certificate by ID
     */
    function get_certificate($tc_id) {
        return $this->db->get_where('transfer_certificate', array('tc_id' => $tc_id))->row_array();
    }
    
    /**
     * Get all transfer certificates
     */
    function get_all_certificates() {
        $this->db->order_by('tc_id', 'DESC');
        return $this->db->get('transfer_certificate')->result_array();
    }
    
    /**
     * Get transfer certificates by student ID
     */
    function get_certificates_by_student($student_id) {
        $this->db->order_by('tc_id', 'DESC');
        return $this->db->get_where('transfer_certificate', array('student_id' => $student_id))->result_array();
    }
    
    /**
     * Generate next TC number
     */
    function generate_tc_number() {
        $this->db->select_max('tc_no');
        $query = $this->db->get('transfer_certificate');
        $result = $query->row();
        
        if (!empty($result->tc_no)) {
            // Extract numeric part and increment
            $tc_number = intval(preg_replace('/[^0-9]/', '', $result->tc_no)) + 1;
        } else {
            // First TC
            $tc_number = 1;
        }
        
        // Get current year (last 2 digits)
        $year = date('y');
        
        // Format as TC/YEAR/NUMBER
        return 'TC/' . $year . '/' . str_pad($tc_number, 4, '0', STR_PAD_LEFT);
    }
    
    /**
     * Ensure transfer certificate table exists
     */
    function create_table_if_not_exists() {
        if (!$this->db->table_exists('transfer_certificate')) {
            $query = "CREATE TABLE IF NOT EXISTS `transfer_certificate` (
                `tc_id` int(11) NOT NULL AUTO_INCREMENT,
                `tc_no` varchar(50) NOT NULL,
                `student_id` int(11) NOT NULL,
                `student_name` varchar(100) NOT NULL,
                `admission_number` varchar(50) NOT NULL,
                `father_name` varchar(100) NOT NULL,
                `mother_name` varchar(100) NOT NULL,
                `nationality` varchar(50) DEFAULT NULL,
                `category` varchar(50) DEFAULT NULL,
                `date_of_birth` date NOT NULL,
                `qualified` varchar(50) DEFAULT NULL,
                `date_of_admission` date NOT NULL,
                `date_of_leaving` date NOT NULL,
                `student_class` varchar(50) NOT NULL,
                `to_class` varchar(50) DEFAULT NULL,
                `class_in_words` varchar(100) DEFAULT NULL,
                `admit_class` varchar(50) DEFAULT NULL,
                `fees_paid_up_to` date DEFAULT NULL,
                `fees_concession_availed` varchar(50) DEFAULT NULL,
                `max_attendance` varchar(20) DEFAULT NULL,
                `obtained_attendance` varchar(20) DEFAULT NULL,
                `last_attendance_date` date DEFAULT NULL,
                `tc_charge` varchar(20) DEFAULT NULL,
                `exam_in` varchar(100) DEFAULT NULL,
                `whether_failed` varchar(50) DEFAULT NULL,
                `subject` text DEFAULT NULL,
                `games_played` text DEFAULT NULL,
                `extra_activity` text DEFAULT NULL,
                `general_conduct` varchar(50) DEFAULT NULL,
                `behavior_remarks` text DEFAULT NULL,
                `reason` varchar(255) DEFAULT NULL,
                `remarks` text DEFAULT NULL,
                `date_of_issue` date NOT NULL,
                `roll_no` varchar(50) DEFAULT NULL,
                `issue_status` varchar(20) DEFAULT 'issued',
                `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`tc_id`),
                UNIQUE KEY `tc_no` (`tc_no`),
                KEY `student_id` (`student_id`),
                KEY `admission_number` (`admission_number`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
            
            $this->db->query($query);
            return true;
        }
        
        return false;
    }
} 