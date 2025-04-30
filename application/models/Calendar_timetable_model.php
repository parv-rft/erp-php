<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Calendar_timetable_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    // Get timetable entries for a specific month
    public function get_monthly_timetable($class_id, $section_id, $month, $year, $teacher_id = null, $subject_id = null) {
        $this->db->select('calendar_timetable.*, subject.name as subject_name, teacher.name as teacher_name');
        $this->db->from('calendar_timetable');
        $this->db->join('subject', 'subject.subject_id = calendar_timetable.subject_id');
        $this->db->join('teacher', 'teacher.teacher_id = calendar_timetable.teacher_id');
        $this->db->where('calendar_timetable.class_id', $class_id);
        $this->db->where('calendar_timetable.section_id', $section_id);
        
        // Filter by month and year
        $start_date = date('Y-m-d', strtotime($year . '-' . $month . '-01'));
        $end_date = date('Y-m-t', strtotime($start_date));
        $this->db->where('calendar_timetable.date >=', $start_date);
        $this->db->where('calendar_timetable.date <=', $end_date);
        
        // Optional filters
        if ($teacher_id) {
            $this->db->where('calendar_timetable.teacher_id', $teacher_id);
        }
        if ($subject_id) {
            $this->db->where('calendar_timetable.subject_id', $subject_id);
        }
        
        $query = $this->db->get();
        $results = $query->result_array();
        
        // Format results as associative array with date_timeslot as key
        $formatted = array();
        foreach ($results as $row) {
            $key = $row['date'] . '_' . $row['time_slot'];
            $formatted[$key] = array(
                'teacher_name' => $row['teacher_name'],
                'subject_name' => $row['subject_name'],
                'teacher_id' => $row['teacher_id'],
                'subject_id' => $row['subject_id']
            );
        }
        
        return $formatted;
    }
    
    // Save or update a timetable entry
    public function save_timetable_slot($data) {
        // Check for existing entry
        $this->db->where('class_id', $data['class_id']);
        $this->db->where('section_id', $data['section_id']);
        $this->db->where('date', $data['date']);
        $this->db->where('time_slot', $data['time_slot']);
        $existing = $this->db->get('calendar_timetable')->row();
        
        if ($existing) {
            // Update existing entry
            $this->db->where('id', $existing->id);
            return $this->db->update('calendar_timetable', array(
                'subject_id' => $data['subject_id'],
                'teacher_id' => $data['teacher_id']
            ));
        } else {
            // Insert new entry
            return $this->db->insert('calendar_timetable', $data);
        }
    }
    
    // Delete a timetable entry
    public function delete_timetable_slot($class_id, $section_id, $date, $time_slot) {
        $this->db->where('class_id', $class_id);
        $this->db->where('section_id', $section_id);
        $this->db->where('date', $date);
        $this->db->where('time_slot', $time_slot);
        return $this->db->delete('calendar_timetable');
    }
    
    // Check for teacher availability
    public function check_teacher_availability($teacher_id, $date, $time_slot) {
        $this->db->where('teacher_id', $teacher_id);
        $this->db->where('date', $date);
        $this->db->where('time_slot', $time_slot);
        $query = $this->db->get('calendar_timetable');
        return $query->num_rows() === 0;
    }
} 