<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Teacher_diary_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
    }
    
    // Check if teacher_diary table exists, if not create it
    function create_table_if_not_exists() {
        try {
            // Check if the table exists
            if (!$this->db->table_exists('teacher_diary')) {
                $query = file_get_contents(FCPATH . 'teacher_diary.sql');
                $this->db->query($query);
                return true;
            } else {
                // Table exists, check if the new columns exist
                $fields = $this->db->list_fields('teacher_diary');
                
                // Check if class_id column exists
                if (!in_array('class_id', $fields)) {
                    $this->db->query("ALTER TABLE `teacher_diary` ADD COLUMN `class_id` int(11) DEFAULT NULL AFTER `teacher_id`");
                    log_message('info', 'Added class_id column to teacher_diary table');
                }
                
                // Check if section_id column exists
                if (!in_array('section_id', $fields)) {
                    $this->db->query("ALTER TABLE `teacher_diary` ADD COLUMN `section_id` int(11) DEFAULT NULL AFTER `class_id`");
                    log_message('info', 'Added section_id column to teacher_diary table');
                }
            }
            return false;
        } catch (Exception $e) {
            log_message('error', 'Failed to create/update teacher_diary table: ' . $e->getMessage());
            throw new Exception('Database error: Could not create/update teacher diary table: ' . $e->getMessage());
        }
    }
    
    // Create a new diary entry
    function create_diary($data) {
        try {
            // Ensure the table exists with the correct columns
            $this->create_table_if_not_exists();
            
            // Validate required fields
            if (!isset($data['teacher_id']) || empty($data['teacher_id'])) {
                throw new Exception('Teacher ID is required');
            }
            
            if (!isset($data['title']) || empty($data['title'])) {
                throw new Exception('Title is required');
            }
            
            if (!isset($data['description']) || empty($data['description'])) {
                throw new Exception('Description is required');
            }
            
            if (!isset($data['date']) || empty($data['date'])) {
                throw new Exception('Date is required');
            }
            
            $insert_data = array(
                'teacher_id' => $data['teacher_id'],
                'title' => $data['title'],
                'description' => $data['description'],
                'date' => $data['date']
            );
            
            // Only include these fields if the columns actually exist
            $fields = $this->db->list_fields('teacher_diary');
            
            if (in_array('class_id', $fields) && isset($data['class_id']) && !empty($data['class_id'])) {
                // Verify that the class exists
                $this->db->where('class_id', $data['class_id']);
                $class = $this->db->get('class')->row_array();
                if (!$class) {
                    throw new Exception('Selected class does not exist');
                }
                $insert_data['class_id'] = $data['class_id'];
            }
            
            if (in_array('section_id', $fields) && isset($data['section_id']) && !empty($data['section_id'])) {
                // Verify that the section exists
                $this->db->where('section_id', $data['section_id']);
                $section = $this->db->get('section')->row_array();
                if (!$section) {
                    throw new Exception('Selected section does not exist');
                }
                $insert_data['section_id'] = $data['section_id'];
            }
            
            if (isset($data['time'])) {
                $insert_data['time'] = $data['time'];
            }
            
            if (isset($data['attachment'])) {
                $insert_data['attachment'] = $data['attachment'];
            }
            
            $this->db->insert('teacher_diary', $insert_data);
            
            // Check if insert was successful
            if ($this->db->affected_rows() <= 0) {
                throw new Exception('Database insert failed: ' . $this->db->error()['message']);
            }
            
            return $this->db->insert_id();
        } catch (Exception $e) {
            log_message('error', 'Failed to create diary entry: ' . $e->getMessage());
            throw new Exception('Database error: Could not create diary entry. ' . $e->getMessage());
        }
    }
    
    // Get all diary entries for a specific teacher
    function get_diaries_by_teacher($teacher_id) {
        try {
            $this->create_table_if_not_exists();
            $this->db->where('teacher_id', $teacher_id);
            $this->db->order_by('date', 'DESC');
            $this->db->order_by('created_at', 'DESC');
            return $this->db->get('teacher_diary')->result_array();
        } catch (Exception $e) {
            log_message('error', 'Failed to get teacher diaries: ' . $e->getMessage());
            return array(); // Return empty array instead of throwing to display "No diaries" message
        }
    }
    
    // Get all diary entries for admin view
    function get_all_diaries() {
        try {
            $this->create_table_if_not_exists();
            $this->db->select('teacher_diary.*, teacher.name as teacher_name');
            $this->db->from('teacher_diary');
            $this->db->join('teacher', 'teacher.teacher_id = teacher_diary.teacher_id');
            $this->db->order_by('date', 'DESC');
            $this->db->order_by('created_at', 'DESC');
            return $this->db->get()->result_array();
        } catch (Exception $e) {
            log_message('error', 'Failed to get all diaries: ' . $e->getMessage());
            return array(); // Return empty array instead of throwing to display "No diaries" message
        }
    }
    
    // Get a specific diary entry
    function get_diary($diary_id) {
        try {
            $this->create_table_if_not_exists();
            $this->db->where('diary_id', $diary_id);
            return $this->db->get('teacher_diary')->row_array();
        } catch (Exception $e) {
            log_message('error', 'Failed to get diary: ' . $e->getMessage());
            throw new Exception('Database error: Could not retrieve diary entry.');
        }
    }
    
    // Update a diary entry
    function update_diary($diary_id, $data) {
        try {
            // Ensure table structure is updated
            $this->create_table_if_not_exists();
            
            $update_data = array(
                'title' => $data['title'],
                'description' => $data['description'],
                'date' => $data['date']
            );
            
            // Only include these fields if the columns actually exist
            $fields = $this->db->list_fields('teacher_diary');
            
            if (in_array('class_id', $fields) && isset($data['class_id']) && !empty($data['class_id'])) {
                $update_data['class_id'] = $data['class_id'];
            }
            
            if (in_array('section_id', $fields) && isset($data['section_id']) && !empty($data['section_id'])) {
                $update_data['section_id'] = $data['section_id'];
            }
            
            if (isset($data['time'])) {
                $update_data['time'] = $data['time'];
            }
            
            if (isset($data['attachment'])) {
                $update_data['attachment'] = $data['attachment'];
            }
            
            $this->db->where('diary_id', $diary_id);
            $this->db->update('teacher_diary', $update_data);
            return $this->db->affected_rows();
        } catch (Exception $e) {
            log_message('error', 'Failed to update diary: ' . $e->getMessage());
            throw new Exception('Database error: Could not update diary entry. ' . $e->getMessage());
        }
    }
    
    // Delete a diary entry
    function delete_diary($diary_id) {
        try {
            $this->db->where('diary_id', $diary_id);
            $this->db->delete('teacher_diary');
            return $this->db->affected_rows();
        } catch (Exception $e) {
            log_message('error', 'Failed to delete diary: ' . $e->getMessage());
            throw new Exception('Database error: Could not delete diary entry.');
        }
    }
    
    // Check if a diary entry belongs to a teacher
    function is_diary_owner($diary_id, $teacher_id) {
        try {
            $this->db->where('diary_id', $diary_id);
            $this->db->where('teacher_id', $teacher_id);
            return ($this->db->get('teacher_diary')->num_rows() > 0);
        } catch (Exception $e) {
            log_message('error', 'Failed to check diary ownership: ' . $e->getMessage());
            throw new Exception('Database error: Could not verify diary ownership.');
        }
    }
    
    // Get class name by class_id
    function get_class_name($class_id) {
        try {
            if (empty($class_id)) {
                return '';
            }
            
            $this->db->where('class_id', $class_id);
            $class = $this->db->get('class')->row_array();
            
            return $class ? $class['name'] : '';
        } catch (Exception $e) {
            log_message('error', 'Failed to get class name: ' . $e->getMessage());
            return '';
        }
    }
    
    // Get section name by section_id
    function get_section_name($section_id) {
        try {
            if (empty($section_id)) {
                return '';
            }
            
            $this->db->where('section_id', $section_id);
            $section = $this->db->get('section')->row_array();
            
            return $section ? $section['name'] : '';
        } catch (Exception $e) {
            log_message('error', 'Failed to get section name: ' . $e->getMessage());
            return '';
        }
    }
    
    // Get all classes
    function get_all_classes() {
        try {
            return $this->db->get('class')->result_array();
        } catch (Exception $e) {
            log_message('error', 'Failed to get classes: ' . $e->getMessage());
            return array();
        }
    }
    
    // Get sections by class_id
    function get_sections_by_class($class_id) {
        try {
            $this->db->where('class_id', $class_id);
            return $this->db->get('section')->result_array();
        } catch (Exception $e) {
            log_message('error', 'Failed to get sections: ' . $e->getMessage());
            return array();
        }
    }
} 