<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Assignment_model extends CI_Model { 
	
	function __construct()
    {
        parent::__construct();
    }
	
   // The function below inserts into assignment table //
   function insertAssignment(){
    $page_data = array(
        'name'                      => $this->input->post('name'),
        'description'               => $this->input->post('description'),
        'class_id'                  => $this->input->post('class_id'),
        'subject_id'                => $this->input->post('subject_id'),
        'teacher_id'                => $this->input->post('teacher_id'),
        'timestamp'                 => $this->input->post('timestamp'),
        'file_type'                 => $this->input->post('file_type')
    );
    

    //uploading file using codeigniter upload library
    $this->load->library('upload');
    $config['upload_path'] = './uploads/assignment/'; // Added ./ for relative path consistency
    $config['allowed_types'] = '*';
    // It's generally better to let CI handle the $_FILES array directly if possible
    // but we'll keep the manual reconstruction for now if it was working before.
    /* 
    $files = $_FILES['file_name']; 
    $_FILES['file_name']['name'] = $files['name'];
    $_FILES['file_name']['type'] = $files['type'];
    $_FILES['file_name']['tmp_name'] = $files['tmp_name'];
    $_FILES['file_name']['size'] = $files['size'];
    */
    $this->upload->initialize($config);

    if ($this->upload->do_upload('file_name')) {
        // Upload successful, get file data
        $upload_data = $this->upload->data();
        $page_data['file_name'] = $upload_data['file_name'];
    } else {
        // Upload failed, stop and display error for debugging
        $error = array('error' => $this->upload->display_errors());
        // In a real application, you would handle this more gracefully 
        // (e.g., redirect back with error message)
        die('Upload Error: ' . print_r($error, true)); 
    }

    $this->db->insert('assignment', $page_data);
}

 // The function below updates assignment table //
 function updateAssignment($param2){
    $page_data = array(
        'name'                      => $this->input->post('name'),
        'description'               => $this->input->post('description'),
        'class_id'                  => $this->input->post('class_id'),
        'subject_id'                => $this->input->post('subject_id'),
        'teacher_id'                => $this->input->post('teacher_id'),
        'timestamp'                 => $this->input->post('timestamp'),
        'file_type'                 => $this->input->post('file_type')
    );

$this->db->where('assignment_id', $param2);
$this->db->update('assignment', $page_data);
}

// The function below delete from assignment table //
function deleteAssignment($param2){
    $this->db->where('assignment_id', $param2);
    $this->db->delete('assignment');
}


	
	
}

