<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PrintInvoice extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('form');
    }
    
    // Check if user is logged in
    private function is_logged_in() {
        if (!$this->session->userdata('login_type')) {
            redirect(base_url() . 'login', 'refresh');
            return false;
        }
        return true;
    }
    
    /**
     * Default method - redirects to invoice method
     */
    public function index() {
        redirect(base_url() . 'login', 'refresh');
    }
    
    /**
     * Print invoice directly
     * 
     * @param string $invoice_id The invoice ID to print
     */
    public function invoice($invoice_id = '') {
        try {
            // Validate login
            if (!$this->is_logged_in()) return;
            
            // Validate invoice ID
            if (empty($invoice_id)) {
                show_error('Invalid Invoice ID. Please provide a valid invoice ID.', 400, 'Invoice Error');
                return;
            }
            
            // Check if invoice exists
            $invoice = $this->db->get_where('invoice', array('invoice_id' => $invoice_id))->row();
            if (!$invoice) {
                show_error('Invoice not found. The requested invoice could not be found.', 404, 'Invoice Not Found');
                return;
            }
            
            // Load the view with data
            $page_data['invoice_id'] = $invoice_id;
            $this->load->view('backend/admin/print_invoice', $page_data);
            
        } catch (Exception $e) {
            // Log the error
            error_log('Error in PrintInvoice::invoice: ' . $e->getMessage());
            
            // Show user-friendly error
            show_error('An error occurred while processing your request. Please try again later.', 500, 'System Error');
        }
    }
    
    /**
     * Handle 404 errors
     */
    public function error_404() {
        $this->output->set_status_header('404');
        $data['heading'] = "404 Not Found";
        $data['message'] = "The page you requested was not found.";
        $this->load->view('errors/html/error_404', $data);
    }
} 