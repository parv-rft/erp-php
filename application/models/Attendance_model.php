<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Attendance_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    /**
     * Get student attendance report for a class, section, month, and year
     * Returns array: [ ['name' => ..., 'attendance' => [status, status, ...]], ... ]
     */
    public function get_student_attendance_report($class_id, $section_id, $month, $year) {
        $students = $this->db->get_where('student', array('class_id' => $class_id, 'section_id' => $section_id))->result_array();
        $total_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $report = array();
        foreach ($students as $student) {
            $attendance_row = array('name' => $student['name'], 'attendance' => array());
            for ($d = 1; $d <= $total_days; $d++) {
                $date = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-' . str_pad($d, 2, '0', STR_PAD_LEFT);
                $att = $this->db->get_where('attendance', array('student_id' => $student['student_id'], 'date' => $date))->row();
                $status = ($att && isset($att->status)) ? intval($att->status) : 0;
                $attendance_row['attendance'][] = $status;
            }
            $report[] = $attendance_row;
        }
        return $report;
    }
} 