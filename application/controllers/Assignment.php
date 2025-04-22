<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Assignment extends CI_Controller { 

    function __construct() {
        parent::__construct();
        		$this->load->database();
                $this->load->library('session');
                $this->load->model('assignment_model');
    }

    // The function below manage assignment //
    function assignment($param1 = null, $param2 = null, $param3 = null){

        if ($param1 == 'insert'){
        
        $this->assignment_model->insertAssignment();
        $this->session->set_flashdata('flash_message', get_phrase('Data successfully saved'));
        redirect(base_url(). 'assignment/assignment', 'refresh');
        }
        
    if($param1 == 'update'){

        $this->assignment_model->updateAssignment($param2);
        $this->session->set_flashdata('flash_message', get_phrase('Data successfully updated'));
        redirect(base_url(). 'assignment/assignment', 'refresh');
    }

    if($param1 == 'delete'){
        $this->assignment_model->deleteAssignment($param2);
        $this->session->set_flashdata('flash_message', get_phrase('Data successfully deleted'));
        redirect(base_url(). 'assignment/assignment', 'refresh');
    }

        $page_data['page_name']         = 'assignment';
        $page_data['page_title']        = get_phrase('Manage Assignment');
        $this->load->view('backend/index', $page_data);


    }

    // Function to handle assignment file download/preview
    function download($assignment_id = null)
    {
        if ($assignment_id == null) {
            show_404();
        }

        // Fetch the assignment record
        $assignment = $this->db->get_where('assignment', array('assignment_id' => $assignment_id))->row();

        if ($assignment && !empty($assignment->file_name)) {
            // Construct the file path using FCPATH for reliability
            $file_path = FCPATH . 'uploads/assignment/' . $assignment->file_name;

            // Check if file exists and is readable
            if (file_exists($file_path) && is_readable($file_path)) {
                
                // Load file helper for mime type detection
                $this->load->helper('file');

                // Get MIME type
                $mime = get_mime_by_extension($file_path);
                // Set a default mime type if detection fails
                if ($mime === false) {
                    $mime = 'application/octet-stream';
                }

                // Set headers for inline display (preview)
                header('Content-Type: ' . $mime);
                header('Content-Disposition: inline; filename="' . basename($file_path) . '"'); // Use inline
                header('Content-Length: ' . filesize($file_path));
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');

                // Clean output buffer
                if (ob_get_level()) {
                  ob_end_clean();
                }
                
                // Read the file and output its contents
                readfile($file_path);
                exit; // Stop script execution after sending file

            } else {
                // File not found or not readable
                log_message('error', 'Assignment file not found or not readable at path: ' . $file_path);
                show_error('The requested file could not be found or accessed on the server.', 404); // Updated error message
            }
        } else {
            // Assignment record not found or has no file
            log_message('error', 'Assignment record not found or has no file_name for ID: ' . $assignment_id);
            show_404();
        }
    }
}