<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Timetable_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    // Create the timetable table if it doesn't exist
    function create_table_if_not_exists() {
        if (!$this->db->table_exists('timetable')) {
            $query = "CREATE TABLE IF NOT EXISTS `timetable` (
                `timetable_id` int(11) NOT NULL AUTO_INCREMENT,
                `class_id` int(11) NOT NULL,
                `section_id` int(11) NOT NULL,
                `subject_id` int(11) NOT NULL,
                `teacher_id` int(11) NOT NULL,
                `day` varchar(20) NOT NULL,
                `starting_time` varchar(20) NOT NULL,
                `ending_time` varchar(20) NOT NULL,
                `room_number` varchar(20) DEFAULT NULL,
                PRIMARY KEY (`timetable_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
            $this->db->query($query);
            return true;
        }
        return false;
    }
    
    // Add a new timetable entry
    function add_timetable($data) {
        // Check if the table exists
        $this->create_table_if_not_exists();
        
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
    function check_timetable_conflict($data, $exclude_id = null) {
        // Check for teacher conflicts (same teacher, same day, overlapping time)
        $this->db->where('teacher_id', $data['teacher_id']);
        $this->db->where('day', $data['day']);
        $this->db->where('(
            (starting_time <= "' . $data['starting_time'] . '" AND ending_time >= "' . $data['starting_time'] . '")
            OR
            (starting_time <= "' . $data['ending_time'] . '" AND ending_time >= "' . $data['ending_time'] . '")
            OR
            (starting_time >= "' . $data['starting_time'] . '" AND ending_time <= "' . $data['ending_time'] . '")
        )', NULL, FALSE);
        
        if ($exclude_id) {
            $this->db->where('timetable_id !=', $exclude_id);
        }
        
        $teacher_conflict = $this->db->get('timetable')->num_rows();
        
        // Check for class/section conflicts (same class, same section, same day, overlapping time)
        $this->db->where('class_id', $data['class_id']);
        $this->db->where('section_id', $data['section_id']);
        $this->db->where('day', $data['day']);
        $this->db->where('(
            (starting_time <= "' . $data['starting_time'] . '" AND ending_time >= "' . $data['starting_time'] . '")
            OR
            (starting_time <= "' . $data['ending_time'] . '" AND ending_time >= "' . $data['ending_time'] . '")
            OR
            (starting_time >= "' . $data['starting_time'] . '" AND ending_time <= "' . $data['ending_time'] . '")
        )', NULL, FALSE);
        
        if ($exclude_id) {
            $this->db->where('timetable_id !=', $exclude_id);
        }
        
        $class_conflict = $this->db->get('timetable')->num_rows();
        
        // Check for room conflicts if a room is specified
        $room_conflict = 0;
        if (!empty($data['room_number'])) {
            $this->db->where('room_number', $data['room_number']);
            $this->db->where('day', $data['day']);
            $this->db->where('(
                (starting_time <= "' . $data['starting_time'] . '" AND ending_time >= "' . $data['starting_time'] . '")
                OR
                (starting_time <= "' . $data['ending_time'] . '" AND ending_time >= "' . $data['ending_time'] . '")
                OR
                (starting_time >= "' . $data['starting_time'] . '" AND ending_time <= "' . $data['ending_time'] . '")
            )', NULL, FALSE);
            
            if ($exclude_id) {
                $this->db->where('timetable_id !=', $exclude_id);
            }
            
            $room_conflict = $this->db->get('timetable')->num_rows();
        }
        
        return ($teacher_conflict > 0 || $class_conflict > 0 || $room_conflict > 0);
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
        $this->db->select('calendar_timetable.*, subject.name as subject_name, teacher.name as teacher_name, class.name as class_name, section.name as section_name');
        $this->db->from('calendar_timetable');
        $this->db->join('subject', 'subject.subject_id = calendar_timetable.subject_id');
        $this->db->join('teacher', 'teacher.teacher_id = calendar_timetable.teacher_id');
        $this->db->join('class', 'class.class_id = calendar_timetable.class_id');
        $this->db->join('section', 'section.section_id = calendar_timetable.section_id');
        
        if ($class_id) {
            $this->db->where('calendar_timetable.class_id', $class_id);
        }
        
        $result = $this->db->get()->result_array();
        
        $events = array();
        foreach ($result as $row) {
            $events[] = array(
                'id' => $row['id'],
                'title' => $row['subject_name'] . ' (' . $row['teacher_name'] . ')',
                'start' => $row['date'] . 'T' . $row['start_time'],
                'end' => $row['date'] . 'T' . $row['end_time'],
                'className' => 'bg-info',
                'description' => 'Class: ' . $row['class_name'] . '<br>Section: ' . $row['section_name']
            );
        }
        
        return $events;
    }
} 