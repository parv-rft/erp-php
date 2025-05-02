<?php defined('BASEPATH') OR exit('No direct script access allowed');

// Add proper class inheritance at the top of the file
class Admin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        // Load required models and libraries
        $this->load->database();
        $this->load->library(array('session', 'role_based_access'));
        $this->load->helper(array('url', 'form'));
        
        // Load all required models
        $this->load->model(array(
            'vacancy_model',
            'application_model',
            'leave_model',
            'admin_model',
            'teacher_model',
            'student_model',
            'timetable_model',
            'class_model',
            'section_model',
            'exam_model',
            'library_model',
            'event_model',
            'language_model',
            'crud_model',
            'sms_model',
            'alumni_model',
            'dormitory_model',
            'academic_model',
            'student_payment_model',
            'award_model',
            'payroll_model',
            'calendar_timetable_model'
        ));
        
        // Check admin login status
        if ($this->session->userdata('admin_login') != 1) {
            redirect(base_url('login'));
        }
        
        // Check role access
        $this->role_based_access->check_access('admin');
        
        // Ensure timetable table exists with proper structure
        $this->ensure_timetable_table();
    }
    
    // Function to ensure timetable table exists with correct structure
    private function ensure_timetable_table() {
        if (!$this->db->table_exists('timetable')) {
            $this->db->query("CREATE TABLE IF NOT EXISTS `timetable` (
                `timetable_id` int(11) NOT NULL AUTO_INCREMENT,
                `class_id` int(11) NOT NULL,
                `section_id` int(11) NOT NULL,
                `subject_id` int(11) NOT NULL,
                `teacher_id` int(11) NOT NULL,
                `day` varchar(10) DEFAULT NULL,
                `start_date` date NOT NULL,
                `end_date` date NOT NULL,
                `start_time` time NOT NULL,
                `end_time` time NOT NULL,
                `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`timetable_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
            
            // Log table creation
            error_log('Created timetable table with proper structure');
        } else {
            // Check if columns exist and add them if needed
            $columns = $this->db->list_fields('timetable');
            $missing_columns = array();
            
            $expected_columns = array(
                'start_date', 'end_date', 'start_time', 'end_time'
            );
            
            foreach ($expected_columns as $column) {
                if (!in_array($column, $columns)) {
                    $missing_columns[] = $column;
                }
            }
            
            // Add missing columns if any
            if (!empty($missing_columns)) {
                if (in_array('start_date', $missing_columns)) {
                    $this->db->query("ALTER TABLE `timetable` ADD COLUMN `start_date` date NOT NULL AFTER `day`");
                }
                
                if (in_array('end_date', $missing_columns)) {
                    $this->db->query("ALTER TABLE `timetable` ADD COLUMN `end_date` date NOT NULL AFTER `start_date`");
                }
                
                if (in_array('start_time', $missing_columns)) {
                    $this->db->query("ALTER TABLE `timetable` ADD COLUMN `start_time` time NOT NULL AFTER `end_date`");
                }
                
                if (in_array('end_time', $missing_columns)) {
                    $this->db->query("ALTER TABLE `timetable` ADD COLUMN `end_time` time NOT NULL AFTER `start_time`");
                }
                
                // Log column additions
                error_log('Added missing columns to timetable table: ' . implode(', ', $missing_columns));
            }
        }
    }

    /**default functin, redirects to login page if no admin logged in yet***/
    public function index() 
	{
    if ($this->session->userdata('admin_login') != 1) redirect(base_url() . 'login', 'refresh');
    if ($this->session->userdata('admin_login') == 1) redirect(base_url() . 'admin/dashboard', 'refresh');
    }
	  /************* / default functin, redirects to login page if no admin logged in yet***/

    /*Admin dashboard code to redirect to admin page if successfull login** */
    function dashboard() {
        if ($this->session->userdata('admin_login') != 1) redirect(base_url(), 'refresh');
       	$page_data['page_name'] = 'dashboard';
        $page_data['page_title'] = get_phrase('admin_dashboard');
        $this->load->view('backend/index', $page_data);
    }
	/******************* / Admin dashboard code to redirect to admin page if successfull login** */


    function manage_profile($param1 = null, $param2 = null, $param3 = null){
    if ($this->session->userdata('admin_login') != 1) redirect(base_url(), 'refresh');
    if ($param1 == 'update') {


        $data['name']   =   $this->input->post('name');
        $data['email']  =   $this->input->post('email');

        $this->db->where('admin_id', $this->session->userdata('admin_id'));
        $this->db->update('admin', $data);
        move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/admin_image/' . $this->session->userdata('admin_id') . '.jpg');
        $this->session->set_flashdata('flash_message', get_phrase('Info Updated'));
        redirect(base_url() . 'admin/manage_profile', 'refresh');
       
    }

    if ($param1 == 'change_password') {
        $data['new_password']           =   sha1($this->input->post('new_password'));
        $data['confirm_new_password']   =   sha1($this->input->post('confirm_new_password'));

        if ($data['new_password'] == $data['confirm_new_password']) {
           
           $this->db->where('admin_id', $this->session->userdata('admin_id'));
           $this->db->update('admin', array('password' => $data['new_password']));
           $this->session->set_flashdata('flash_message', get_phrase('Password Changed'));
        }

        else{
            $this->session->set_flashdata('error_message', get_phrase('Type the same password'));
        }
        redirect(base_url() . 'admin/manage_profile', 'refresh');
    }

        $page_data['page_name']     = 'manage_profile';
        $page_data['page_title']    = get_phrase('Manage Profile');
        $page_data['edit_profile']  = $this->db->get_where('admin', array('admin_id' => $this->session->userdata('admin_id')))->result_array();
        $this->load->view('backend/index', $page_data);
    }


    function enquiry_category($param1 = null, $param2 = null, $param3 = null){

    if($param1 == 'insert'){
   
        $this->crud_model->enquiry_category();

        $this->session->set_flashdata('flash_message', get_phrase('Data saved successfully'));
        redirect(base_url(). 'admin/enquiry_category', 'refresh');
    }

    if($param1 == 'update'){

       $this->crud_model->update_category($param2);


        $this->session->set_flashdata('flash_message', get_phrase('Data updated successfully'));
        redirect(base_url(). 'admin/enquiry_category', 'refresh');

        }

    if($param1 == 'delete'){

       $this->crud_model->delete_category($param2);
        $this->session->set_flashdata('flash_message', get_phrase('Data deleted successfully'));
        redirect(base_url(). 'admin/enquiry_category', 'refresh');

        }

        $page_data['page_name']     = 'enquiry_category';
        $page_data['page_title']    = get_phrase('Manage Category');
        $page_data['enquiry_category']  = $this->db->get('enquiry_category')->result_array();
        $this->load->view('backend/index', $page_data);

    }


    function list_enquiry ($param1 = null, $param2 = null, $param3 = null){


        if($param1 == 'delete')
        {
            $this->crud_model->delete_enquiry($param2);
            $this->session->set_flashdata('flash_message', get_phrase('Data deleted successfully'));
            redirect(base_url(). 'admin/list_enquiry', 'refresh');
    
        }

        $page_data['page_name']     = 'list_enquiry';
        $page_data['page_title']    = get_phrase('All Enquiries');
        $page_data['select_enquiry']  = $this->db->get('enquiry')->result_array();
        $this->load->view('backend/index', $page_data);

    }



    function club ($param1 = null, $param2 = null, $param3 = null){

        if($param1 == 'insert'){
            $this->crud_model->insert_club();
            $this->session->set_flashdata('flash_message', get_phrase('Data saved successfully'));
            redirect(base_url(). 'admin/club', 'refresh');
        }

        if($param1 == 'update'){
            $this->crud_model->update_club($param2);
            $this->session->set_flashdata('flash_message', get_phrase('Data updated successfully'));
            redirect(base_url(). 'admin/club', 'refresh');
        }


        if($param1 == 'delete'){
            $this->crud_model->delete_club($param2);
            $this->session->set_flashdata('flash_message', get_phrase('Data deleted successfully'));
            redirect(base_url(). 'admin/club', 'refresh');
    
            }


        $page_data['page_name']     = 'club';
        $page_data['page_title']    = get_phrase('Manage Club');
        $page_data['select_club']  = $this->db->get('club')->result_array();
        $this->load->view('backend/index', $page_data);

    }


    function circular($param1 = null, $param2 = null, $param3 = null){

        if ($param1 == 'insert'){

            $this->crud_model->insert_circular();
            $this->session->set_flashdata('flash_message', get_phrase('Data successfully saved'));
            redirect(base_url(). 'admin/circular', 'refresh');
        }


        if($param1 == 'update'){

            $this->crud_model->update_circular($param2);
            $this->session->set_flashdata('flash_message', get_phrase('Data successfully updated'));
            redirect(base_url(). 'admin/circular', 'refresh');

        }


        if($param1 == 'delete'){
            $this->crud_model->delete_circular($param2);
            $this->session->set_flashdata('flash_message', get_phrase('Data successfully deleted'));
            redirect(base_url(). 'admin/circular', 'refresh');


        }

        $page_data['page_name']         = 'circular';
        $page_data['page_title']        = get_phrase('Manage Circular');
        $page_data['select_circular']   = $this->db->get('circular')->result_array();
        $this->load->view('backend/index', $page_data);

    }


    function parent($param1 = null, $param2 = null, $param3 = null){

        if ($param1 == 'insert'){

            $this->crud_model->insert_parent();
            $this->session->set_flashdata('flash_message', get_phrase('Data successfully saved'));
            redirect(base_url(). 'admin/parent', 'refresh');
        }


        if($param1 == 'update'){

            $this->crud_model->update_parent($param2);
            $this->session->set_flashdata('flash_message', get_phrase('Data successfully updated'));
            redirect(base_url(). 'admin/parent', 'refresh');

        }

        if($param1 == 'delete'){
            $this->crud_model->delete_parent($param2);
            $this->session->set_flashdata('flash_message', get_phrase('Data successfully deleted'));
            redirect(base_url(). 'admin/parent', 'refresh');

        }

        $page_data['page_name']         = 'parent';
        $page_data['page_title']        = get_phrase('Manage Parent');
        $page_data['select_parent']   = $this->db->get('parent')->result_array();
        $this->load->view('backend/index', $page_data);
    }


    function librarian($param1 = null, $param2 = null, $param3 = null){

        if ($param1 == 'insert'){

            $this->crud_model->insert_librarian();

            $this->session->set_flashdata('flash_message', get_phrase('Data successfully saved'));
            redirect(base_url(). 'admin/librarian', 'refresh');
        }


        if($param1 == 'update'){

            $this->crud_model->update_librarian($param2);
            $this->session->set_flashdata('flash_message', get_phrase('Data successfully updated'));
            redirect(base_url(). 'admin/librarian', 'refresh');

        }

        if($param1 == 'delete'){
            $this->crud_model->delete_librarian($param2);
            $this->session->set_flashdata('flash_message', get_phrase('Data successfully deleted'));
            redirect(base_url(). 'admin/librarian', 'refresh');

        }

        $page_data['page_name']         = 'librarian';
        $page_data['page_title']        = get_phrase('Manage Librarian');
        $page_data['select_librarian']   = $this->db->get('librarian')->result_array();
        $this->load->view('backend/index', $page_data);
    }

  

    function accountant($param1 = null, $param2 = null, $param3 = null){

        if ($param1 == 'insert'){

            $this->crud_model->insert_accountant();

            $this->session->set_flashdata('flash_message', get_phrase('Data successfully saved'));
            redirect(base_url(). 'admin/accountant', 'refresh');
        }


        if($param1 == 'update'){

            $this->crud_model->update_accountant($param2);
            $this->session->set_flashdata('flash_message', get_phrase('Data successfully updated'));
            redirect(base_url(). 'admin/accountant', 'refresh');

        }

        if($param1 == 'delete'){
            $this->crud_model->delete_accountant($param2);
            $this->session->set_flashdata('flash_message', get_phrase('Data successfully deleted'));
            redirect(base_url(). 'admin/accountant', 'refresh');

        }

        $page_data['page_name']         = 'accountant';
        $page_data['page_title']        = get_phrase('Manage Accountant');
        $page_data['select_accountant']   = $this->db->get('accountant')->result_array();
        $this->load->view('backend/index', $page_data);
    }




    function hostel($param1 = null, $param2 = null, $param3 = null){

        if ($param1 == 'insert'){

            $this->crud_model->insert_hostel();

            $this->session->set_flashdata('flash_message', get_phrase('Data successfully saved'));
            redirect(base_url(). 'admin/hostel', 'refresh');
        }


        if($param1 == 'update'){

            $this->crud_model->update_hostel($param2);
            $this->session->set_flashdata('flash_message', get_phrase('Data successfully updated'));
            redirect(base_url(). 'admin/hostel', 'refresh');

        }

        if($param1 == 'delete'){
            $this->crud_model->delete_hostel($param2);
            $this->session->set_flashdata('flash_message', get_phrase('Data successfully deleted'));
            redirect(base_url(). 'admin/hostel', 'refresh');

        }

        $page_data['page_name']         = 'hostel';
        $page_data['page_title']        = get_phrase('Manage Hostel');
        $page_data['select_hostel']     = $this->db->get('hostel')->result_array();
        $this->load->view('backend/index', $page_data);
    }





    function hrm($param1 = null, $param2 = null, $param3 = null){

        if ($param1 == 'insert'){

            $this->crud_model->insert_hrm();

            $this->session->set_flashdata('flash_message', get_phrase('Data successfully saved'));
            redirect(base_url(). 'admin/hrm', 'refresh');
        }


        if($param1 == 'update'){

            $this->crud_model->update_hrm($param2);
            $this->session->set_flashdata('flash_message', get_phrase('Data successfully updated'));
            redirect(base_url(). 'admin/hrm', 'refresh');

        }

        if($param1 == 'delete'){
            $this->crud_model->delete_hrm($param2);
            $this->session->set_flashdata('flash_message', get_phrase('Data successfully deleted'));
            redirect(base_url(). 'admin/hrm', 'refresh');

        }

        $page_data['page_name']         = 'hrm';
        $page_data['page_title']        = get_phrase('Manage HRM');
        $page_data['select_hrm']        = $this->db->get('hrm')->result_array();
        $this->load->view('backend/index', $page_data);
    }




    function alumni($param1 = null, $param2 = null, $param3 = null){

        if ($param1 == 'insert'){

            $this->alumni_model->insert_alumni();

            $this->session->set_flashdata('flash_message', get_phrase('Data successfully saved'));
            redirect(base_url(). 'admin/alumni', 'refresh');
        }


        if($param1 == 'update'){

            $this->alumni_model->update_alumni($param2);
            $this->session->set_flashdata('flash_message', get_phrase('Data successfully updated'));
            redirect(base_url(). 'admin/alumni', 'refresh');

        }

        if($param1 == 'delete'){
        $this->alumni_model->delete_alumni($param2);
        $this->session->set_flashdata('flash_message', get_phrase('Data successfully deleted'));
        redirect(base_url(). 'admin/alumni', 'refresh');

        }

        $page_data['page_name']         = 'alumni';
        $page_data['page_title']        = get_phrase('Manage Alumni');
        $page_data['select_alumni']        = $this->db->get('alumni')->result_array();
        $this->load->view('backend/index', $page_data);
    }


    function teacher ($param1 = null, $param2 = null, $param3 = null){

        if($param1 == 'insert'){
            $this->teacher_model->insetTeacherFunction();
            $this->session->set_flashdata('flash_message', get_phrase('Data saved successfully'));
            redirect(base_url(). 'admin/teacher', 'refresh');
        }

        if($param1 == 'update'){
            $this->teacher_model->updateTeacherFunction($param2);
            $this->session->set_flashdata('flash_message', get_phrase('Data updated successfully'));
            redirect(base_url(). 'admin/teacher', 'refresh');
        }


        if($param1 == 'delete'){
            $this->teacher_model->deleteTeacherFunction($param2);
            $this->session->set_flashdata('flash_message', get_phrase('Data deleted successfully'));
            redirect(base_url(). 'admin/teacher', 'refresh');
    
        }

        $page_data['page_name']     = 'teacher';
        $page_data['page_title']    = get_phrase('Manage Teacher');
        $page_data['select_teacher']  = $this->db->get('teacher')->result_array();
        $this->load->view('backend/index', $page_data);

    }

    function get_designation($department_id = null){

        $designation = $this->db->get_where('designation', array('department_id' => $department_id))->result_array();
        foreach($designation as $key => $row)
        echo '<option value="'.$row['designation_id'].'">' . $row['name'] . '</option>';
    }

    /***********  The function manages vacancy   ***********************/
    function vacancy ($param1 = null, $param2 = null, $param3 = null){

        if($param1 == 'insert'){
            $this->vacancy_model->insetVacancyFunction();
            $this->session->set_flashdata('flash_message', get_phrase('Data saved successfully'));
            redirect(base_url(). 'admin/vacancy', 'refresh');
        }

        if($param1 == 'update'){
            $this->vacancy_model->updateVacancyFunction($param2);
            $this->session->set_flashdata('flash_message', get_phrase('Data updated successfully'));
            redirect(base_url(). 'admin/vacancy', 'refresh');
        }


        if($param1 == 'delete'){
            $this->vacancy_model->deleteVacancyFunction($param2);
            $this->session->set_flashdata('flash_message', get_phrase('Data deleted successfully'));
            redirect(base_url(). 'admin/vacancy', 'refresh');
    
        }

        $page_data['page_name']     = 'vacancy';
        $page_data['page_title']    = get_phrase('Manage Vacancy');
        $page_data['select_vacancy']  = $this->db->get('vacancy')->result_array();
        $this->load->view('backend/index', $page_data);

    }


    /***********  The function manages job applicant   ***********************/
    function application ($param1 = 'applied', $param2 = null, $param3 = null){

        if($param1 == 'insert'){
            $this->application_model->insertApplicantFunction();
            $this->session->set_flashdata('flash_message', get_phrase('Data saved successfully'));
            redirect(base_url(). 'admin/application', 'refresh');
        }

        if($param1 == 'update'){
            $this->application_model->updateApplicantFunction($param2);
            $this->session->set_flashdata('flash_message', get_phrase('Data updated successfully'));
            redirect(base_url(). 'admin/application', 'refresh');
        }


        if($param1 == 'delete'){
            $this->application_model->deleteApplicantFunction($param2);
            $this->session->set_flashdata('flash_message', get_phrase('Data deleted successfully'));
            redirect(base_url(). 'admin/application', 'refresh');
    
        }

        if($param1 != 'applied' && $param1 != 'on_review' && $param1 != 'interviewed' && $param1 != 'offered' && $param1 != 'hired' && $param1 != 'declined')
        $param1 ='applied';

        
        
        $page_data['status']        = $param1;
        $page_data['page_name']     = 'application';
        $page_data['page_title']    = get_phrase('Job Applicant');
        $this->load->view('backend/index', $page_data);

    }


    /***********  The function manages Leave  ***********************/
    function leave ($param1 = null, $param2 = null, $param3 = null){

        if($param1 == 'update'){
            $this->leave_model->updateLeaveFunction($param2);
            $this->session->set_flashdata('flash_message', get_phrase('Data updated successfully'));
            redirect(base_url(). 'admin/leave', 'refresh');
        }


        if($param1 == 'delete'){
            $this->leave_model->deleteLeaveFunction($param2);
            $this->session->set_flashdata('flash_message', get_phrase('Data deleted successfully'));
            redirect(base_url(). 'admin/leave', 'refresh');
    
        }
        
        $page_data['page_name']     = 'leave';
        $page_data['page_title']    = get_phrase('Manage Leave');
        $this->load->view('backend/index', $page_data);

    }


    /***********  The function manages Awards  ***********************/
    function award ($param1 = null, $param2 = null, $param3 = null){

        if($param1 == 'create'){
            $this->award_model->createAwardFunction();
            $this->session->set_flashdata('flash_message', get_phrase('Data updated successfully'));
            redirect(base_url(). 'admin/award', 'refresh');
        }

        if($param1 == 'update'){
            $this->award_model->updateAwardFunction($param2);
            $this->session->set_flashdata('flash_message', get_phrase('Data updated successfully'));
            redirect(base_url(). 'admin/award', 'refresh');
        }


        if($param1 == 'delete'){
            $this->award_model->deleteAwardFunction($param2);
            $this->session->set_flashdata('flash_message', get_phrase('Data deleted successfully'));
            redirect(base_url(). 'admin/award', 'refresh');
    
        }

        $page_data['page_name']     = 'award';
        $page_data['page_title']    = get_phrase('Manage Award');
        $this->load->view('backend/index', $page_data);

    }

    function payroll(){
        
        $page_data['page_name']     = 'payroll_add';
        $page_data['page_title']    = get_phrase('Create Payslip');
        $this->load->view('backend/index', $page_data);

    }

    function get_employees($department_id = null)
    {
        $employees = $this->db->get_where('teacher', array('department_id' => $department_id))->result_array();
        foreach($employees as $key => $employees)
            echo '<option value="' . $employees['teacher_id'] . '">' . $employees['name'] . '</option>';
    }

    function payroll_selector()
    {
        $department_id  = $this->input->post('department_id');
        $employee_id    = $this->input->post('employee_id');
        $month          = $this->input->post('month');
        $year           = $this->input->post('year');
        
        redirect(base_url() . 'admin/payroll_view/' . $department_id. '/' . $employee_id . '/' . $month . '/' . $year, 'refresh');
    }
    
    function payroll_view($department_id = null, $employee_id = null, $month = null, $year = null)
    {
        $page_data['department_id'] = $department_id;
        $page_data['employee_id']   = $employee_id;
        $page_data['month']         = $month;
        $page_data['year']          = $year;
        $page_data['page_name']     = 'payroll_add_view';
        $page_data['page_title']    = get_phrase('Create Payslip');
        $this->load->view('backend/index', $page_data);
    }


    function create_payroll(){

        $this->payroll_model->insertPayrollFunction();
        $this->session->set_flashdata('flash_message', get_phrase('Data saved successfully'));
        redirect(base_url(). 'admin/payroll_list/filter2/'. $this->input->post('month').'/'. $this->input->post('year'), 'refresh');
    }


    /***********  The function manages Payroll List  ***********************/
    function payroll_list ($param1 = null, $param2 = null, $param3 = null, $param4 = null){

        if($param1 == 'mark_paid'){
            
            $data['status'] =  1;
            $this->db->update('payroll', $data, array('payroll_id' => $param2));

            $this->session->set_flashdata('flash_message', get_phrase('Data updated successfully'));
            redirect(base_url(). 'admin/payroll_list/filter2/'. $param3.'/'. $param4, 'refresh');
        }

        if($param1 == 'filter'){
            $page_data['month'] = $this->input->post('month');
            $page_data['year'] = $this->input->post('year');
        }
        else{
            $page_data['month'] = date('n');
            $page_data['year'] = date('Y');
        }

        if($param1 == 'filter2'){
            
            $page_data['month'] = $param2;
            $page_data['year'] = $param3;
        }


        $page_data['page_name']     = 'payroll_list';
        $page_data['page_title']    = get_phrase('List Payroll');
        $this->load->view('backend/index', $page_data);

    }

    /***********  The function manages Class Information  ***********************/
      function classes ($param1 = null, $param2 = null, $param3 = null){

        if($param1 == 'create'){
            $this->class_model->createClassFunction();
            $this->session->set_flashdata('flash_message', get_phrase('Data updated successfully'));
            redirect(base_url(). 'admin/classes', 'refresh');
        }

        if($param1 == 'update'){
            $this->class_model->updateClassFunction($param2);
            $this->session->set_flashdata('flash_message', get_phrase('Data updated successfully'));
            redirect(base_url(). 'admin/classes', 'refresh');
        }


        if($param1 == 'delete'){
            $this->class_model->deleteClassFunction($param2);
            $this->session->set_flashdata('flash_message', get_phrase('Data deleted successfully'));
            redirect(base_url(). 'admin/classes', 'refresh');
    
        }

        $page_data['page_name']     = 'class';
        $page_data['page_title']    = get_phrase('Manage Class');
        $this->load->view('backend/index', $page_data);

    }


    /***********  The function manages Section  ***********************/
    function section ($param1 = null, $param2 = null, $param3 = null){

        if($param1 == 'create'){
        $this->section_model->createSectionFunction();
        $this->session->set_flashdata('flash_message', get_phrase('Data updated successfully'));
        redirect(base_url(). 'admin/section', 'refresh');
        }

        if($param1 == 'update'){
        $this->section_model->updateSectionFunction($param2);
        $this->session->set_flashdata('flash_message', get_phrase('Data updated successfully'));
        redirect(base_url(). 'admin/section', 'refresh');
        }

        if($param1 == 'delete'){
        $this->section_model->deleteSectionFunction($param2);
        $this->session->set_flashdata('flash_message', get_phrase('Data deleted successfully'));
        redirect(base_url(). 'admin/section', 'refresh');
        }

        $page_data['page_name']     = 'section';
        $page_data['page_title']    = get_phrase('Manage Section');
        $this->load->view('backend/index', $page_data);
    }

        function sections ($class_id = null){

            if($class_id == '')
            $class_id = $this->db->get('class')->first_row()->class_id;
            
            $page_data['page_name']     = 'section';
            $page_data['class_id']      = $class_id;
            $page_data['page_title']    = get_phrase('Manage Section');
            $this->load->view('backend/index', $page_data);

        }
    

    /***********  The function manages school timetable  ***********************/
    function class_routine ($param1 = null, $param2 = null, $param3 = null){

        if($param1 == 'create'){
        $this->class_routine_model->createTimetableFunction();
        $this->session->set_flashdata('flash_message', get_phrase('Data updated successfully'));
        redirect(base_url(). 'admin/listStudentTimetable', 'refresh');
        }

        if($param1 == 'update'){
        
        $this->class_routine_model->updateTimetableFunction($param2);
        $this->session->set_flashdata('flash_message', get_phrase('Data updated successfully'));
        redirect(base_url(). 'admin/listStudentTimetable', 'refresh');
        }

        if($param1 == 'delete'){
        
        $this->db->where('class_routine_id', $param2);
        $this->db->delete('class_routine');
        //$this->class_routine_model->deleteTimetableFunction($param2);
        $this->session->set_flashdata('flash_message', get_phrase('Data deleted successfully'));
        redirect(base_url(). 'admin/listStudentTimetable', 'refresh');
        }
    }

    function listStudentTimetable(){

        $page_data['page_name']     = 'listStudentTimetable';
        $page_data['page_title']    = get_phrase('School Timetable');
        $this->load->view('backend/index', $page_data);
    }

    function class_routine_add(){

        $page_data['page_name']     = 'class_routine_add';
        $page_data['page_title']    = get_phrase('School Timetable');
        $this->load->view('backend/index', $page_data);
    }

    function get_class_section_subject($class_id){
        $page_data['class_id']  =   $class_id;
        $this->load->view('backend/admin/class_routine_section_subject_selector', $page_data);

    }

    function studentTimetableLoad($class_id){

        $page_data['class_id']  =   $class_id;
        $this->load->view('backend/admin/studentTimetableLoad', $page_data);

    }

    function class_routine_print_view($class_id, $section_id){

        $page_data['class_id']      =   $class_id;
        $page_data['section_id']    =   $section_id;
        $this->load->view('backend/admin/class_routine_print_view', $page_data);
    }


    function section_subject_edit($class_id, $class_routine_id){

    $page_data['class_id']          =   $class_id;
    $page_data['class_routine_id']  =   $class_routine_id;
    $this->load->view('backend/admin/class_routine_section_subject_edit', $page_data);

    }


    /***********  The function manages school dormitory  ***********************/
    function dormitory ($param1 = null, $param2 = null, $param3 = null){

    if($param1 == 'create'){
        $this->dormitory_model->createDormitoryFunction();
        $this->session->set_flashdata('flash_message', get_phrase('Data updated successfully'));
        redirect(base_url(). 'admin/dormitory', 'refresh');
    }

    if($param1 == 'update'){
        $this->dormitory_model->updateDormitoryFunction($param2);
        $this->session->set_flashdata('flash_message', get_phrase('Data updated successfully'));
        redirect(base_url(). 'admin/dormitory', 'refresh');
    }


    if($param1 == 'delete'){
        $this->dormitory_model->deleteDormitoryFunction($param2);
        $this->session->set_flashdata('flash_message', get_phrase('Data deleted successfully'));
        redirect(base_url(). 'admin/dormitory', 'refresh');

    }

    $page_data['page_name']     = 'dormitory';
    $page_data['page_title']    = get_phrase('Manage Dormitory');
    $this->load->view('backend/index', $page_data);

    }


    /***********  The function manages hostel room  ***********************/
    function hostel_room ($param1 = null, $param2 = null, $param3 = null){

    if($param1 == 'create'){
        $this->dormitory_model->createHostelRoomFunction();
        $this->session->set_flashdata('flash_message', get_phrase('Data saved successfully'));
        redirect(base_url(). 'admin/hostel_room', 'refresh');
    }

    if($param1 == 'update'){
        $this->dormitory_model->updateHostelRoomFunction($param2);
        $this->session->set_flashdata('flash_message', get_phrase('Data updated successfully'));
        redirect(base_url(). 'admin/hostel_room', 'refresh');
    }


    if($param1 == 'delete'){
        $this->dormitory_model->deleteHostelRoomFunction($param2);
        $this->session->set_flashdata('flash_message', get_phrase('Data deleted successfully'));
        redirect(base_url(). 'admin/hostel_room', 'refresh');

    }

    $page_data['page_name']     = 'hostel_room';
    $page_data['page_title']    = get_phrase('Hostel Room');
    $this->load->view('backend/index', $page_data);

    }


    /***********  The function manages hostel category  ***********************/
    function hostel_category ($param1 = null, $param2 = null, $param3 = null){

    if($param1 == 'create'){
        $this->dormitory_model->createHostelCategoryFunction();
        $this->session->set_flashdata('flash_message', get_phrase('Data saved successfully'));
        redirect(base_url(). 'admin/hostel_category', 'refresh');
    }

    if($param1 == 'update'){
        $this->dormitory_model->updateHostelCategoryFunction($param2);
        $this->session->set_flashdata('flash_message', get_phrase('Data updated successfully'));
        redirect(base_url(). 'admin/hostel_category', 'refresh');
    }


    if($param1 == 'delete'){
        $this->dormitory_model->deleteHostelCategoryFunction($param2);
        $this->session->set_flashdata('flash_message', get_phrase('Data deleted successfully'));
        redirect(base_url(). 'admin/hostel_category', 'refresh');

    }

    $page_data['page_name']     = 'hostel_category';
    $page_data['page_title']    = get_phrase('Hostel Category');
    $this->load->view('backend/index', $page_data);
    }



    /***********  The function manages academic syllabus ***********************/
    function academic_syllabus ($param1 = null, $param2 = null, $param3 = null){

        if($param1 == 'create'){
        $this->academic_model->createAcademicSyllabus();
        $this->session->set_flashdata('flash_message', get_phrase('Data saved successfully'));
        redirect(base_url(). 'admin/academic_syllabus', 'refresh');
    }

    if($param1 == 'update'){
        $this->academic_model->updateAcademicSyllabus($param2);
        $this->session->set_flashdata('flash_message', get_phrase('Data updated successfully'));
        redirect(base_url(). 'admin/academic_syllabus', 'refresh');
    }


    if($param1 == 'delete'){
        $this->academic_model->deleteAcademicSyllabus($param2);
        $this->session->set_flashdata('flash_message', get_phrase('Data deleted successfully'));
        redirect(base_url(). 'admin/academic_syllabus', 'refresh');

        }

        $page_data['page_name']     = 'academic_syllabus';
        $page_data['page_title']    = get_phrase('Academic Syllabus');
        $this->load->view('backend/index', $page_data);

    }

    function get_class_subject($class_id){
        $subjects = $this->db->get_where('subject', array('class_id' => $class_id))->result_array();
            foreach($subjects as $key => $subject)
            {
                echo '<option value="'.$subject['subject_id'].'">'.$subject['name'].'</option>';
            }
    }

    function get_class_section($class_id){
        $sections = $this->db->get_where('section', array('class_id' => $class_id))->result_array();
            foreach($sections as $key => $section)
            {
                echo '<option value="'.$section['section_id'].'">'.$section['name'].'</option>';
            }
    }


    function download_academic_syllabus($academic_syllabus_code){
        $get_file_name = $this->db->get_where('academic_syllabus', array('academic_syllabus_code' => $academic_syllabus_code))->row()->file_name;
        // Loading download from helper.
        $this->load->helper('download');
        $get_download_content = file_get_contents('uploads/syllabus' . $get_file_name);
        $name = $file_name;
        force_download($name, $get_download_content);
    }

    function get_academic_syllabus ($class_id = null){

        if($class_id == '')
        $class_id = $this->db->get('class')->first_row()->class_id;
        
        $page_data['page_name']     = 'academic_syllabus';
        $page_data['class_id']      = $class_id;
        $page_data['page_title']    = get_phrase('Academic Syllabus');
        $this->load->view('backend/index', $page_data);

    }

    /***********  The function below add, update and delete student from students' table ***********************/
    function new_student ($param1 = null, $param2 = null, $param3 = null){

        if($param1 == 'create'){
            $this->student_model->createNewStudent();
            $this->session->set_flashdata('flash_message', get_phrase('Data saved successfully'));
            redirect(base_url(). 'admin/student_information', 'refresh');
        }

        if($param1 == 'update'){
            $this->student_model->updateNewStudent($param2);
            $this->session->set_flashdata('flash_message', get_phrase('Data updated successfully'));
            redirect(base_url(). 'admin/student_information', 'refresh');
        }

        if($param1 == 'delete'){
            $this->student_model->deleteNewStudent($param2);
            $this->session->set_flashdata('flash_message', get_phrase('Data deleted successfully'));
            redirect(base_url(). 'admin/student_information', 'refresh');

        }

        $page_data['page_name']     = 'new_student';
        $page_data['page_title']    = get_phrase('Manage Student');
        $this->load->view('backend/index', $page_data);

    }


    function student_information(){

        $page_data['page_name']     = 'student_information';
        $page_data['page_title']    = get_phrase('List Student');
        $this->load->view('backend/index', $page_data);
    }


    /**************************  search student function with ajax starts here   ***********************************/
    function getStudentClasswise($class_id){

        $page_data['class_id'] = $class_id;
        $this->load->view('backend/admin/showStudentClasswise', $page_data);
    }
    /**************************  search student function with ajax ends here   ***********************************/


    function edit_student($student_id){

        $page_data['student_id']      = $student_id;
        $page_data['page_name']     = 'edit_student';
        $page_data['page_title']    = get_phrase('Edit Student');
        $this->load->view('backend/index', $page_data);
    }


    function resetStudentPassword ($student_id) {
        $password['password']               =   sha1($this->input->post('new_password'));
        $confirm_password['confirm_new_password']   =   sha1($this->input->post('confirm_new_password'));
        if ($password['password'] == $confirm_password['confirm_new_password']) {
           $this->db->where('student_id', $student_id);
           $this->db->update('student', $password);
           $this->session->set_flashdata('flash_message', get_phrase('Password Changed'));
        }
        else{
            $this->session->set_flashdata('error_message', get_phrase('Type the same password'));
        }
        redirect(base_url() . 'admin/student_information', 'refresh');
    }

    function manage_attendance($date = null, $month= null, $year = null, $class_id = null, $section_id = null ){
        $active_sms_gateway = $this->db->get_where('sms_settings', array('type' => 'active_sms_gateway'))->row()->info;
        
        if ($_POST) {
	
            // Loop all the students of $class_id
            $students = $this->db->get_where('student', array('class_id' => $class_id))->result_array();
            foreach ($students as $key => $student) {
            $attendance_status = $this->input->post('status_' . $student['student_id']);
            $full_date = $year . "-" . $month . "-" . $date;
            $this->db->where('student_id', $student['student_id']);
            $this->db->where('date', $full_date);
    
            $this->db->update('attendance', array('status' => $attendance_status));
    
                   if ($attendance_status == 2) 
            {
                     if ($active_sms_gateway != '' || $active_sms_gateway != 'disabled') {
                        $student_name   = $this->db->get_where('student' , array('student_id' => $student['student_id']))->row()->name;
                        $parent_id      = $this->db->get_where('student' , array('student_id' => $student['student_id']))->row()->parent_id;
                        $message        = 'Your child' . ' ' . $student_name . 'is absent today.';
                        if($parent_id != null && $parent_id != 0){
                            $recieverPhoneNumber = $this->db->get_where('parent' , array('parent_id' => $parent_id))->row()->phone;
                            if($recieverPhoneNumber != '' || $recieverPhoneNumber != null){
                                $this->sms_model->send_sms($message, $recieverPhoneNumber);
                            }
                            else{
                                $this->session->set_flashdata('error_message' , get_phrase('Parent Phone Not Found'));
                            }
                        }
                        else{
                            $this->session->set_flashdata('error_message' , get_phrase('SMS Gateway Not Found'));
                        }
                    }
           }
        }
    
            $this->session->set_flashdata('flash_message', get_phrase('Updated Successfully'));
            redirect(base_url() . 'admin/manage_attendance/' . $date . '/' . $month . '/' . $year . '/' . $class_id . '/' . $section_id, 'refresh');
        }

        $page_data['date'] = $date;
        $page_data['month'] = $month;
        $page_data['year'] = $year;
        $page_data['class_id'] = $class_id;
        $page_data['section_id'] = $section_id;
        $page_data['page_name'] = 'manage_attendance';
        $page_data['page_title'] = get_phrase('Manage Attendance');
        $this->load->view('backend/index', $page_data);

    }

    function attendance_selector(){
        $date = $this->input->post('timestamp');
        $date = date_create($date);
        $date = date_format($date, "d/m/Y");
        redirect(base_url(). 'admin/manage_attendance/' .$date. '/' . $this->input->post('class_id'). '/' . $this->input->post('section_id'), 'refresh');
    }


    function attendance_report($class_id = NULL, $section_id = NULL, $month = NULL, $year = NULL) {
        
        $active_sms_gateway = $this->db->get_where('sms_settings', array('type' => 'active_sms_gateway'))->row()->info;
        
        
        if ($_POST) {
        redirect(base_url() . 'admin/attendance_report/' . $class_id . '/' . $section_id . '/' . $month . '/' . $year, 'refresh');
        }
        
        $classes = $this->db->get('class')->result_array();
        foreach ($classes as $key => $class) {
            if (isset($class_id) && $class_id == $class['class_id'])
                $class_name = $class['name'];
            }
                    
        $sections = $this->db->get('section')->result_array();
            foreach ($sections as $key => $section) {
                if (isset($section_id) && $section_id == $section['section_id'])
                    $section_name = $section['name'];
        }
        
        $page_data['month'] = $month;
        $page_data['year'] = $year;
        $page_data['class_id'] = $class_id;
        $page_data['section_id'] = $section_id;
        $page_data['page_name'] = 'attendance_report';
        $page_data['page_title'] = "Attendance Report:" . $class_name . " : Section " . $section_name;
        $this->load->view('backend/index', $page_data);
    }


    /******************** Load attendance with ajax code starts from here **********************/
	function loadAttendanceReport($class_id, $section_id, $month, $year)
    {
        $page_data['class_id'] 		= $class_id;					// get all class_id
		$page_data['section_id'] 	= $section_id;					// get all section_id
		$page_data['month'] 		= $month;						// get all month
		$page_data['year'] 			= $year;						// get all class year
		
        $this->load->view('backend/admin/loadAttendanceReport' , $page_data);
    }
    /******************** Load attendance with ajax code ends from here **********************/
    

    /******************** print attendance report **********************/
	function printAttendanceReport($class_id=NULL, $section_id=NULL, $month=NULL, $year=NULL)
    {
        $page_data['class_id'] 		= $class_id;					// get all class_id
		$page_data['section_id'] 	= $section_id;					// get all section_id
		$page_data['month'] 		= $month;						// get all month
		$page_data['year'] 			= $year;						// get all class year
		
        $page_data['page_name'] = 'printAttendanceReport';
        $page_data['page_title'] = "Attendance Report";
        $this->load->view('backend/index', $page_data);
    }
    /******************** /Ends here **********************/
    


     /***********  The function below add, update and delete exam question table ***********************/
    function examQuestion ($param1 = null, $param2 = null, $param3 = null){

        if($param1 == 'create'){
            $this->exam_question_model->createexamQuestion();
            $this->session->set_flashdata('flash_message', get_phrase('Data saved successfully'));
            redirect(base_url(). 'admin/examQuestion', 'refresh');
        }

        if($param1 == 'update'){
            $this->exam_question_model->updateexamQuestion($param2);
            $this->session->set_flashdata('flash_message', get_phrase('Data updated successfully'));
            redirect(base_url(). 'admin/examQuestion', 'refresh');
        }

        if($param1 == 'delete'){
            $this->exam_question_model->deleteexamQuestion($param2);
            $this->session->set_flashdata('flash_message', get_phrase('Data deleted successfully'));
            redirect(base_url(). 'admin/examQuestion', 'refresh');
        }

        $page_data['page_name']     = 'examQuestion';
        $page_data['page_title']    = get_phrase('Exam Question');
        $this->load->view('backend/index', $page_data);
    }
     /***********  The function below add, update and delete exam question table ends here ***********************/


    /***********  The function below add, update and delete examination table ***********************/
    function createExamination ($param1 = null, $param2 = null, $param3 = null){

        if($param1 == 'create'){
            $this->exam_model->createExamination();
            $this->session->set_flashdata('flash_message', get_phrase('Data saved successfully'));
            redirect(base_url(). 'admin/createExamination', 'refresh');
        }

        if($param1 == 'update'){
            $this->exam_model->updateExamination($param2);
            $this->session->set_flashdata('flash_message', get_phrase('Data updated successfully'));
            redirect(base_url(). 'admin/createExamination', 'refresh');
        }

        if($param1 == 'delete'){
            $this->exam_model->deleteExamination($param2);
            $this->session->set_flashdata('flash_message', get_phrase('Data deleted successfully'));
            redirect(base_url(). 'admin/createExamination', 'refresh');
        }

        $page_data['page_name']     = 'createExamination';
        $page_data['page_title']    = get_phrase('Create Exam');
        $this->load->view('backend/index', $page_data);
    }
    /***********  The function below add, update and delete examination table ends here ***********************/

    /***********  The function below add, update and delete student payment table ***********************/
    function student_payment ($param1 = null, $param2 = null, $param3 = null){

        if($param1 == 'single_invoice'){
            $this->student_payment_model->createStudentSinglePaymentFunction();
            $this->session->set_flashdata('flash_message', get_phrase('Data saved successfully'));
            redirect(base_url(). 'admin/student_invoice', 'refresh');
        }

        if($param1 == 'mass_invoice'){
            $this->student_payment_model->createStudentMassPaymentFunction();
            $this->session->set_flashdata('flash_message', get_phrase('Data updated successfully'));
            redirect(base_url(). 'admin/student_invoice', 'refresh');
        }

        if($param1 == 'update_invoice'){
            $this->student_payment_model->updateStudentPaymentFunction($param2);
            $this->session->set_flashdata('flash_message', get_phrase('Data updated successfully'));
            redirect(base_url(). 'admin/student_invoice', 'refresh');
        }

        if($param1 == 'take_payment'){
            $this->student_payment_model->takeNewPaymentFromStudent($param2);
            $this->session->set_flashdata('flash_message', get_phrase('Data updated successfully'));
            redirect(base_url(). 'admin/student_invoice', 'refresh');
        }


        if($param1 == 'delete_invoice'){
            $this->student_payment_model->deleteStudentPaymentFunction($param2);
            $this->session->set_flashdata('flash_message', get_phrase('Data deleted successfully'));
            redirect(base_url(). 'admin/student_invoice', 'refresh');
        }

        $page_data['page_name']     = 'student_payment';
        $page_data['page_title']    = get_phrase('Student Payment');
        $this->load->view('backend/index', $page_data);
    }   
    /***********  / Student payment ends here ***********************/
    
    function get_class_student($class_id){
        $students = $this->db->get_where('student', array('class_id' => $class_id))->result_array();
            foreach($students as $key => $student)
            {
                echo '<option value="'.$student['student_id'].'">'.$student['name'].'</option>';
            }
    }


    function get_class_mass_student($class_id){

        $students = $this->db->get_where('student', array('class_id' => $class_id))->result_array();
        foreach($students as $key => $student)
        {
            echo '<div class="">
            <label><input type="checkbox" class="check" name="student_id[]" value="' . $student['student_id'] . '">' . '&nbsp;'. $student['name'] .'</label></div>';
        }

        echo '<br><button type ="button" class="btn btn-success btn-sm btn-rounded" onClick="select()">'.get_phrase('Select All').'</button>';
        echo '<button type ="button" class="btn btn-primary btn-sm btn-rounded" onClick="unselect()">'.get_phrase('Unselect All').'</button>';
    }

    function student_invoice(){

        $page_data['page_name']     = 'student_invoice';
        $page_data['page_title']    = get_phrase('Manage Invoice');
        $this->load->view('backend/index', $page_data);

    }

    /***********  The function below add, update and delete publisher table ***********************/
    function publisher ($param1 = null, $param2 = null, $param3 = null){

        if($param1 == 'create'){
            $this->library_model->createPublisherFunction();
            $this->session->set_flashdata('flash_message', get_phrase('Data saved successfully'));
            redirect(base_url(). 'admin/publisher', 'refresh');
        }

        if($param1 == 'update'){
            $this->library_model->updatePublisherFunction($param2);
            $this->session->set_flashdata('flash_message', get_phrase('Data updated successfully'));
            redirect(base_url(). 'admin/publisher', 'refresh');
        }

        if($param1 == 'delete'){
            $this->library_model->deletePublisherFunction($param2);
            $this->session->set_flashdata('flash_message', get_phrase('Data deleted successfully'));
            redirect(base_url(). 'admin/publisher', 'refresh');
        }

        $page_data['page_name']     = 'publisher';
        $page_data['page_title']    = get_phrase('Manage Publisher');
        $this->load->view('backend/index', $page_data);
    }
    /***********  The function below add, update and delete publisher table ends here ***********************/


    /***********  The function below add, update and delete publisher table ***********************/
    function author ($param1 = null, $param2 = null, $param3 = null){

        if($param1 == 'create'){
            $this->library_model->createAuthorFunction();
            $this->session->set_flashdata('flash_message', get_phrase('Data saved successfully'));
            redirect(base_url(). 'admin/author', 'refresh');
        }

        if($param1 == 'update'){
            $this->library_model->updateAuthorFunction($param2);
            $this->session->set_flashdata('flash_message', get_phrase('Data updated successfully'));
            redirect(base_url(). 'admin/author', 'refresh');
        }

        if($param1 == 'delete'){
            $this->library_model->deleteAuthorFunction($param2);
            $this->session->set_flashdata('flash_message', get_phrase('Data deleted successfully'));
            redirect(base_url(). 'admin/author', 'refresh');
        }

        $page_data['page_name']     = 'author';
        $page_data['page_title']    = get_phrase('Manage Author');
        $this->load->view('backend/index', $page_data);
    }

    /***********  The function below add, update and delete publisher table ends here ***********************/

    /***********  The function below add, update and delete BookCategory table ***********************/
    function book_category ($param1 = null, $param2 = null, $param3 = null){

        if($param1 == 'create'){
            $this->library_model->createBookCategoryFunction();
            $this->session->set_flashdata('flash_message', get_phrase('Data saved successfully'));
            redirect(base_url(). 'admin/book_category', 'refresh');
        }

        if($param1 == 'update'){
            $this->library_model->updateBookCategoryFunction($param2);
            $this->session->set_flashdata('flash_message', get_phrase('Data updated successfully'));
            redirect(base_url(). 'admin/book_category', 'refresh');
        }

        if($param1 == 'delete'){
            $this->library_model->deleteBookCategoryFunction($param2);
            $this->session->set_flashdata('flash_message', get_phrase('Data deleted successfully'));
            redirect(base_url(). 'admin/book_category', 'refresh');
        }

        $page_data['page_name']     = 'book_category';
        $page_data['page_title']    = get_phrase('Book Category');
        $this->load->view('backend/index', $page_data);
    }
    /***********  The function below add, update and delete BookCategory table ends here ***********************/



    /***********  The function below add, update and delete book table ***********************/
    function book ($param1 = null, $param2 = null, $param3 = null){

        if($param1 == 'create'){
            $this->library_model->createBookFunction();
            $this->session->set_flashdata('flash_message', get_phrase('Data saved successfully'));
            redirect(base_url(). 'admin/book', 'refresh');
        }

        if($param1 == 'update'){
            $this->library_model->updateBookFunction($param2);
            $this->session->set_flashdata('flash_message', get_phrase('Data updated successfully'));
            redirect(base_url(). 'admin/book', 'refresh');
        }

        if($param1 == 'delete'){
            $this->library_model->deleteBookFunction($param2);
            $this->session->set_flashdata('flash_message', get_phrase('Data deleted successfully'));
            redirect(base_url(). 'admin/book', 'refresh');
        }

        $page_data['page_name']     = 'book';
        $page_data['page_title']    = get_phrase('Manage Library');
        $this->load->view('backend/index', $page_data);
    }
    /***********  The function below add, update and delete book table ends here ***********************/

    /***********  The function below manages school event ***********************/
    function noticeboard ($param1 = null, $param2 = null, $param3 = null){

        if($param1 == 'create'){
            $this->event_model->createNoticeboardFunction();
            $this->session->set_flashdata('flash_message', get_phrase('Data saved successfully'));
            redirect(base_url(). 'admin/noticeboard', 'refresh');
        }

        if($param1 == 'update'){
            $this->event_model->updateNoticeboardFunction($param2);
            $this->session->set_flashdata('flash_message', get_phrase('Data updated successfully'));
            redirect(base_url(). 'admin/noticeboard', 'refresh');
        }

        if($param1 == 'delete'){
            $this->event_model->deleteNoticeboardFunction($param2);
            $this->session->set_flashdata('flash_message', get_phrase('Data deleted successfully'));
            redirect(base_url(). 'admin/noticeboard', 'refresh');
        }

        $page_data['page_name']     = 'noticeboard';
        $page_data['page_title']    = get_phrase('School Event');
        $this->load->view('backend/index', $page_data);
    }
    /***********  The function that manages school events ends here ***********************/

     /***********  The function below manages school language ***********************/
     function manage_language ($param1 = null, $param2 = null, $param3 = null){

        if($param1 == 'edit_phrase'){
            $page_data['edit_profile']  =   $param2;
        }

        if($param1 == 'add_language'){
            $this->language_model->createNewLanguage();
            $this->session->set_flashdata('flash_message', get_phrase('Data saved successfully'));
            redirect(base_url(). 'admin/manage_language', 'refresh');
        }

        if($param1 == 'add_phrase'){
            $this->language_model->createNewLanguagePhrase();
            $this->session->set_flashdata('flash_message', get_phrase('Data saved successfully'));
            redirect(base_url(). 'admin/manage_language', 'refresh');
        }

        if($param1 == 'delete_language'){
            $this->language_model->deleteLanguage($param2);
            $this->session->set_flashdata('flash_message', get_phrase('Data deleted successfully'));
            redirect(base_url(). 'admin/manage_language', 'refresh');
        }

        $page_data['page_name']     = 'manage_language';
        $page_data['page_title']    = get_phrase('Manage Language');
        $this->load->view('backend/index', $page_data);
    }
    /***********  The function that manages school language ends here ***********************/

    function updatePhraseWithAjax(){

        $checker['phrase_id']   =   $this->input->post('phraseId');
        $updater[$this->input->post('currentEditingLanguage')]  =   $this->input->post('updatedValue');

        $this->db->where('phrase_id', $checker['phrase_id'] );
        $this->db->update('language', $updater);

        echo $checker['phrase_id']. ' '. $this->input->post('currentEditingLanguage'). ' '. $this->input->post('updatedValue');

    }


    /***********  The function below manages school marks ***********************/
    function marks ($exam_id = null, $class_id = null, $student_id = null){

            if($this->input->post('operation') == 'selection'){

                $page_data['exam_id']       =  $this->input->post('exam_id'); 
                $page_data['class_id']      =  $this->input->post('class_id');
                $page_data['student_id']    =  $this->input->post('student_id');

                if($page_data['exam_id'] > 0 && $page_data['class_id'] > 0 && $page_data['student_id'] > 0){

                    redirect(base_url(). 'admin/marks/'. $page_data['exam_id'] .'/' . $page_data['class_id'] . '/' . $page_data['student_id'], 'refresh');
                }
                else{
                    $this->session->set_flashdata('error_message', get_phrase('Pleasen select something'));
                    redirect(base_url(). 'admin/marks', 'refresh');
                }
            }

            if($this->input->post('operation') == 'update_student_subject_score'){

                $select_subject_first = $this->db->get_where('subject', array('class_id' => $class_id ))->result_array();
                    foreach ($select_subject_first as $key => $dispay_subject_from_subject_table){

                        $page_data['class_score1']  =   $this->input->post('class_score1_' . $dispay_subject_from_subject_table['subject_id']);
                        $page_data['class_score2']  =   $this->input->post('class_score2_' . $dispay_subject_from_subject_table['subject_id']);
                        $page_data['class_score3']  =   $this->input->post('class_score3_' . $dispay_subject_from_subject_table['subject_id']);
                        $page_data['exam_score']    =   $this->input->post('exam_score_' . $dispay_subject_from_subject_table['subject_id']);
                        $page_data['comment']       =   $this->input->post('comment_' . $dispay_subject_from_subject_table['subject_id']);

                        $this->db->where('mark_id', $this->input->post('mark_id_' . $dispay_subject_from_subject_table['subject_id']));
                        $this->db->update('mark', $page_data);  
                    }

                    $this->session->set_flashdata('flash_message', get_phrase('Data Updated Successfully'));
                    redirect(base_url(). 'admin/marks/'. $this->input->post('exam_id') .'/' . $this->input->post('class_id') . '/' . $this->input->post('student_id'), 'refresh');
            }

        $page_data['exam_id']       =   $exam_id;
        $page_data['class_id']      =   $class_id;
        $page_data['student_id']    =   $student_id;
        $page_data['subject_id']   =    $subject_id;
        $page_data['page_name']     =   'marks';
        $page_data['page_title']    = get_phrase('Student Marks');
        $this->load->view('backend/index', $page_data);
    }
    /***********  The function that manages school marks ends here ***********************/



    /***********  The function below manages school marks ***********************/
     function student_marksheet_subject ($exam_id = null, $class_id = null, $subject_id = null){

        if($this->input->post('operation') == 'selection'){

            $page_data['exam_id']       =  $this->input->post('exam_id'); 
            $page_data['class_id']      =  $this->input->post('class_id');
            $page_data['subject_id']    =  $this->input->post('subject_id');

            if($page_data['exam_id'] > 0 && $page_data['class_id'] > 0 && $page_data['subject_id'] > 0){

                redirect(base_url(). 'admin/student_marksheet_subject/'. $page_data['exam_id'] .'/' . $page_data['class_id'] . '/' . $page_data['subject_id'], 'refresh');
            }
            else{
                $this->session->set_flashdata('error_message', get_phrase('Pleasen select something'));
                redirect(base_url(). 'admin/student_marksheet_subject', 'refresh');
            }
        }

        if($this->input->post('operation') == 'update_student_subject_score'){

            $select_student_first = $this->db->get_where('student', array('class_id' => $class_id ))->result_array();
                foreach ($select_student_first as $key => $dispay_student_from_student_table){

                    $page_data['class_score1']  =   $this->input->post('class_score1_' . $dispay_student_from_student_table['student_id']);
                    $page_data['class_score2']  =   $this->input->post('class_score2_' . $dispay_student_from_student_table['student_id']);
                    $page_data['class_score3']  =   $this->input->post('class_score3_' . $dispay_student_from_student_table['student_id']);
                    $page_data['exam_score']    =   $this->input->post('exam_score_' . $dispay_student_from_student_table['student_id']);
                    $page_data['comment']       =   $this->input->post('comment_' . $dispay_student_from_student_table['student_id']);

                    $this->db->where('mark_id', $this->input->post('mark_id_' . $dispay_student_from_student_table['student_id']));
                    $this->db->update('mark', $page_data);  
                }

                $this->session->set_flashdata('flash_message', get_phrase('Data Updated Successfully'));
                redirect(base_url(). 'admin/student_marksheet_subject/'. $this->input->post('exam_id') .'/' . $this->input->post('class_id') . '/' . $this->input->post('subject_id'), 'refresh');
        }

    $page_data['exam_id']       =   $exam_id;
    $page_data['class_id']      =   $class_id;
    $page_data['student_id']    =   $student_id;
    $page_data['subject_id']   =    $subject_id;
    $page_data['page_name']     =   'student_marksheet_subject';
    $page_data['page_title']    = get_phrase('Student Marks');
    $this->load->view('backend/index', $page_data);
    }
    /***********  The function that manages school marks ends here ***********************/

    /***********  The function below manages school event ***********************/
    function exam_marks_sms ($param1 = null, $param2 = null, $param3 = null){

        if($param1 == 'send'){
            $this->crud_model->send_student_score_model();
            $this->session->set_flashdata('flash_message', get_phrase('Data Sent successfully'));
            redirect(base_url(). 'admin/exam_marks_sms', 'refresh');
        }

        $page_data['page_name']     = 'exam_marks_sms';
        $page_data['page_title']    = get_phrase('Send Student Scores');
        $this->load->view('backend/index', $page_data);
    }
    /***********  The function that manages school events ends here ***********************/

    
    /***********  The function below manages new admin ***********************/
    function newAdministrator ($param1 = null, $param2 = null, $param3 = null){

        if($param1 == 'create'){
            $this->admin_model->createNewAdministrator();
            $this->session->set_flashdata('flash_message', get_phrase('Data saved successfully'));
            redirect(base_url(). 'admin/newAdministrator', 'refresh');
        }

        if($param1 == 'update'){
            $this->admin_model->updateAdministrator($param2);
            $this->session->set_flashdata('flash_message', get_phrase('Data updated successfully'));
            redirect(base_url(). 'admin/newAdministrator', 'refresh');
        }

        if($param1 == 'delete'){
            $this->admin_model->deleteAdministrator($param2);
            $this->session->set_flashdata('flash_message', get_phrase('Data deleted successfully'));
            redirect(base_url(). 'admin/newAdministrator', 'refresh');
        }

        $page_data['page_name']     = 'newAdministrator';
        $page_data['page_title']    = get_phrase('New Administrator');
        $this->load->view('backend/index', $page_data);
    }
    /***********  The function that manages administrator ends here ***********************/

    function updateAdminRole($param2){
        $this->admin_model->updateAllDetailsForAdminRole($param2);
        $this->session->set_flashdata('flash_message', get_phrase('Data updated successfully'));
        redirect(base_url(). 'admin/newAdministrator', 'refresh');
    }

    /* Teacher Attendance functions */
    function teacher_attendance($param1 = '', $param2 = '', $param3 = '') {
        if ($this->session->userdata('admin_login') != 1) {
            redirect(base_url(), 'refresh');
        }

        // Check if teacher_attendance table exists, if not create it
        if (!$this->db->table_exists('teacher_attendance')) {
            $query = "CREATE TABLE IF NOT EXISTS `teacher_attendance` (
                `attendance_id` int(11) NOT NULL AUTO_INCREMENT,
                `teacher_id` int(11) NOT NULL,
                `status` int(11) NOT NULL DEFAULT '0' COMMENT '0=undefined, 1=present, 2=absent',
                `date` date NOT NULL,
                `year` varchar(10) NOT NULL,
                `month` varchar(10) NOT NULL,
                `day` varchar(10) NOT NULL,
                PRIMARY KEY (`attendance_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
            $this->db->query($query);
        }

        if ($param1 == 'save') {
            $this->load->model('teacher_attendance_model');
            $this->teacher_attendance_model->saveTeacherAttendance();
            $this->session->set_flashdata('flash_message', get_phrase('Teacher attendance saved successfully'));
            redirect(base_url() . 'admin/teacher_attendance_view/' . $this->input->post('date'), 'refresh');
        }

        $page_data['page_name'] = 'teacher_attendance';
        $page_data['page_title'] = get_phrase('Teacher Attendance');
        $this->load->view('backend/index', $page_data);
    }

    function teacher_attendance_view($date = '') {
        if ($this->session->userdata('admin_login') != 1) {
            redirect(base_url(), 'refresh');
        }

        if ($date == '') {
            $date = date('Y-m-d');
        }

        $this->load->model('teacher_attendance_model');
        $page_data['attendance_data'] = $this->teacher_attendance_model->getTeacherAttendance($date);
        $page_data['date'] = $date;
        $page_data['page_name'] = 'teacher_attendance_view';
        $page_data['page_title'] = get_phrase('Teacher Attendance');
        $this->load->view('backend/index', $page_data);
    }

    function teacher_attendance_report($param1 = '', $param2 = '', $param3 = '') {
        if ($this->session->userdata('admin_login') != 1) {
            redirect(base_url(), 'refresh');
        }

        if ($param1 == 'view_report') {
            $page_data['month'] = $this->input->post('month');
            $page_data['year'] = $this->input->post('year');
            redirect(base_url() . 'admin/teacher_attendance_report/' . $page_data['month'] . '/' . $page_data['year'], 'refresh');
        }

        $page_data['month'] = $param1 ? $param1 : date('m');
        $page_data['year'] = $param2 ? $param2 : date('Y');

        $this->load->model('teacher_attendance_model');
        $page_data['attendance_report'] = $this->teacher_attendance_model->getTeacherAttendanceReport($page_data['month'], $page_data['year']);
        
        $page_data['page_name'] = 'teacher_attendance_report';
        $page_data['page_title'] = get_phrase('Teacher Attendance Report');
        $this->load->view('backend/index', $page_data);
    }
    /* End of Teacher Attendance functions */

    /* Class Timetable Enhanced functions */
    function timetable($param1 = '', $param2 = '', $param3 = '') {
        if ($this->session->userdata('admin_login') != 1) {
            redirect(base_url(), 'refresh');
        }
        
        try {
            // Check if timetable table exists and create if needed
            if (!$this->db->table_exists('timetable')) {
                $this->timetable_model->create_table_if_not_exists();
                log_message('info', 'Created timetable table as it did not exist');
            }
            
            if ($param1 == 'create') {
                // Validate that required fields are present
                if (empty($this->input->post('class_id')) || 
                    empty($this->input->post('section_id')) || 
                    empty($this->input->post('subject_id')) ||
                    empty($this->input->post('teacher_id')) ||
                    empty($this->input->post('day')) ||
                    empty($this->input->post('starting_time')) ||
                    empty($this->input->post('ending_time'))) {
                    $this->session->set_flashdata('error_message', get_phrase('All fields are required'));
                    redirect(base_url() . 'admin/timetable', 'refresh');
                    return;
                }
                
                $data = array(
                    'class_id' => $this->input->post('class_id'),
                    'section_id' => $this->input->post('section_id'),
                    'subject_id' => $this->input->post('subject_id'),
                    'teacher_id' => $this->input->post('teacher_id'),
                    'day' => $this->input->post('day'),
                    'starting_time' => $this->input->post('starting_time'),
                    'ending_time' => $this->input->post('ending_time'),
                    'room_number' => $this->input->post('room_number')
                );
                
                // Check for related data existence
                $class_exists = $this->db->get_where('class', array('class_id' => $data['class_id']))->num_rows() > 0;
                $section_exists = $this->db->get_where('section', array('section_id' => $data['section_id']))->num_rows() > 0;
                $subject_exists = $this->db->get_where('subject', array('subject_id' => $data['subject_id']))->num_rows() > 0;
                $teacher_exists = $this->db->get_where('teacher', array('teacher_id' => $data['teacher_id']))->num_rows() > 0;
                
                if (!$class_exists || !$section_exists || !$subject_exists || !$teacher_exists) {
                    $this->session->set_flashdata('error_message', get_phrase('Related records not found. Please check your data.'));
                    redirect(base_url() . 'admin/timetable', 'refresh');
                    return;
                }
                
                // Now insert the data
                $this->db->insert('timetable', $data);
                log_message('debug', 'Timetable entry created: ' . json_encode($data));
                $this->session->set_flashdata('flash_message', get_phrase('Timetable added successfully'));
                redirect(base_url() . 'admin/timetable', 'refresh');
            }
            
            if ($param1 == 'update') {
                // Validate that required fields are present
                if (empty($this->input->post('class_id')) || 
                    empty($this->input->post('section_id')) || 
                    empty($this->input->post('subject_id')) ||
                    empty($this->input->post('teacher_id')) ||
                    empty($this->input->post('day')) ||
                    empty($this->input->post('starting_time')) ||
                    empty($this->input->post('ending_time'))) {
                    $this->session->set_flashdata('error_message', get_phrase('All fields are required'));
                    redirect(base_url() . 'admin/timetable', 'refresh');
                    return;
                }
                
                // Validate that record exists
                $record_exists = $this->db->get_where('timetable', array('timetable_id' => $param2))->num_rows() > 0;
                if (!$record_exists) {
                    $this->session->set_flashdata('error_message', get_phrase('Timetable entry not found'));
                    redirect(base_url() . 'admin/timetable', 'refresh');
                    return;
                }
                
                $data = array(
                    'class_id' => $this->input->post('class_id'),
                    'section_id' => $this->input->post('section_id'),
                    'subject_id' => $this->input->post('subject_id'),
                    'teacher_id' => $this->input->post('teacher_id'),
                    'day' => $this->input->post('day'),
                    'starting_time' => $this->input->post('starting_time'),
                    'ending_time' => $this->input->post('ending_time'),
                    'room_number' => $this->input->post('room_number')
                );
                
                // Check for related data existence
                $class_exists = $this->db->get_where('class', array('class_id' => $data['class_id']))->num_rows() > 0;
                $section_exists = $this->db->get_where('section', array('section_id' => $data['section_id']))->num_rows() > 0;
                $subject_exists = $this->db->get_where('subject', array('subject_id' => $data['subject_id']))->num_rows() > 0;
                $teacher_exists = $this->db->get_where('teacher', array('teacher_id' => $data['teacher_id']))->num_rows() > 0;
                
                if (!$class_exists || !$section_exists || !$subject_exists || !$teacher_exists) {
                    $this->session->set_flashdata('error_message', get_phrase('Related records not found. Please check your data.'));
                    redirect(base_url() . 'admin/timetable', 'refresh');
                    return;
                }
                
                $this->db->where('timetable_id', $param2);
                $this->db->update('timetable', $data);
                log_message('debug', 'Timetable entry updated: ' . json_encode($data) . ' for ID: ' . $param2);
                $this->session->set_flashdata('flash_message', get_phrase('Timetable updated successfully'));
                redirect(base_url() . 'admin/timetable', 'refresh');
            }
            
            if ($param1 == 'delete') {
                // Validate that record exists
                $record_exists = $this->db->get_where('timetable', array('timetable_id' => $param2))->num_rows() > 0;
                if (!$record_exists) {
                    $this->session->set_flashdata('error_message', get_phrase('Timetable entry not found'));
                    redirect(base_url() . 'admin/timetable', 'refresh');
                    return;
                }
                
                $this->db->where('timetable_id', $param2);
                $this->db->delete('timetable');
                log_message('debug', 'Timetable entry deleted for ID: ' . $param2);
                $this->session->set_flashdata('flash_message', get_phrase('Timetable deleted successfully'));
                redirect(base_url() . 'admin/timetable', 'refresh');
            }
            
            $page_data['page_name'] = 'timetable';
            $page_data['page_title'] = get_phrase('Class Timetable');
            $this->load->view('backend/index', $page_data);
            
        } catch (Exception $e) {
            log_message('error', 'Error in timetable function: ' . $e->getMessage());
            $this->session->set_flashdata('error_message', get_phrase('An error occurred. Please try again.'));
            redirect(base_url() . 'admin/dashboard', 'refresh');
        }
    }

    function timetable_view($class_id = '', $section_id = '') {
        if ($this->session->userdata('admin_login') != 1) {
            redirect(base_url(), 'refresh');
        }
        
        try {
            // Validate parameters
            if (empty($class_id) || !is_numeric($class_id) || empty($section_id) || !is_numeric($section_id)) {
                $this->session->set_flashdata('error_message', get_phrase('Invalid class or section'));
                redirect(base_url() . 'admin/timetable', 'refresh');
                return;
            }
            
            // Check if class and section exist
            $class_exists = $this->db->get_where('class', array('class_id' => $class_id))->num_rows() > 0;
            $section_exists = $this->db->get_where('section', array('section_id' => $section_id))->num_rows() > 0;
            
            if (!$class_exists || !$section_exists) {
                $this->session->set_flashdata('error_message', get_phrase('Class or section not found'));
                redirect(base_url() . 'admin/timetable', 'refresh');
                return;
            }
            
            // Check if timetable entries exist for this class and section
            $entries_exist = $this->db->get_where('timetable', array(
                'class_id' => $class_id,
                'section_id' => $section_id
            ))->num_rows() > 0;
            
            $page_data['class_id'] = $class_id;
            $page_data['section_id'] = $section_id;
            $page_data['page_name'] = 'timetable_view';
            $page_data['page_title'] = get_phrase('Class Timetable View');
            $page_data['has_entries'] = $entries_exist;
            
            log_message('debug', 'Loading timetable view for class_id: ' . $class_id . ', section_id: ' . $section_id);
            $this->load->view('backend/index', $page_data);
        } catch (Exception $e) {
            log_message('error', 'Error in timetable_view: ' . $e->getMessage());
            $this->session->set_flashdata('error_message', get_phrase('An error occurred while loading the timetable'));
            redirect(base_url() . 'admin/timetable', 'refresh');
        }
    }

    function timetable_print_view($class_id = '', $section_id = '') {
        if ($this->session->userdata('admin_login') != 1) {
            redirect(base_url(), 'refresh');
        }
        
        try {
            // Validate parameters
            if (empty($class_id) || !is_numeric($class_id) || empty($section_id) || !is_numeric($section_id)) {
                $this->session->set_flashdata('error_message', get_phrase('Invalid class or section'));
                redirect(base_url() . 'admin/timetable', 'refresh');
                return;
            }
            
            // Check if class and section exist
            $class_exists = $this->db->get_where('class', array('class_id' => $class_id))->num_rows() > 0;
            $section_exists = $this->db->get_where('section', array('section_id' => $section_id))->num_rows() > 0;
            
            if (!$class_exists || !$section_exists) {
                $this->session->set_flashdata('error_message', get_phrase('Class or section not found'));
                redirect(base_url() . 'admin/timetable', 'refresh');
                return;
            }
            
            // Check if timetable entries exist for this class and section
            $entries_exist = $this->db->get_where('timetable', array(
                'class_id' => $class_id,
                'section_id' => $section_id
            ))->num_rows() > 0;
            
            if (!$entries_exist) {
                $this->session->set_flashdata('error_message', get_phrase('No timetable entries found for this class and section'));
                redirect(base_url() . 'admin/timetable_view/' . $class_id . '/' . $section_id, 'refresh');
                return;
            }
            
            $page_data['class_id'] = $class_id;
            $page_data['section_id'] = $section_id;
            $page_data['page_title'] = get_phrase('Print Timetable');
            log_message('debug', 'Loading timetable print view for class_id: ' . $class_id . ', section_id: ' . $section_id);
            $this->load->view('backend/admin/timetable_print_view', $page_data);
        } catch (Exception $e) {
            log_message('error', 'Error in timetable_print_view: ' . $e->getMessage());
            $this->session->set_flashdata('error_message', get_phrase('An error occurred while preparing the print view'));
            redirect(base_url() . 'admin/timetable_view/' . $class_id . '/' . $section_id, 'refresh');
        }
    }
    /* End of Class Timetable Enhanced functions */

    /* New method to load teacher attendance report via AJAX */
    function load_teacher_attendance_report($month = '', $year = '') {
        if ($this->session->userdata('admin_login') != 1) {
            echo json_encode(['error' => 'Access denied']);
            return;
        }

        try {
            // Set default values if params are empty
            $page_data['month'] = $month ? intval($month) : intval(date('m'));
            $page_data['year'] = $year ? intval($year) : intval(date('Y'));

            // Validate inputs
            if (!is_numeric($page_data['month']) || $page_data['month'] < 1 || $page_data['month'] > 12) {
                echo '<div class="alert alert-danger">Invalid month parameter: ' . htmlspecialchars($month) . '</div>';
                return;
            }

            if (!is_numeric($page_data['year']) || $page_data['year'] < 2000 || $page_data['year'] > 2100) {
                echo '<div class="alert alert-danger">Invalid year parameter: ' . htmlspecialchars($year) . '</div>';
                return;
            }

            // Make sure the teacher_attendance table exists
            if (!$this->db->table_exists('teacher_attendance')) {
                log_message('debug', 'Creating teacher_attendance table as it does not exist');
                $query = "CREATE TABLE IF NOT EXISTS `teacher_attendance` (
                    `attendance_id` int(11) NOT NULL AUTO_INCREMENT,
                    `teacher_id` int(11) NOT NULL,
                    `status` int(11) NOT NULL DEFAULT '0' COMMENT '0=undefined, 1=present, 2=absent',
                    `date` date NOT NULL,
                    `year` varchar(10) NOT NULL,
                    `month` varchar(10) NOT NULL,
                    `day` varchar(10) NOT NULL,
                    PRIMARY KEY (`attendance_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
                $this->db->query($query);
            }
            
            // Load model if not already loaded
            if (!isset($this->teacher_attendance_model)) {
                $this->load->model('teacher_attendance_model');
            }
            
            // Log request info
            log_message('debug', 'Loading attendance report for month ' . $page_data['month'] . ' year ' . $page_data['year']);
            
            // Get the attendance report from the model with error handling
            $page_data['attendance_report'] = $this->teacher_attendance_model->getTeacherAttendanceReport($page_data['month'], $page_data['year']);
            
            if (empty($page_data['attendance_report'])) {
                log_message('debug', 'No attendance report data found for month ' . $page_data['month'] . ' year ' . $page_data['year']);
                echo '<div class="alert alert-info">No attendance data found for ' . date('F Y', mktime(0, 0, 0, $page_data['month'], 1, $page_data['year'])) . '</div>';
                return;
            }
            
            // Load the view
            $this->load->view('backend/admin/teacher_attendance_report_ajax', $page_data);
            
        } catch (Exception $e) {
            log_message('error', 'Error in load_teacher_attendance_report: ' . $e->getMessage());
            echo '<div class="alert alert-danger">
                <h4><i class="fa fa-exclamation-triangle"></i> Error</h4>
                <p>Error: ' . $e->getMessage() . '</p>
                <p>Please try again later or contact support.</p>
            </div>';
        }
    }
    
    /* Add this method to handle teacher attendance report printing */
    function teacher_attendance_report_print_view($month = '', $year = '') {
        if ($this->session->userdata('admin_login') != 1) {
            redirect(base_url(), 'refresh');
        }
        
        $page_data['month'] = $month ? $month : date('m');
        $page_data['year'] = $year ? $year : date('Y');
        
        $this->load->model('teacher_attendance_model');
        $page_data['attendance_report'] = $this->teacher_attendance_model->getTeacherAttendanceReport($page_data['month'], $page_data['year']);
        
        $page_data['page_title'] = get_phrase('Teacher Attendance Report');
        $this->load->view('backend/admin/teacher_attendance_report_print_view', $page_data);
    }

    /* Teacher Diary functionality for Admin */
    function teacher_diaries() {
        if ($this->session->userdata('admin_login') != 1) {
            redirect(base_url(), 'refresh');
        }
        
        // Load the teacher diary model
        $this->load->model('teacher_diary_model');
        
        try {
            // Create the table if it doesn't exist
            $this->teacher_diary_model->create_table_if_not_exists();
            
            $page_data['diaries'] = $this->teacher_diary_model->get_all_diaries();
            $page_data['page_name'] = 'teacher_diaries';
            $page_data['page_title'] = get_phrase('Teacher Diaries');
            $this->load->view('backend/index', $page_data);
        } catch (Exception $e) {
            $this->session->set_flashdata('error_message', get_phrase('Error loading diaries: ') . $e->getMessage());
            redirect(base_url() . 'admin/dashboard', 'refresh');
        }
    }
    
    function view_teacher_diary($diary_id) {
        if ($this->session->userdata('admin_login') != 1) {
            redirect(base_url(), 'refresh');
        }
        
        try {
            $this->load->model('teacher_diary_model');
            
            $page_data['diary'] = $this->teacher_diary_model->get_diary($diary_id);
            
            if (empty($page_data['diary'])) {
                $this->session->set_flashdata('error_message', get_phrase('Diary not found'));
                redirect(base_url() . 'admin/teacher_diaries', 'refresh');
            }
            
            // Get teacher name
            $this->db->where('teacher_id', $page_data['diary']['teacher_id']);
            $teacher = $this->db->get('teacher')->row_array();
            
            if (empty($teacher)) {
                $page_data['teacher_name'] = get_phrase('Unknown Teacher');
            } else {
                $page_data['teacher_name'] = $teacher['name'];
            }
            
            $page_data['page_name'] = 'view_teacher_diary';
            $page_data['page_title'] = get_phrase('View Teacher Diary');
            $this->load->view('backend/index', $page_data);
        } catch (Exception $e) {
            $this->session->set_flashdata('error_message', get_phrase('Error viewing diary: ') . $e->getMessage());
            redirect(base_url() . 'admin/teacher_diaries', 'refresh');
        }
    }

    /* Update timetable entry via AJAX */
    function update_timetable_ajax() {
        if ($this->session->userdata('admin_login') != 1) {
            echo json_encode(array('status' => 'error', 'message' => 'Access denied'));
            return;
        }
        
        // Get POST data
        $timetable_id = $this->input->post('timetable_id');
        $day = $this->input->post('day');
        $starting_time = $this->input->post('starting_time');
        $ending_time = $this->input->post('ending_time');
        
        // Validate inputs
        if (empty($timetable_id) || empty($day) || empty($starting_time) || empty($ending_time)) {
            echo json_encode(array('status' => 'error', 'message' => 'Missing required parameters'));
            return;
        }
        
        // Load timetable model if needed
        if (!isset($this->timetable_model)) {
            $this->load->model('timetable_model');
        }
        
        // Get current timetable entry to keep other data unchanged
        $current_entry = $this->db->get_where('timetable', array('timetable_id' => $timetable_id))->row_array();
        if (empty($current_entry)) {
            echo json_encode(array('status' => 'error', 'message' => 'Timetable entry not found'));
            return;
        }
        
        // Update data
        $data = array(
            'day' => $day,
            'starting_time' => $starting_time,
            'ending_time' => $ending_time
        );
        
        // Check for conflicts
        $conflict = $this->timetable_model->check_timetable_conflict(
            array_merge($current_entry, $data),
            $timetable_id
        );
        
        if ($conflict) {
            echo json_encode(array('status' => 'error', 'message' => 'Time conflict with another class or teacher'));
            return;
        }
        
        // Update entry
        $this->db->where('timetable_id', $timetable_id);
        $this->db->update('timetable', $data);
        
        // Return success
        echo json_encode(array('status' => 'success', 'message' => 'Timetable updated successfully'));
    }
    
    /* Delete timetable entry via AJAX */
    function delete_timetable_ajax($timetable_id = '') {
        if ($this->session->userdata('admin_login') != 1) {
            echo json_encode(array('status' => 'error', 'message' => 'Access denied'));
            return;
        }
        
        // Validate input
        if (empty($timetable_id)) {
            echo json_encode(array('status' => 'error', 'message' => 'Missing timetable ID'));
            return;
        }
        
        // Check if entry exists
        $entry = $this->db->get_where('timetable', array('timetable_id' => $timetable_id))->row_array();
        if (empty($entry)) {
            echo json_encode(array('status' => 'error', 'message' => 'Timetable entry not found'));
            return;
        }
        
        // Delete entry
        $this->db->where('timetable_id', $timetable_id);
        $this->db->delete('timetable');
        
        // Return success
        echo json_encode(array('status' => 'success', 'message' => 'Timetable deleted successfully'));
    }

    // Calendar Timetable View
    public function calendar_timetable($param1 = '', $param2 = '', $param3 = '') {
        if ($this->session->userdata('admin_login') != 1) {
            redirect(base_url(), 'refresh');
        }
        
        $page_data['page_name'] = 'calendar_timetable';
        $page_data['page_title'] = get_phrase('class_timetable');
        
        // Get classes and teachers for dropdowns
        $page_data['classes'] = $this->db->get('class')->result_array();
        $page_data['teachers'] = $this->db->get('teacher')->result_array();
        
        $this->load->view('backend/index', $page_data);
    }
    
    // Get timetable data for calendar view
    public function get_timetable_data_ajax() {
        if (!$this->session->userdata('admin_login') && !$this->session->userdata('teacher_login')) {
            echo json_encode(['error' => 'Access denied']);
            return;
        }

        try {
            $class_id = $this->input->post('class_id');
            $teacher_id = $this->session->userdata('teacher_login') == 1 ? $this->session->userdata('teacher_id') : $this->input->post('teacher_id');
            
            // Log input parameters
            error_log("get_timetable_data_ajax called with: class_id=$class_id, teacher_id=$teacher_id");
            
            // First check if timetable table exists
            if (!$this->db->table_exists('timetable')) {
                error_log("Timetable table does not exist");
                echo json_encode([]);
                return;
            }
            
            // Use a direct query to get timetable data including names from related tables
            $this->db->select('t.timetable_id, t.class_id, t.section_id, t.subject_id, t.teacher_id, 
                               t.start_date, t.end_date, t.start_time, t.end_time, 
                               c.name as class_name, s.name as section_name, 
                               sub.name as subject_name, tea.name as teacher_name');
            $this->db->from('timetable t');
            $this->db->join('class c', 'c.class_id = t.class_id');
            $this->db->join('section s', 's.section_id = t.section_id');
            $this->db->join('subject sub', 'sub.subject_id = t.subject_id');
            $this->db->join('teacher tea', 'tea.teacher_id = t.teacher_id');
            
            // Add filters
            if ($class_id) {
                $this->db->where('t.class_id', $class_id);
            }
            
            if ($teacher_id) {
                $this->db->where('t.teacher_id', $teacher_id);
            }
            
            $query = $this->db->get();
            
            // Log the query
            error_log("Timetable query: " . $this->db->last_query());
            
            if (!$query) {
                error_log("Query failed: " . $this->db->error()['message']);
                echo json_encode([]);
                return;
            }
            
            $events = $query->result_array();
            
            // Log results count
            error_log("Timetable results: " . count($events) . " entries found");
            
            if (count($events) === 0) {
                // Check if we have any records in the timetable table
                $total_count = $this->db->count_all('timetable');
                error_log("Total records in timetable table: $total_count");
            } else {
                // Log the first result for debugging
                error_log("First result: " . json_encode($events[0]));
            }
            
            // Format events for FullCalendar
            $calendar_events = array_map(function($event) {
                return [
                    'id' => $event['timetable_id'],
                    'title' => $event['subject_name'] . ' (' . $event['teacher_name'] . ')',
                    'start' => $event['start_date'] . 'T' . $event['start_time'],
                    'end' => $event['end_date'] . 'T' . $event['end_time'],
                    'className' => 'bg-info',
                    'description' => sprintf(
                        'Class: %s<br>Section: %s<br>Subject: %s<br>Teacher: %s',
                        $event['class_name'],
                        $event['section_name'],
                        $event['subject_name'],
                        $event['teacher_name']
                    ),
                    // Add these fields for edit mode
                    'class_id' => $event['class_id'],
                    'section_id' => $event['section_id'],
                    'subject_id' => $event['subject_id'],
                    'teacher_id' => $event['teacher_id']
                ];
            }, $events);
            
            echo json_encode($calendar_events);
        } catch (Exception $e) {
            error_log("Exception in get_timetable_data_ajax: " . $e->getMessage());
            echo json_encode([]);
        }
    }
    
    // Save timetable slot
    public function save_timetable_slot_ajax() {
        if (!$this->session->userdata('admin_login')) {
            echo json_encode(['status' => 'error', 'message' => 'Access denied']);
            return;
        }

        try {
            $data = [
                'class_id' => $this->input->post('class_id'),
                'section_id' => $this->input->post('section_id'),
                'subject_id' => $this->input->post('subject_id'),
                'teacher_id' => $this->input->post('teacher_id'),
                'date' => $this->input->post('start_date'),
                'start_time' => $this->input->post('start_time'),
                'end_time' => $this->input->post('end_time')
            ];

            // Validate required fields
            foreach ($data as $key => $value) {
                if (empty($value)) {
                    echo json_encode(['status' => 'error', 'message' => ucfirst($key) . ' is required']);
                    return;
                }
            }

            // Load the timetable model
            $this->load->model('timetable_model');

            // Check for conflicts
            if ($this->timetable_model->check_timetable_conflict($data)) {
                echo json_encode(['status' => 'error', 'message' => 'Time slot conflicts with existing schedule']);
                return;
            }

            // Save the timetable entry
            $timetable_id = $this->input->post('timetable_id');
            if ($timetable_id) {
                // Update existing entry
                $success = $this->timetable_model->update_timetable($timetable_id, $data);
                $message = 'Timetable updated successfully';
            } else {
                // Add new entry
                $success = $this->timetable_model->add_timetable($data);
                $message = 'Timetable added successfully';
            }

            if ($success) {
                echo json_encode(['status' => 'success', 'message' => $message]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to save timetable']);
            }

        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()]);
        }
    }
    
    // Delete timetable slot
    public function delete_timetable_slot_ajax() {
        // Check login status
        if (!$this->session->userdata('admin_login')) {
            echo json_encode(['status' => 'error', 'message' => 'Access denied']);
            return;
        }
        
        try {
            $timetable_id = $this->input->post('timetable_id');
            
            if (empty($timetable_id)) {
                echo json_encode(['status' => 'error', 'message' => 'Timetable ID is required']);
                return;
            }
            
            // Log the delete operation
            error_log("Deleting timetable ID: $timetable_id");
            
            // Delete from timetable table - note that we use timetable_id not id
            $this->db->where('timetable_id', $timetable_id);
            $deleted = $this->db->delete('timetable');
            
            if ($deleted) {
                echo json_encode(['status' => 'success', 'message' => 'Timetable entry deleted successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to delete timetable entry']);
            }
        } catch (Exception $e) {
            error_log("Error deleting timetable: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    // Get sections by class for AJAX request
    public function get_sections_by_class($class_id) {
        $sections = $this->db->get_where('section', array('class_id' => $class_id))->result_array();
        $html = '<option value="">' . get_phrase('select_section') . '</option>';
        foreach($sections as $row) {
            $html .= '<option value="' . $row['section_id'] . '">' . $row['name'] . '</option>';
        }
        echo $html;
    }

    // Get subjects by class for AJAX request
    public function get_subjects_by_class($class_id) {
        $subjects = $this->db->get_where('subject', array('class_id' => $class_id))->result_array();
        $html = '<option value="">' . get_phrase('select_subject') . '</option>';
        foreach($subjects as $row) {
            $html .= '<option value="' . $row['subject_id'] . '">' . $row['name'] . '</option>';
        }
        echo $html;
    }

    // Get sections for a class
    public function get_sections($class_id) {
        $sections = $this->db->get_where('section', array('class_id' => $class_id))->result_array();
        echo '<option value="">Select Section</option>';
        foreach($sections as $row) {
            echo '<option value="'.$row['section_id'].'">'.$row['name'].'</option>';
        }
    }

    // Get subjects for a class
    public function get_subjects($class_id) {
        $subjects = $this->db->get_where('subject', array('class_id' => $class_id))->result_array();
        echo '<option value="">Select Subject</option>';
        foreach($subjects as $row) {
            echo '<option value="'.$row['subject_id'].'">'.$row['name'].'</option>';
        }
    }

    public function save_timetable_ajax() {
        // Set content type and headers
        header('Content-Type: application/json');
        
        // Check login status
        if (!$this->session->userdata('admin_login')) {
            echo json_encode(['status' => 'error', 'message' => 'Access denied']);
            return;
        }

        try {
            // Get POST data
            $class_id = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');
            $subject_id = $this->input->post('subject_id');
            $teacher_id = $this->input->post('teacher_id');
            $start_date = $this->input->post('start_date');
            $end_date = $this->input->post('end_date');
            $start_time = $this->input->post('start_time');
            $end_time = $this->input->post('end_time');
            $timetable_id = $this->input->post('timetable_id');
            
            // Log received data
            error_log("Received data: " . json_encode($_POST));
            
            // Validate required fields
            if (empty($class_id) || empty($section_id) || empty($subject_id) || 
                empty($teacher_id) || empty($start_date) || empty($end_date) || 
                empty($start_time) || empty($end_time)) {
                echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
                return;
            }
            
            // Prepare data for database
            $data = array(
                'class_id' => $class_id,
                'section_id' => $section_id,
                'subject_id' => $subject_id,
                'teacher_id' => $teacher_id,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'start_time' => $start_time,
                'end_time' => $end_time
            );
            
            // Start transaction
            $this->db->trans_start();
            
            if (!empty($timetable_id)) {
                // Update existing record
                $this->db->where('timetable_id', $timetable_id);
                $this->db->update('timetable', $data);
                $message = 'Timetable updated successfully';
            } else {
                // Insert new record
                $this->db->insert('timetable', $data);
                $inserted_id = $this->db->insert_id();
                $message = 'Timetable added successfully';
            }
            
            // Complete transaction
            $this->db->trans_complete();
            
            if ($this->db->trans_status() === FALSE) {
                // Transaction failed
                echo json_encode(['status' => 'error', 'message' => 'Database error occurred']);
            } else {
                // Success
                echo json_encode(['status' => 'success', 'message' => $message]);
            }
            
        } catch (Exception $e) {
            // Log and return error
            error_log('Error in save_timetable_ajax: ' . $e->getMessage());
            echo json_encode([
                'status' => 'error',
                'message' => 'An error occurred: ' . $e->getMessage()
            ]);
        }
    }

    // View teacher timetable
    public function teacher_timetable($teacher_id = '') {
        if ($this->session->userdata('admin_login') != 1) {
            redirect(base_url(), 'refresh');
        }
        
        $page_data['page_name'] = 'teacher_timetable';
        $page_data['page_title'] = get_phrase('teacher_timetable');
        $page_data['teacher_id'] = $teacher_id;
        
        // Get teacher details
        if (!empty($teacher_id)) {
            $page_data['teacher'] = $this->db->get_where('teacher', ['teacher_id' => $teacher_id])->row_array();
        }
        
        $this->load->view('backend/index', $page_data);
    }

    // Inside Admin class, after the calendar_timetable method

    // AJAX endpoint for getting timetable data
    public function get_calendar_timetable_data() {
        if ($this->session->userdata('admin_login') != 1) {
            echo json_encode(['status' => 'error', 'message' => get_phrase('access_denied')]);
            return;
        }
        
        try {
            $class_id = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');
            
            // Validate inputs
            if (!$class_id || !$section_id) {
                echo json_encode(['status' => 'error', 'message' => get_phrase('please_select_class_and_section')]);
                return;
            }
            
            // Get timetable entries
            $this->db->select('calendar_timetable.*, subject.name as subject_name, teacher.name as teacher_name');
            $this->db->from('calendar_timetable');
            $this->db->join('subject', 'subject.subject_id = calendar_timetable.subject_id');
            $this->db->join('teacher', 'teacher.teacher_id = calendar_timetable.teacher_id');
            $this->db->where('calendar_timetable.class_id', $class_id);
            $this->db->where('calendar_timetable.section_id', $section_id);
            $this->db->order_by('calendar_timetable.day_of_week', 'ASC');
            $this->db->order_by('calendar_timetable.time_slot_start', 'ASC');
            
            $entries = $this->db->get()->result_array();
            
            echo json_encode($entries);
        } catch (Exception $e) {
            log_message('error', 'Error in get_calendar_timetable_data: ' . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => get_phrase('error_loading_timetable')]);
        }
    }

    // AJAX endpoint for saving a calendar timetable entry
    public function save_calendar_timetable_entry() {
        if ($this->session->userdata('admin_login') != 1) {
            echo json_encode(['status' => 'error', 'message' => get_phrase('access_denied')]);
            return;
        }
        
        try {
            $data = [
                'class_id' => $this->input->post('class_id'),
                'section_id' => $this->input->post('section_id'),
                'subject_id' => $this->input->post('subject_id'),
                'teacher_id' => $this->input->post('teacher_id'),
                'day_of_week' => $this->input->post('day_of_week'),
                'time_slot_start' => $this->input->post('time_slot_start'),
                'time_slot_end' => $this->input->post('time_slot_end'),
                'room_number' => $this->input->post('room_number')
            ];
            
            // Validate required fields
            $required_fields = ['class_id', 'section_id', 'subject_id', 'teacher_id', 'day_of_week', 'time_slot_start', 'time_slot_end'];
            foreach ($required_fields as $field) {
                if (empty($data[$field])) {
                    echo json_encode(['status' => 'error', 'message' => get_phrase('please_fill_all_required_fields')]);
                    return;
                }
            }
            
            // Check for time slot conflicts
            $this->db->where('class_id', $data['class_id']);
            $this->db->where('section_id', $data['section_id']);
            $this->db->where('day_of_week', $data['day_of_week']);
            $this->db->group_start();
            $this->db->where("(time_slot_start < '{$data['time_slot_end']}' AND time_slot_end > '{$data['time_slot_start']}')");
            $this->db->group_end();
            
            if ($this->input->post('id')) {
                $this->db->where('id !=', $this->input->post('id'));
            }
            
            $existing = $this->db->get('calendar_timetable')->num_rows();
            
            if ($existing > 0) {
                echo json_encode(['status' => 'error', 'message' => get_phrase('time_slot_conflict')]);
                return;
            }
            
            // Save or update
            if ($this->input->post('id')) {
                $this->db->where('id', $this->input->post('id'));
                $this->db->update('calendar_timetable', $data);
                $message = get_phrase('timetable_entry_updated_successfully');
            } else {
                $this->db->insert('calendar_timetable', $data);
                $message = get_phrase('timetable_entry_added_successfully');
            }
            
            echo json_encode(['status' => 'success', 'message' => $message]);
        } catch (Exception $e) {
            log_message('error', 'Error in save_calendar_timetable_entry: ' . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => get_phrase('error_saving_timetable')]);
        }
    }

    // AJAX endpoint for deleting a calendar timetable entry
    public function delete_calendar_timetable_entry() {
        if ($this->session->userdata('admin_login') != 1) {
            echo json_encode(['status' => 'error', 'message' => get_phrase('access_denied')]);
            return;
        }
        
        try {
            $id = $this->input->post('id');
            
            if (!$id || !is_numeric($id)) {
                echo json_encode(['status' => 'error', 'message' => get_phrase('invalid_entry_id')]);
                return;
            }
            
            $this->db->where('id', $id);
            $this->db->delete('calendar_timetable');
            
            if ($this->db->affected_rows() > 0) {
                echo json_encode(['status' => 'success', 'message' => get_phrase('timetable_entry_deleted_successfully')]);
            } else {
                echo json_encode(['status' => 'error', 'message' => get_phrase('entry_not_found')]);
            }
        } catch (Exception $e) {
            log_message('error', 'Error in delete_calendar_timetable_entry: ' . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => get_phrase('error_deleting_entry')]);
        }
    }

}
