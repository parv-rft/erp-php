<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Studymaterial extends CI_Controller { 

    function __construct() {
        parent::__construct();
        		$this->load->database();
                $this->load->library('session');
                $this->load->model('material_model');
    }

    // The function below manage study material //
    function study_material($param1 = '', $param2 = '', $param3 = ''){

        if ($param1 == 'insert'){
        
        $this->material_model->insertIntoMaterial();
        $this->session->set_flashdata('flash_message', get_phrase('Data successfully saved'));
        redirect(base_url(). 'studymaterial/study_material', 'refresh');
        }
        
    if($param1 == 'update'){

        $this->material_model->updateStudyMaterial($param2);
        $this->session->set_flashdata('flash_message', get_phrase('Data successfully updated'));
        redirect(base_url(). 'studymaterial/study_material', 'refresh');
    }

    if($param1 == 'delete'){
        $this->material_model->deleteFromMaterial($param2);
        $this->session->set_flashdata('flash_message', get_phrase('Data successfully deleted'));
        redirect(base_url(). 'studymaterial/study_material', 'refresh');
    }

        $page_data['page_name']         = 'study_material';
        $page_data['page_title']        = get_phrase('Study Material');
        $this->load->view('backend/index', $page_data);


    }

    // Function to handle study material file download/preview
    function download($material_id = null)
    {
        if ($material_id == null) {
            show_404();
        }

        // Fetch the material record
        $material = $this->db->get_where('material', array('material_id' => $material_id))->row();

        if ($material && !empty($material->file_name)) {
            // Construct the file path using FCPATH for reliability
            $file_path = FCPATH . 'uploads/study_material/' . $material->file_name;

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
                log_message('error', 'Study material file not found or not readable at path: ' . $file_path);
                show_error('The requested file could not be found or accessed on the server.', 404);
            }
        } else {
            // Material record not found or has no file
            log_message('error', 'Study material record not found or has no file_name for ID: ' . $material_id);
            show_404();
        }
    }
}