<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modal extends CI_Controller {

	
	function __construct()
    {
        parent::__construct();
		$this->load->database();
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->helper('form');
    }
	
	public function index()
	{
		
	}
	
	

	function popup($page_name = '' , $param2 = '' , $param3 = '')
	{
		try {
			// Check if user is logged in
			if (!$this->session->userdata('login_type')) {
				echo json_encode(array('error' => 'You are not logged in'));
				return;
			}
			
			$account_type = $this->session->userdata('login_type');
			$page_data['param2'] = $param2;
			$page_data['param3'] = $param3;
			
			// Validate the page_name parameter
			if (empty($page_name)) {
				echo json_encode(array('error' => 'Invalid page name'));
				return;
			}
			
			// Special handling for timetable_add which is in modal directory
			if ($page_name == 'timetable_add') {
				if (file_exists(APPPATH . 'views/backend/modal/' . $page_name . '.php')) {
					$this->load->view('backend/modal/' . $page_name . '.php', $page_data);
					return;
				}
			}
			
			// Check if the view file exists
			$view_path = APPPATH . 'views/backend/' . $account_type . '/' . $page_name . '.php';
			$modal_view_path = APPPATH . 'views/backend/modal/' . $page_name . '.php';
			
			if (file_exists($view_path)) {
				$this->load->view('backend/' . $account_type . '/' . $page_name . '.php', $page_data);
			} else if (file_exists($modal_view_path)) {
				// Try loading from modal directory if the file isn't in account type directory
				$this->load->view('backend/modal/' . $page_name . '.php', $page_data);
			} else {
				// View file doesn't exist
				log_message('error', 'Modal view not found: ' . $view_path . ' or ' . $modal_view_path);
				echo json_encode(array('error' => 'Modal view not found: ' . $page_name));
			}
		} catch (Exception $e) {
			log_message('error', 'Error in modal popup: ' . $e->getMessage());
			echo json_encode(array('error' => 'An error occurred: ' . $e->getMessage()));
		}
	}
}

