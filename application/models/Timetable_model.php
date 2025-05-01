<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Timetable_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
        
        // Create timetable table if it doesn't exist
        if (!$this->db->table_exists('timetable')) {
            $this->create_table_if_not_exists();
        }
    }
    
    // Create the timetable table if it doesn't exist
    function create_table_if_not_exists() {
        $this->db->query("CREATE TABLE IF NOT EXISTS `timetable` (
            `timetable_id` int(11) NOT NULL AUTO_INCREMENT,
            `class_id` int(11) NOT NULL,
            `section_id` int(11) NOT NULL,
            `subject_id` int(11) NOT NULL,
            `teacher_id` int(11) NOT NULL,
            `start_date` date NOT NULL,
            `end_date` date NOT NULL,
            `start_time` time NOT NULL,
            `end_time` time NOT NULL,
            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`timetable_id`),
            KEY `class_id` (`class_id`),
            KEY `section_id` (`section_id`),
            KEY `subject_id` (`subject_id`),
            KEY `teacher_id` (`teacher_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }
    
    // Add a new timetable entry
    function add_timetable($data) {
        // Check for conflicts
        if ($this->check_timetable_conflict($data)) {
            return false;
        }
        
        // Insert the data
        $this->db->insert('timetable', $data);
        return $this->db->insert_id();
    }
    
    // Update a timetable entry
    function update_timetable($timetable_id, $data) {
        // Check for conflicts (excluding the current entry)
        if ($this->check_timetable_conflict($data, $timetable_id)) {
            return false;
        }
        
        $this->db->where('timetable_id', $timetable_id);
        $this->db->update('timetable', $data);
        return true;
    }
    
    // Delete a timetable entry
    function delete_timetable($timetable_id) {
        $this->db->where('timetable_id', $timetable_id);
        $this->db->delete('timetable');
        return true;
    }
    
    // Get all timetable entries
    function get_all_timetables() {
        $this->db->order_by('class_id', 'ASC');
        $this->db->order_by('day', 'ASC');
        $this->db->order_by('starting_time', 'ASC');
        return $this->db->get('timetable')->result_array();
    }
    
    // Get timetable entries for a specific class and section
    function get_timetable_by_class_section($class_id, $section_id) {
        $this->db->where('class_id', $class_id);
        $this->db->where('section_id', $section_id);
        $this->db->order_by('day', 'ASC');
        $this->db->order_by('starting_time', 'ASC');
        return $this->db->get('timetable')->result_array();
    }
    
    // Get timetable entries for a specific teacher
    function get_timetable_by_teacher($teacher_id) {
        $this->db->where('teacher_id', $teacher_id);
        $this->db->order_by('day', 'ASC');
        $this->db->order_by('starting_time', 'ASC');
        return $this->db->get('timetable')->result_array();
    }
    
    // Get a specific timetable entry
    function get_timetable($timetable_id) {
        $this->db->where('timetable_id', $timetable_id);
        return $this->db->get('timetable')->row_array();
    }
        
    // Check for timetable conflicts
    public function check_timetable_conflict($data, $exclude_id = null) {
        // Check for overlapping schedules
        $where = "(
            (start_date <= '{$data['start_date']}' AND end_date >= '{$data['start_date']}') OR
            (start_date <= '{$data['end_date']}' AND end_date >= '{$data['end_date']}') OR
            (start_date >= '{$data['start_date']}' AND end_date <= '{$data['end_date']}')
        ) AND (
            (start_time <= '{$data['start_time']}' AND end_time > '{$data['start_time']}') OR
            (start_time < '{$data['end_time']}' AND end_time >= '{$data['end_time']}') OR
            (start_time >= '{$data['start_time']}' AND end_time <= '{$data['end_time']}')
        )";

        // Check teacher conflicts
        $this->db->where('teacher_id', $data['teacher_id']);
        $this->db->where($where, NULL, FALSE);
        if ($exclude_id) {
            $this->db->where('timetable_id !=', $exclude_id);
        }
        $teacher_conflict = $this->db->get('timetable')->num_rows() > 0;

        // Check class/section conflicts
        $this->db->where('class_id', $data['class_id']);
        $this->db->where('section_id', $data['section_id']);
        $this->db->where($where, NULL, FALSE);
        if ($exclude_id) {
            $this->db->where('timetable_id !=', $exclude_id);
        }
        $class_conflict = $this->db->get('timetable')->num_rows() > 0;

        return $teacher_conflict || $class_conflict;
    }
    
    // Get unique time slots for a class and section
    function get_time_slots_by_class_section($class_id, $section_id) {
        $this->db->select('DISTINCT(starting_time), ending_time');
        $this->db->where('class_id', $class_id);
        $this->db->where('section_id', $section_id);
        $this->db->order_by('starting_time', 'ASC');
        return $this->db->get('timetable')->result_array();
    }
    
    // Get timetable entries for a specific day
    function get_timetable_by_day($day) {
        $this->db->where('day', $day);
        $this->db->order_by('starting_time', 'ASC');
        return $this->db->get('timetable')->result_array();
    }
    
    // Get all days that have timetable entries
    function get_all_timetable_days() {
        $this->db->select('DISTINCT(day)');
        $this->db->order_by('FIELD(day, "monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday")', '', FALSE);
        return $this->db->get('timetable')->result_array();
    }
    
    // Get classes that have timetable entries
    function get_classes_with_timetable() {
        $this->db->select('DISTINCT(class_id)');
        $this->db->order_by('class_id', 'ASC');
        $classes = $this->db->get('timetable')->result_array();
        
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
    
    // Get teachers who have timetable entries
    function get_teachers_with_timetable() {
        $this->db->select('DISTINCT(teacher_id)');
        $this->db->order_by('teacher_id', 'ASC');
        $teachers = $this->db->get('timetable')->result_array();
        
        $teacher_names = array();
        foreach ($teachers as $teacher) {
            $teacher_detail = $this->db->get_where('teacher', array('teacher_id' => $teacher['teacher_id']))->row_array();
            if ($teacher_detail) {
                $teacher_names[] = array(
                    'teacher_id' => $teacher['teacher_id'],
                    'name' => $teacher_detail['name']
                );
            }
        }
        
        return $teacher_names;
    }
    
    public function get_timetable_events($class_id = null) {
        $this->db->select('t.*, c.name as class_name, s.name as section_name, 
                          sub.name as subject_name, tea.name as teacher_name');
        $this->db->from('timetable t');
        $this->db->join('class c', 'c.class_id = t.class_id');
        $this->db->join('section s', 's.section_id = t.section_id');
        $this->db->join('subject sub', 'sub.subject_id = t.subject_id');
        $this->db->join('teacher tea', 'tea.teacher_id = t.teacher_id');
        
        if ($class_id) {
            $this->db->where('t.class_id', $class_id);
        }
        
        return $this->db->get()->result_array();
    }
    
    public function save_timetable($data) {
        // Validate data
        $required_fields = ['class_id', 'section_id', 'subject_id', 'teacher_id', 
                          'start_date', 'end_date', 'start_time', 'end_time'];
        
        foreach ($required_fields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                return false;
            }
        }

        // Check for conflicts
        $conflict_check = [
            'class_id' => $data['class_id'],
            'section_id' => $data['section_id'],
            'teacher_id' => $data['teacher_id'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time']
        ];

        $exclude_id = isset($data['timetable_id']) ? $data['timetable_id'] : null;
        
        if ($this->check_timetable_conflict($conflict_check, $exclude_id)) {
            return false;
        }

        // Update or insert
        if (isset($data['timetable_id'])) {
            $this->db->where('timetable_id', $data['timetable_id']);
            return $this->db->update('timetable', $data);
        } else {
            return $this->db->insert('timetable', $data);
        }
    }
} 