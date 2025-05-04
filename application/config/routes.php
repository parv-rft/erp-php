<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'login';
$route['404_override'] = 'errors/error_404';
$route['translate_uri_dashes'] = FALSE;

$route['teacher/my_diaries'] = 'teacher/my_diaries';
$route['teacher/my_diaries/create'] = 'teacher/my_diaries/create';
$route['teacher/my_diaries/update/(:num)'] = 'teacher/my_diaries/update/$1';
$route['teacher/my_diaries/delete/(:num)'] = 'teacher/my_diaries/delete/$1';
$route['teacher/view_diary/(:num)'] = 'teacher/view_diary/$1';
$route['teacher/edit_diary/(:num)'] = 'teacher/edit_diary/$1';
$route['teacher/download_diary_attachment/(:num)'] = 'teacher/download_diary_attachment/$1';
$route['admin/teacher_diaries'] = 'admin/teacher_diaries';
$route['admin/view_teacher_diary/(:num)'] = 'admin/view_teacher_diary/$1';

// Timetable routes
$route['teacher/timetable'] = 'teacher/class_timetable';
$route['teacher/timetable/view/(:num)'] = 'teacher/class_timetable/view/$1';
$route['student/timetable'] = 'student/timetable';

// Calendar Timetable Routes
$route['admin/calendar_timetable'] = 'admin/calendar_timetable';
$route['admin/calendar_timetable/(:num)'] = 'admin/calendar_timetable/$1';
$route['admin/calendar_timetable/(:num)/(:num)'] = 'admin/calendar_timetable/$1/$2';

$route['admin/save_timetable_ajax'] = 'admin/save_timetable_ajax';

// Add calendar timetable routes
$route['admin/calendar_timetable'] = 'admin/calendar_timetable';
$route['admin/get_calendar_timetable_data'] = 'admin/get_calendar_timetable_data';
$route['admin/save_calendar_timetable_entry'] = 'admin/save_calendar_timetable_entry';
$route['admin/delete_calendar_timetable_entry'] = 'admin/delete_calendar_timetable_entry';

// Teacher calendar timetable routes
$route['teacher/calendar_timetable'] = 'teacher/calendar_timetable';
$route['teacher/get_teacher_timetable_data'] = 'teacher/get_teacher_timetable_data';
$route['teacher/edit_calendar_timetable_entry'] = 'teacher/edit_calendar_timetable_entry';
$route['teacher/delete_calendar_timetable_entry'] = 'teacher/delete_calendar_timetable_entry';

// Student calendar timetable routes
$route['student/calendar_timetable'] = 'student/calendar_timetable';
$route['student/get_class_timetable_data'] = 'student/get_class_timetable_data';

// Teacher routes
$route['teacher/my_timetable'] = 'teacher/my_timetable';
$route['teacher/get_my_timetable_data'] = 'teacher/get_my_timetable_data';

// Common routes for both
$route['get_sections/(:num)'] = 'admin/get_sections/$1';
$route['get_subjects/(:num)'] = 'admin/get_subjects/$1';

// Teacher attendance routes
$route['admin/teacher_attendance'] = 'admin/teacher_attendance';
$route['admin/teacher_attendance/attendance_selector'] = 'admin/teacher_attendance/attendance_selector';
$route['admin/teacher_attendance/take_attendance'] = 'admin/teacher_attendance/take_attendance';
$route['admin/teacher_attendance_view/(:any)'] = 'admin/teacher_attendance_view/$1';
$route['admin/teacher_attendance_report'] = 'admin/teacher_attendance_report';
$route['admin/teacher_attendance_report/generate'] = 'admin/teacher_attendance_report/generate';
$route['admin/teacher_attendance_report_view/(:num)/(:num)/(:any)'] = 'admin/teacher_attendance_report_view/$1/$2/$3';
