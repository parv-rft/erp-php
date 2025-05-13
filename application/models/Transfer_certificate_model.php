<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transfer_certificate_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
    }
    
    // Get student information by admission number
    function get_student_by_admission_number($admission_number) {
        // Try string comparison first
        $this->db->where('admission_number', $admission_number);
        $query = $this->db->get('student');
        
        // If no results, try integer comparison
        if ($query->num_rows() == 0) {
            $this->db->where('admission_number', (int)$admission_number);
            $query = $this->db->get('student');
        }
        
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        
        // Log for debugging if no student found
        error_log('No student found with admission_number: ' . $admission_number);
        
        // Try to get a sample student for debugging
        $sample_query = $this->db->limit(1)->get('student');
        if ($sample_query->num_rows() > 0) {
            $sample = $sample_query->row_array();
            error_log('Sample student data - admission_number: ' . $sample['admission_number'] . 
                     ' (type: ' . gettype($sample['admission_number']) . ')');
        } else {
            error_log('No students found in the database');
        }
        
        return false;
    }
    
    // Get detailed student information for transfer certificate
    function get_student_details($admission_number) {
        // Convert admission_number to integer since it's stored as an integer in the database
        $admission_number = (int)$admission_number;
        
        $student = $this->get_student_by_admission_number($admission_number);
        
        if (!$student) {
            error_log('Failed to find student with admission number: ' . $admission_number);
            return false;
        }
        
        // Log all student data for debugging
        error_log('Student data: ' . print_r($student, true));
        
        // Get class information
        $class = $this->db->get_where('class', array('class_id' => $student['class_id']))->row_array();
        
        // Get section information
        $section = $this->db->get_where('section', array('section_id' => $student['section_id']))->row_array();
        
        // Get total attendance
        $total_attendance = $this->get_student_attendance($student['student_id']);
        
        // Get subjects
        $subjects = $this->get_student_subjects($student['class_id']);
        
        // Match the field names between student and transfer_certificate tables
        $response = array(
            'student_id' => $student['student_id'],
            'student_name' => $student['name'],
            
            // Handle father information with fallbacks
            'father_name' => isset($student['father_name']) && !empty($student['father_name']) 
                ? $student['father_name'] : 'Not Available',
            'father_phone' => isset($student['father_phone']) && !empty($student['father_phone']) 
                ? $student['father_phone'] : '',
            'father_email' => isset($student['father_email']) && !empty($student['father_email']) 
                ? $student['father_email'] : '',
            'father_occupation' => isset($student['father_occupation']) && !empty($student['father_occupation']) 
                ? $student['father_occupation'] : '',
                
            // Handle mother information with fallbacks
            'mother_name' => isset($student['mother_name']) && !empty($student['mother_name']) 
                ? $student['mother_name'] : 'Not Available',
            'mother_phone' => isset($student['mother_phone']) && !empty($student['mother_phone']) 
                ? $student['mother_phone'] : '',
            'mother_email' => isset($student['mother_email']) && !empty($student['mother_email']) 
                ? $student['mother_email'] : '',
            'mother_occupation' => isset($student['mother_occupation']) && !empty($student['mother_occupation']) 
                ? $student['mother_occupation'] : '',
                
            'date_of_birth' => isset($student['birthday']) ? $student['birthday'] : '',
            'admission_number' => $student['admission_number'],
            'student_class' => $class ? $class['name'] : '',
            'roll_no' => isset($student['roll']) ? $student['roll'] : '',
            'obtained_attendance' => $total_attendance,
            'subjects' => $subjects,
            'nationality' => isset($student['nationality']) ? $student['nationality'] : '',
            'admit_class' => isset($student['class_study']) ? $student['class_study'] : ''
        );
        
        // Handle date fields with proper fallbacks
        if (isset($student['date_of_admission']) && !empty($student['date_of_admission'])) {
            $response['date_of_admission'] = $student['date_of_admission'];
        } else if (isset($student['admission_date']) && !empty($student['admission_date'])) {
            $response['date_of_admission'] = $student['admission_date'];
        } else if (isset($student['date_of_joining']) && !empty($student['date_of_joining'])) {
            $response['date_of_admission'] = $student['date_of_joining'];
        } else {
            $response['date_of_admission'] = date('Y-m-d');
        }
        
        // Handle leaving date
        if (isset($student['date_of_leaving']) && !empty($student['date_of_leaving'])) {
            $response['date_of_leaving'] = $student['date_of_leaving'];
        } else {
            $response['date_of_leaving'] = date('Y-m-d');
        }
        
        return $response;
    }
    
    // Generate new unique TC number
    function generate_tc_number() {
        // Get the last TC number and increment it
        $this->db->select('tc_no');
        $this->db->order_by('tc_id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get('transfer_certificate');
        
        if ($query->num_rows() > 0) {
            $last_tc = $query->row_array();
            $tc_parts = explode('/', $last_tc['tc_no']);
            $tc_number = intval(end($tc_parts)) + 1;
        } else {
            $tc_number = 1;
        }
        
        // Format: TC/YEAR/NUMBER
        $year = date('Y');
        return "TC/{$year}/{$tc_number}";
    }
    
    // Create a new transfer certificate
    function create_certificate() {
        $data = array(
            'tc_no' => $this->input->post('tc_no'),
            'student_id' => $this->input->post('student_id'),
            'student_name' => $this->input->post('student_name'),
            'admission_number' => $this->input->post('admission_number'),
            'father_name' => $this->input->post('father_name'),
            'mother_name' => $this->input->post('mother_name'),
            'nationality' => $this->input->post('nationality'),
            'category' => $this->input->post('category'),
            'date_of_birth' => $this->input->post('date_of_birth'),
            'qualified' => $this->input->post('qualified'),
            'date_of_admission' => $this->input->post('date_of_admission'),
            'date_of_leaving' => $this->input->post('date_of_leaving'),
            'student_class' => $this->input->post('student_class'),
            'to_class' => $this->input->post('to_class'),
            'class_in_words' => $this->input->post('class_in_words'),
            'admit_class' => $this->input->post('admit_class'),
            'fees_paid_up_to' => $this->input->post('fees_paid_up_to'),
            'fees_concession_availed' => $this->input->post('fees_concession_availed'),
            'max_attendance' => $this->input->post('max_attendance'),
            'obtained_attendance' => $this->input->post('obtained_attendance'),
            'last_attendance_date' => $this->input->post('last_attendance_date'),
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
            'date_of_issue' => $this->input->post('date_of_issue'),
            'roll_no' => $this->input->post('roll_no'),
            'issue_status' => 'issued'
        );
        
        // Insert and return the new ID
        $this->db->insert('transfer_certificate', $data);
        return $this->db->insert_id();
    }
    
    // Get certificate by ID
    function get_certificate($tc_id) {
        $this->db->where('tc_id', $tc_id);
        $query = $this->db->get('transfer_certificate');
        
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return false;
    }
    
    // Update existing certificate
    function update_certificate($tc_id) {
        $data = array(
            'student_name' => $this->input->post('student_name'),
            'admission_number' => $this->input->post('admission_number'),
            'father_name' => $this->input->post('father_name'),
            'mother_name' => $this->input->post('mother_name'),
            'nationality' => $this->input->post('nationality'),
            'category' => $this->input->post('category'),
            'date_of_birth' => $this->input->post('date_of_birth'),
            'qualified' => $this->input->post('qualified'),
            'date_of_admission' => $this->input->post('date_of_admission'),
            'date_of_leaving' => $this->input->post('date_of_leaving'),
            'student_class' => $this->input->post('student_class'),
            'to_class' => $this->input->post('to_class'),
            'class_in_words' => $this->input->post('class_in_words'),
            'admit_class' => $this->input->post('admit_class'),
            'fees_paid_up_to' => $this->input->post('fees_paid_up_to'),
            'fees_concession_availed' => $this->input->post('fees_concession_availed'),
            'max_attendance' => $this->input->post('max_attendance'),
            'obtained_attendance' => $this->input->post('obtained_attendance'),
            'last_attendance_date' => $this->input->post('last_attendance_date'),
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
            'roll_no' => $this->input->post('roll_no'),
            'date_of_issue' => $this->input->post('date_of_issue')
        );
        
        $this->db->where('tc_id', $tc_id);
        return $this->db->update('transfer_certificate', $data);
    }
    
    // Delete certificate
    function delete_certificate($tc_id) {
        $this->db->where('tc_id', $tc_id);
        return $this->db->delete('transfer_certificate');
    }
    
    // Get all transfer certificates
    function get_all_certificates() {
        $this->db->order_by('tc_id', 'DESC');
        $query = $this->db->get('transfer_certificate');
        return $query->result_array();
    }
    
    // Count certificates
    function count_certificates() {
        return $this->db->count_all('transfer_certificate');
    }
    
    // Get certificate by student ID
    function get_certificate_by_student_id($student_id) {
        $this->db->where('student_id', $student_id);
        $query = $this->db->get('transfer_certificate');
        
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return false;
    }
    
    // Search certificates
    function search_certificates($search_term) {
        $this->db->like('student_name', $search_term);
        $this->db->or_like('admission_number', $search_term);
        $this->db->or_like('tc_no', $search_term);
        $query = $this->db->get('transfer_certificate');
        return $query->result_array();
    }
    
    // Get student attendance details
    function get_student_attendance($student_id) {
        $this->db->select('COUNT(*) as total_days');
        $this->db->where('student_id', $student_id);
        $this->db->where('status', 1); // 1 for present
        $query = $this->db->get('attendance');
        
        return $query->row()->total_days;
    }
    
    // Get student class details
    function get_student_class_details($class_id) {
        $this->db->where('class_id', $class_id);
        $query = $this->db->get('class');
        
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return false;
    }
    
    // Get student subjects
    function get_student_subjects($class_id) {
        $this->db->select('name');
        $this->db->where('class_id', $class_id);
        $query = $this->db->get('subject');
        
        $subjects = array();
        foreach ($query->result_array() as $row) {
            $subjects[] = $row['name'];
        }
        
        return implode(', ', $subjects);
    }
}
