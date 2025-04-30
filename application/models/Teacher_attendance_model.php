<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Teacher_attendance_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
    }
    
    // Create the teacher_attendance table if it doesn't exist
    function createTableIfNotExists() {
        if (!$this->db->table_exists('teacher_attendance')) {
            $query = "CREATE TABLE IF NOT EXISTS `teacher_attendance` (
                `attendance_id` int(11) NOT NULL AUTO_INCREMENT,
                `teacher_id` int(11) NOT NULL,
                `status` int(11) NOT NULL DEFAULT '0' COMMENT '0=undefined, 1=present, 2=absent, 3=late',
                `date` date NOT NULL,
                `year` varchar(10) NOT NULL,
                `month` varchar(10) NOT NULL,
                `day` varchar(10) NOT NULL,
                PRIMARY KEY (`attendance_id`),
                KEY `teacher_id` (`teacher_id`),
                KEY `date` (`date`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
            $this->db->query($query);
            return true;
        }
        return false;
    }
    
    // Save teacher attendance for a specific date
    function saveTeacherAttendance() {
        // Ensure table exists
        $this->createTableIfNotExists();
        
        $teacher_ids = $this->input->post('teacher_id');
        $statuses = $this->input->post('status');
        $date = $this->input->post('date');
        
        if (!$teacher_ids || !$statuses || !$date) {
            return false;
        }
        
        // Parse date components
        $year = date('Y', strtotime($date));
        $month = date('m', strtotime($date));
        $day = date('d', strtotime($date));
        
        // First delete existing records for this date
        $this->db->where('date', $date);
        $this->db->delete('teacher_attendance');
        
        // Insert new records
        for ($i = 0; $i < count($teacher_ids); $i++) {
            $data = array(
                'teacher_id' => $teacher_ids[$i],
                'status' => $statuses[$i],
                'date' => $date,
                'year' => $year,
                'month' => $month,
                'day' => $day
            );
            $this->db->insert('teacher_attendance', $data);
        }
        
        return true;
    }
    
    // Get teacher attendance for a specific date
    function getTeacherAttendance($date) {
        // Ensure table exists
        $this->createTableIfNotExists();
        
        // Get all teachers
        $teachers = $this->db->get('teacher')->result_array();
        
        // Check attendance for each teacher
        foreach ($teachers as &$teacher) {
            $this->db->where('teacher_id', $teacher['teacher_id']);
            $this->db->where('date', $date);
            $attendance = $this->db->get('teacher_attendance')->row_array();
            
            if (!empty($attendance)) {
                $teacher['status'] = $attendance['status'];
                $teacher['attendance_id'] = $attendance['attendance_id'];
            } else {
                $teacher['status'] = 0; // No record, set as undefined
            }
        }
        
        return $teachers;
    }
    
    // Get monthly attendance report for all teachers
    function getTeacherAttendanceReport($month, $year) {
        // Ensure table exists
        $this->createTableIfNotExists();
        
        // Validate inputs
        $month = intval($month);
        $year = intval($year);
        
        if ($month < 1 || $month > 12 || $year < 2000 || $year > 2100) {
            log_message('error', 'Invalid month or year provided to getTeacherAttendanceReport: ' . $month . '/' . $year);
            return array();
        }
        
        try {
            // Get all teachers
            $teachers = $this->db->get('teacher')->result_array();
            
            if (empty($teachers)) {
                log_message('info', 'No teachers found for attendance report');
                return array();
            }
            
            // Get number of days in the month
            $number_of_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            
            $result = array();
            
            // For each teacher
            foreach ($teachers as $teacher) {
                if (!isset($teacher['teacher_id']) || empty($teacher['teacher_id'])) {
                    continue; // Skip invalid teacher entries
                }
                
                $teacher_id = $teacher['teacher_id'];
                $teacher_name = isset($teacher['name']) ? $teacher['name'] : 'Unknown Teacher';
                
                $attendance_data = array();
                
                // Check attendance for each day
                for ($d = 1; $d <= $number_of_days; $d++) {
                    $day_formatted = str_pad($d, 2, '0', STR_PAD_LEFT);
                    $date = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-' . $day_formatted;
                    
                    $this->db->where('teacher_id', $teacher_id);
                    $this->db->where('date', $date);
                    $attendance_record = $this->db->get('teacher_attendance')->row_array();
                    
                    if (!empty($attendance_record) && isset($attendance_record['status'])) {
                        $attendance_data[$d] = intval($attendance_record['status']);
                    } else {
                        $attendance_data[$d] = 0; // Not marked
                    }
                }
                
                // Calculate statistics
                $present_count = 0;
                $absent_count = 0;
                $late_count = 0;
                $unmarked_count = 0;
                
                foreach ($attendance_data as $status) {
                    if ($status == 1) $present_count++;
                    else if ($status == 2) $absent_count++;
                    else if ($status == 3) $late_count++;
                    else $unmarked_count++;
                }
                
                $result[$teacher_id] = array(
                    'teacher_id' => $teacher_id,
                    'teacher_name' => $teacher_name,
                    'attendance_data' => $attendance_data,
                    'stats' => array(
                        'present' => $present_count,
                        'absent' => $absent_count,
                        'late' => $late_count,
                        'unmarked' => $unmarked_count,
                        'total_days' => $number_of_days
                    )
                );
            }
            
            return $result;
        } catch (Exception $e) {
            log_message('error', 'Error in getTeacherAttendanceReport: ' . $e->getMessage());
            return array();
        }
    }
    
    // Get attendance statistics for a specific teacher
    function getTeacherAttendanceStats($teacher_id, $month, $year) {
        // Ensure table exists
        $this->createTableIfNotExists();
        
        // Get number of days in the month
        $number_of_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        
        $present = 0;
        $absent = 0;
        $late = 0;
        $unmarked = 0;
        
        // Check attendance for each day
        for ($d = 1; $d <= $number_of_days; $d++) {
            $date = date('Y-m-d', strtotime($year . '-' . $month . '-' . $d));
            
            $this->db->where('teacher_id', $teacher_id);
            $this->db->where('date', $date);
            $result = $this->db->get('teacher_attendance');
            
            if ($result->num_rows() > 0) {
                $status = $result->row()->status;
                if ($status == 1) $present++;
                else if ($status == 2) $absent++;
                else if ($status == 3) $late++;
            } else {
                $unmarked++;
            }
        }
        
        return array(
            'present' => $present,
            'absent' => $absent,
            'late' => $late,
            'unmarked' => $unmarked,
            'total_days' => $number_of_days
        );
    }
} 