<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Calendar_timetable_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
        
        // Create timetable table if it doesn't exist
        if (!$this->db->table_exists('calendar_timetable')) {
            $this->create_table_if_not_exists();
        }
    }
    
    // Create the calendar timetable table if it doesn't exist
    function create_table_if_not_exists() {
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `calendar_timetable` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `class_id` int(11) NOT NULL,
              `section_id` int(11) NOT NULL,
              `subject_id` int(11) DEFAULT NULL,
              `teacher_id` int(11) DEFAULT NULL,
              `day_of_week` varchar(20) NOT NULL,
              `time_slot_start` time NOT NULL,
              `time_slot_end` time NOT NULL,
              `month` int(2) NOT NULL,
              `year` int(4) NOT NULL,
              `room_number` varchar(20) DEFAULT NULL,
              `notes` text DEFAULT NULL,
              `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
              PRIMARY KEY (`id`),
              KEY `class_section_idx` (`class_id`, `section_id`),
              KEY `day_time_idx` (`day_of_week`, `time_slot_start`),
              KEY `month_year_idx` (`month`, `year`),
              UNIQUE KEY `unique_slot` (`class_id`, `section_id`, `day_of_week`, `time_slot_start`, `month`, `year`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");
    }
    
    // Add a new timetable entry
    function add_timetable_entry($data) {
        try {
            // First check if the table exists
            if (!$this->db->table_exists('calendar_timetable')) {
                $this->create_table_if_not_exists();
            }
            
            // Check for conflicts
            if ($this->check_timetable_conflict($data)) {
                return [
                    'status' => 'error',
                    'message' => 'There is a scheduling conflict with this time slot.'
                ];
            }
            
            // Validate required fields
            $required_fields = ['class_id', 'section_id', 'day_of_week', 'time_slot_start', 'time_slot_end', 'month', 'year'];
            foreach ($required_fields as $field) {
                if (empty($data[$field])) {
                    return [
                        'status' => 'error',
                        'message' => ucfirst(str_replace('_', ' ', $field)) . ' is required'
                    ];
                }
            }
            
            // Check if referenced entities exist
            $exists = $this->db->get_where('class', array('class_id' => $data['class_id']))->num_rows() > 0;
            if (!$exists) {
                return [
                    'status' => 'error',
                    'message' => 'The specified class does not exist'
                ];
            }
            
            $exists = $this->db->get_where('section', array('section_id' => $data['section_id']))->num_rows() > 0;
            if (!$exists) {
                return [
                    'status' => 'error',
                    'message' => 'The specified section does not exist'
                ];
            }
            
            if (!empty($data['subject_id'])) {
                $exists = $this->db->get_where('subject', array('subject_id' => $data['subject_id']))->num_rows() > 0;
                if (!$exists) {
                    return [
                        'status' => 'error',
                        'message' => 'The specified subject does not exist'
                    ];
                }
            }
            
            if (!empty($data['teacher_id'])) {
                $exists = $this->db->get_where('teacher', array('teacher_id' => $data['teacher_id']))->num_rows() > 0;
                if (!$exists) {
                    return [
                        'status' => 'error',
                        'message' => 'The specified teacher does not exist'
                    ];
                }
            }
            
            // Insert the data
            $this->db->insert('calendar_timetable', $data);
            
            if ($this->db->affected_rows() > 0) {
                return [
                    'status' => 'success',
                    'message' => 'Timetable entry added successfully.',
                    'id' => $this->db->insert_id()
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Failed to add timetable entry. Please try again.'
                ];
            }
        } catch (Exception $e) {
            log_message('error', 'Error in add_timetable_entry: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'An error occurred: ' . $e->getMessage()
            ];
        }
    }
    
    // Update a timetable entry
    function update_timetable_entry($id, $data) {
        try {
            // First check if the table exists
            if (!$this->db->table_exists('calendar_timetable')) {
                $this->create_table_if_not_exists();
                return [
                    'status' => 'error',
                    'message' => 'The entry does not exist'
                ];
            }
            
            // Verify the entry exists
            $exists = $this->db->get_where('calendar_timetable', array('id' => $id))->num_rows() > 0;
            if (!$exists) {
                return [
                    'status' => 'error',
                    'message' => 'The entry does not exist'
                ];
            }
            
            // Check for conflicts (excluding the current entry)
            if ($this->check_timetable_conflict($data, $id)) {
                return [
                    'status' => 'error',
                    'message' => 'There is a scheduling conflict with this time slot.'
                ];
            }
            
            // Update the data
            $this->db->where('id', $id);
            $this->db->update('calendar_timetable', $data);
            
            if ($this->db->affected_rows() > 0) {
                return [
                    'status' => 'success',
                    'message' => 'Timetable entry updated successfully.'
                ];
            } else {
                return [
                    'status' => 'success',
                    'message' => 'No changes were made to the timetable entry.'
                ];
            }
        } catch (Exception $e) {
            log_message('error', 'Error in update_timetable_entry: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'An error occurred: ' . $e->getMessage()
            ];
        }
    }
    
    // Delete a timetable entry
    function delete_timetable_entry($id) {
        try {
            // First check if the table exists
            if (!$this->db->table_exists('calendar_timetable')) {
                $this->create_table_if_not_exists();
                return [
                    'status' => 'error',
                    'message' => 'The entry does not exist'
                ];
            }
            
            // Verify the entry exists
            $exists = $this->db->get_where('calendar_timetable', array('id' => $id))->num_rows() > 0;
            if (!$exists) {
                return [
                    'status' => 'error',
                    'message' => 'The entry does not exist'
                ];
            }
            
            // Delete the entry
            $this->db->where('id', $id);
            $this->db->delete('calendar_timetable');
            
            if ($this->db->affected_rows() > 0) {
                return [
                    'status' => 'success',
                    'message' => 'Timetable entry deleted successfully.'
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Failed to delete timetable entry. Please try again.'
                ];
            }
        } catch (Exception $e) {
            log_message('error', 'Error in delete_timetable_entry: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'An error occurred: ' . $e->getMessage()
            ];
        }
    }
    
    // Get all timetable entries for a specific month and year
    function get_month_timetable($month, $year, $class_id = null, $section_id = null) {
        $this->db->where('month', $month);
        $this->db->where('year', $year);
        
        if ($class_id) {
            $this->db->where('class_id', $class_id);
        }
        
        if ($section_id) {
            $this->db->where('section_id', $section_id);
        }
        
        $this->db->order_by('day_of_week', 'ASC');
        $this->db->order_by('time_slot_start', 'ASC');
        
        return $this->db->get('calendar_timetable')->result_array();
    }
    
    // Get timetable entries for a specific teacher
    function get_teacher_timetable($teacher_id, $month, $year) {
        $this->db->where('teacher_id', $teacher_id);
        $this->db->where('month', $month);
        $this->db->where('year', $year);
        $this->db->order_by('day_of_week', 'ASC');
        $this->db->order_by('time_slot_start', 'ASC');
        
        return $this->db->get('calendar_timetable')->result_array();
    }
    
    // Get a specific timetable entry
    function get_timetable_entry($id) {
        $this->db->where('id', $id);
        return $this->db->get('calendar_timetable')->row_array();
    }
    
    // Check for timetable conflicts
    function check_timetable_conflict($data, $exclude_id = null) {
        // Check if there's a conflict with the same class, section, day, time slot, month, and year
        $this->db->where('class_id', $data['class_id']);
        $this->db->where('section_id', $data['section_id']);
        $this->db->where('day_of_week', $data['day_of_week']);
        $this->db->where('month', $data['month']);
        $this->db->where('year', $data['year']);
        
        // Check if the time slots overlap
        $this->db->where("(
            (time_slot_start <= '{$data['time_slot_start']}' AND time_slot_end > '{$data['time_slot_start']}') OR
            (time_slot_start < '{$data['time_slot_end']}' AND time_slot_end >= '{$data['time_slot_end']}') OR
            (time_slot_start >= '{$data['time_slot_start']}' AND time_slot_end <= '{$data['time_slot_end']}')
        )");
        
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        
        $class_conflict = $this->db->get('calendar_timetable')->num_rows() > 0;
        
        // Check if the teacher is already busy at this time
        if (isset($data['teacher_id']) && $data['teacher_id']) {
            $this->db->where('teacher_id', $data['teacher_id']);
            $this->db->where('day_of_week', $data['day_of_week']);
            $this->db->where('month', $data['month']);
            $this->db->where('year', $data['year']);
            
            // Check if the time slots overlap
            $this->db->where("(
                (time_slot_start <= '{$data['time_slot_start']}' AND time_slot_end > '{$data['time_slot_start']}') OR
                (time_slot_start < '{$data['time_slot_end']}' AND time_slot_end >= '{$data['time_slot_end']}') OR
                (time_slot_start >= '{$data['time_slot_start']}' AND time_slot_end <= '{$data['time_slot_end']}')
            )");
            
            if ($exclude_id) {
                $this->db->where('id !=', $exclude_id);
            }
            
            $teacher_conflict = $this->db->get('calendar_timetable')->num_rows() > 0;
            
            return $class_conflict || $teacher_conflict;
        }
        
        return $class_conflict;
    }
    
    // Get unique time slots for all timetable entries
    function get_all_time_slots() {
        $this->db->select('DISTINCT time_slot_start, time_slot_end');
        $this->db->order_by('time_slot_start', 'ASC');
        
        return $this->db->get('calendar_timetable')->result_array();
    }
    
    // Get classes that have timetable entries
    function get_classes_with_timetable() {
        $this->db->select('DISTINCT(class_id)');
        $this->db->order_by('class_id', 'ASC');
        $classes = $this->db->get('calendar_timetable')->result_array();
        
        $class_names = array();
        foreach ($classes as $class) {
            $class_detail = $this->db->get_where('class', array('class_id' => $class['class_id']))->row_array();
            if ($class_detail) {
                $class_names[] = array(
                    'class_id' => $class['class_id'],
                    'name' => $class_detail['name']
                );
            }
        }
        
        return $class_names;
    }
    
    // Get all sections for a specific class
    function get_sections_by_class($class_id) {
        $this->db->where('class_id', $class_id);
        return $this->db->get('section')->result_array();
    }
} 