<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// Add fallback if CAL_GREGORIAN constant is not defined
if (!defined('CAL_GREGORIAN')) {
    define('CAL_GREGORIAN', 0);
}

// Add fallback if cal_days_in_month is not available
if (!function_exists('cal_days_in_month')) {
    function cal_days_in_month($calendar, $month, $year) {
        // Ignore $calendar parameter since we don't need it
        return date('t', mktime(0, 0, 0, $month, 1, $year));
    }
}

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
            error_log('Invalid month or year provided to getTeacherAttendanceReport: ' . $month . '/' . $year);
            return array();
        }
        
        try {
            // Get all teachers
            $teachers = $this->db->get('teacher')->result_array();
            
            if (empty($teachers)) {
                error_log('No teachers found for attendance report');
                return array();
            }
            
            error_log('Found ' . count($teachers) . ' teachers for attendance report');
            
            // Get number of days in the month - using the fallback if needed
            $number_of_days = date('t', mktime(0, 0, 0, $month, 1, $year));
            error_log('Number of days in month: ' . $number_of_days);
            
            $result = array();
            
            // For each teacher
            foreach ($teachers as $teacher) {
                if (!isset($teacher['teacher_id']) || empty($teacher['teacher_id'])) {
                    error_log('Skipping teacher with missing ID');
                    continue; // Skip invalid teacher entries
                }
                
                $teacher_id = $teacher['teacher_id'];
                $teacher_name = isset($teacher['name']) ? $teacher['name'] : 'Unknown Teacher';
                
                error_log('Processing teacher: ' . $teacher_id . ' - ' . $teacher_name);
                $attendance_data = array();
                
                // Check attendance for each day
                for ($d = 1; $d <= $number_of_days; $d++) {
                    $day_formatted = str_pad($d, 2, '0', STR_PAD_LEFT);
                    $date = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-' . $day_formatted;
                    
                    try {
                        $this->db->where('teacher_id', $teacher_id);
                        $this->db->where('date', $date);
                        $attendance_record = $this->db->get('teacher_attendance')->row_array();
                        
                        if (!empty($attendance_record) && isset($attendance_record['status'])) {
                            // For the first day, show the real status if marked, otherwise blank
                            $attendance_data[$d] = intval($attendance_record['status']);
                        } else {
                            $attendance_data[$d] = 0; // Not marked
                        }
                    } catch (Exception $e) {
                        error_log('Error getting attendance for teacher ' . $teacher_id . ' on ' . $date . ': ' . $e->getMessage());
                        $attendance_data[$d] = 0; // Default to not marked in case of error
                    }
                }
                
                // Calculate statistics
                $present_count = 0;
                $absent_count = 0;
                $late_count = 0;
                $half_day_count = 0;
                
                foreach ($attendance_data as $status) {
                    if ($status == 1) $present_count++;
                    else if ($status == 2) $absent_count++;
                    else if ($status == 3) $late_count++;
                    else if ($status == 4) $half_day_count++;
                }
                
                $result[] = array(
                    'teacher_id' => $teacher_id,
                    'teacher_name' => $teacher_name,
                    'attendance_data' => $attendance_data,
                    'present_count' => $present_count,
                    'absent_count' => $absent_count,
                    'late_count' => $late_count,
                    'half_day_count' => $half_day_count,
                    'total_days' => $number_of_days
                );
            }
            
            return $result;
            
        } catch (Exception $e) {
            error_log('Error in getTeacherAttendanceReport: ' . $e->getMessage());
            return array();
        }
    }
    
    // Helper function to create sample attendance data if none exists
    function createSampleAttendanceData($month, $year) {
        // Get all teachers
        $teachers = $this->db->get('teacher')->result_array();
        if (empty($teachers)) return;
        
        // Get the first day of the month
        $day = 1;
        $date = date('Y-m-d', strtotime($year . '-' . $month . '-' . $day));
        
        // For each teacher, create a sample attendance record
        foreach ($teachers as $teacher) {
            if (!isset($teacher['teacher_id']) || empty($teacher['teacher_id'])) {
                continue;
            }
            
            // Check if record already exists
            $this->db->where('teacher_id', $teacher['teacher_id']);
            $this->db->where('date', $date);
            $exists = $this->db->get('teacher_attendance')->num_rows() > 0;
            
            if (!$exists) {
                $data = array(
                    'teacher_id' => $teacher['teacher_id'],
                    'status' => 1, // Present
                    'date' => $date,
                    'year' => $year,
                    'month' => $month,
                    'day' => $day
                );
                
                $this->db->insert('teacher_attendance', $data);
                error_log('Created sample attendance record for teacher ID ' . $teacher['teacher_id']);
            }
        }
    }
    
    // Get attendance statistics for a specific teacher
    function getTeacherAttendanceStats($teacher_id, $month, $year) {
        // Ensure table exists
        $this->createTableIfNotExists();
        
        // Get number of days in the month - using the fallback if needed
        $number_of_days = date('t', mktime(0, 0, 0, $month, 1, $year));
        
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