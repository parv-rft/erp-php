<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Student_model extends CI_Model { 
	
	function __construct()
    {
        parent::__construct();
    }



    // The function below insert into student house //
    function createStudentHouse(){

        $page_data = array(
            'name'          => html_escape($this->input->post('name')),
            'description'      => html_escape($this->input->post('description'))
	    );

        $this->db->insert('house', $page_data);
    }

// The function below update student house //
    function updateStudentHouse($param2){
        $page_data = array(
            'name'         => html_escape($this->input->post('name')),
            'description'  => html_escape($this->input->post('description'))
	    );

        $this->db->where('house_id', $param2);
        $this->db->update('house', $page_data);
    }

    // The function below delete from student house table //
    function deleteStudentHouse($param2){
        $this->db->where('house_id', $param2);
        $this->db->delete('house');
    }



    // The function below insert into student category //
    function createstudentCategory(){

        $page_data = array(
            'name'        => html_escape($this->input->post('name')),
            'description' => html_escape($this->input->post('description'))
	    );
        $this->db->insert('student_category', $page_data);
    }

// The function below update student category //
    function updatestudentCategory($param2){
        $page_data = array(
            'name'        => html_escape($this->input->post('name')),
            'description' => html_escape($this->input->post('description'))
	    );

        $this->db->where('student_category_id', $param2);
        $this->db->update('student_category', $page_data);
    }

    // The function below delete from student category table //
    function deletestudentCategory($param2){
        $this->db->where('student_category_id', $param2);
        $this->db->delete('student_category');
    }




    // Function to check if student_id already exists
    function check_student_id_exists($student_id, $current_id = null) {
        // If editing an existing student, exclude the current student from the check
        if ($current_id) {
            $this->db->where('student_id !=', $current_id);
        }
        
        $this->db->where('student_id', $student_id);
        $query = $this->db->get('student');
        
        return $query->num_rows() > 0;
    }
     
    //  the function below insert into student table
    function createNewStudent() {
        // Validate file upload
        if (empty($_FILES['userfile']['name'])) {
            $this->session->set_flashdata('error_message', get_phrase('Please select a student photo'));
            return false;
        }

        // Get file details
        $file = $_FILES['userfile'];
        $file_size = $file['size'];
        $file_type = $file['type'];
        
        // Validate file size (5MB)
        if ($file_size > 5 * 1024 * 1024) {
            $this->session->set_flashdata('error_message', get_phrase('File size exceeds 5MB limit'));
            return false;
        }

        // Validate file type
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!in_array($file_type, $allowed_types)) {
            $this->session->set_flashdata('error_message', get_phrase('Invalid file type. Only JPG, JPEG, and PNG are allowed'));
            return false;
        }

        // Create student data array
        $page_data = array(
            'admission_number' => html_escape($this->input->post('admission_no')),
            'name' => html_escape($this->input->post('name')),
            'birthday' => html_escape($this->input->post('birthday')),
            'age' => html_escape($this->input->post('age')),
            // 'place_birth' => html_escape($this->input->post('place_birth')),
            'sex' => html_escape($this->input->post('sex')),
            // 'm_tongue' => html_escape($this->input->post('m_tongue')),
            'religion' => html_escape($this->input->post('religion')),
            'blood_group' => html_escape($this->input->post('blood_group')),
            'address' => html_escape($this->input->post('address')),
            'city' => html_escape($this->input->post('city')),
            'state' => html_escape($this->input->post('state')),
                // 'nationality' => html_escape($this->input->post('nationality')),
            'phone' => html_escape($this->input->post('phone')),
            'email' => html_escape($this->input->post('student_email')),
            'password' => sha1($this->input->post('password')),
            'class_id' => html_escape($this->input->post('class_id')),
            'section_id' => html_escape($this->input->post('section_id')),
            'father_id' => html_escape($this->input->post('father_id')),
            
            // Father details
            'father_name' => html_escape($this->input->post('father_name')),
            'father_phone' => html_escape($this->input->post('father_phone')),
            'father_email' => html_escape($this->input->post('father_email')),
            'father_occupation' => html_escape($this->input->post('father_occupation')),
            'father_adhar' => html_escape($this->input->post('father_adhar')),
            'father_annual_income' => html_escape($this->input->post('father_annual_income')),
            'father_designation' => html_escape($this->input->post('father_designation')),
            'father_qualification' => html_escape($this->input->post('father_qualification')),
            
            // Mother details
            'mother_name' => html_escape($this->input->post('mother_name')),
            'mother_phone' => html_escape($this->input->post('mother_phone')),
            'mother_email' => html_escape($this->input->post('mother_email')),
            'mother_occupation' => html_escape($this->input->post('mother_occupation')),
            'mother_adhar' => html_escape($this->input->post('mother_adhar')),
            'mother_annual_income' => html_escape($this->input->post('mother_annual_income')),
            'mother_designation' => html_escape($this->input->post('mother_designation')),
            'mother_qualification' => html_escape($this->input->post('mother_qualification')),
            
            'roll' => html_escape($this->input->post('roll')),
            
            // Transport Information
            'transport_mode' => html_escape($this->input->post('transport_mode')),
            'transport_id' => html_escape($this->input->post('transport_id')),
            'pick_area' => html_escape($this->input->post('pick_area')),
            'pick_stand' => html_escape($this->input->post('pick_stand')),
            'pick_route_id' => html_escape($this->input->post('pick_route_id')),
            'pick_driver_id' => html_escape($this->input->post('pick_driver_id')),
            'drop_area' => html_escape($this->input->post('drop_area')),
            'drop_stand' => html_escape($this->input->post('drop_stand')),
            'drop_route_id' => html_escape($this->input->post('drop_route_id')),
            'drop_driver_id' => html_escape($this->input->post('drop_driver_id')),
            
            'dormitory_id' => html_escape($this->input->post('dormitory_id')),
            'house_id' => html_escape($this->input->post('house_id')),
            'student_category_id' => html_escape($this->input->post('student_category_id')),
            'club_id' => html_escape($this->input->post('club_id')),
            'session' => html_escape($this->input->post('session')),
            'student_code' => html_escape($this->input->post('student_code')),
            'apaar_id' => html_escape($this->input->post('apaar_id')),
            'admission_date' => html_escape($this->input->post('admission_date'))
        );
        
        // Handle transport months array
        if ($this->input->post('transport_months')) {
            $page_data['transport_months'] = json_encode($this->input->post('transport_months'));
        } else {
            $page_data['transport_months'] = json_encode(array());
        }

        // Begin transaction
        $this->db->trans_start();

        try {
            // Insert student data
            $this->db->insert('student', $page_data);
            $student_id = $this->db->insert_id();

            // Create upload directory if it doesn't exist
            $upload_path = 'uploads/student_image/';
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, true);
            }

            // Upload student photo
            $file_path = $upload_path . $student_id . '.jpg';
            if (!move_uploaded_file($file['tmp_name'], $file_path)) {
                throw new Exception('Failed to upload student photo');
            }
            
            // Upload father's photo if provided
            if (!empty($_FILES['father_image']['name'])) {
                $father_file = $_FILES['father_image'];
                $father_file_path = 'uploads/parent_image/' . $student_id . '_father.jpg';
                
                // Create directory if it doesn't exist
                if (!is_dir('uploads/parent_image/')) {
                    mkdir('uploads/parent_image/', 0777, true);
                }
                
                if (move_uploaded_file($father_file['tmp_name'], $father_file_path)) {
                    // Update the database with the father's photo path
                    $this->db->where('student_id', $student_id);
                    $this->db->update('student', array('father_photo' => $student_id . '_father.jpg'));
                }
            }
            
            // Upload mother's photo if provided
            if (!empty($_FILES['mother_image']['name'])) {
                $mother_file = $_FILES['mother_image'];
                $mother_file_path = 'uploads/parent_image/' . $student_id . '_mother.jpg';
                
                // Create directory if it doesn't exist
                if (!is_dir('uploads/parent_image/')) {
                    mkdir('uploads/parent_image/', 0777, true);
                }
                
                if (move_uploaded_file($mother_file['tmp_name'], $mother_file_path)) {
                    // Update the database with the mother's photo path
                    $this->db->where('student_id', $student_id);
                    $this->db->update('student', array('mother_photo' => $student_id . '_mother.jpg'));
                }
            }

            // Commit transaction
            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Database transaction failed');
            }

            $this->session->set_flashdata('flash_message', get_phrase('Student added successfully'));
            return true;

        } catch (Exception $e) {
            // Rollback transaction
            $this->db->trans_rollback();
            
            // Delete uploaded files if they exist
            if (file_exists($file_path)) {
                unlink($file_path);
            }
            
            if (isset($father_file_path) && file_exists($father_file_path)) {
                unlink($father_file_path);
            }
            
            if (isset($mother_file_path) && file_exists($mother_file_path)) {
                unlink($mother_file_path);
            }

            $this->session->set_flashdata('error_message', get_phrase('Error creating student: ') . $e->getMessage());
            return false;
        }
    }


    //the function below update student
    function updateNewStudent($param2){
        // Begin transaction
        $this->db->trans_start();
        
        try {
        $page_data = array(
            'admission_number' => html_escape($this->input->post('admission_no')),
                'name'           => html_escape($this->input->post('name')),
                'birthday'       => html_escape($this->input->post('birthday')),
                'age'            => html_escape($this->input->post('age')),
                // 'place_birth'    => html_escape($this->input->post('place_birth')),
                'sex'            => html_escape($this->input->post('sex')),
                // 'm_tongue'       => html_escape($this->input->post('m_tongue')),
                'religion'       => html_escape($this->input->post('religion')),
                'blood_group'    => html_escape($this->input->post('blood_group')),
                
                // Address Information
                'address'        => html_escape($this->input->post('address')),
                'city'           => html_escape($this->input->post('city')),
                'state'          => html_escape($this->input->post('state')),
                'pincode'        => html_escape($this->input->post('pincode')),
                'same_as_present' => $this->input->post('same_as_present') ? 1 : 0,
                'permanent_address' => html_escape($this->input->post('permanent_address')),
                'permanent_city' => html_escape($this->input->post('permanent_city')),
                'permanent_state' => html_escape($this->input->post('permanent_state')),
                'permanent_pincode' => html_escape($this->input->post('permanent_pincode')),
                
                // 'nationality'    => html_escape($this->input->post('nationality')),
                'phone'          => html_escape($this->input->post('phone')),
                'email'          => html_escape($this->input->post('email')),
                
                // Previous School Information
                // 'ps_attended'    => html_escape($this->input->post('ps_attended')),
                // 'ps_address'     => html_escape($this->input->post('ps_address')),
                // 'ps_purpose'     => html_escape($this->input->post('ps_purpose')),
                // 'class_study'    => html_escape($this->input->post('class_study')),
                // 'date_of_leaving' => html_escape($this->input->post('date_of_leaving')),
                'am_date'        => html_escape($this->input->post('am_date')),
                
                // Category Information
                'caste'          => html_escape($this->input->post('caste')),
                'admission_category' => html_escape($this->input->post('admission_category')),
                'student_category_id' => html_escape($this->input->post('student_category_id')),
                
                // Documents Information
                'tran_cert'      => html_escape($this->input->post('tran_cert')),
                'dob_cert'       => html_escape($this->input->post('dob_cert')),
                'mark_join'      => html_escape($this->input->post('mark_join')),
                // 'physical_h'     => html_escape($this->input->post('physical_h')),
                
                // Academic Information
                'class_id'       => html_escape($this->input->post('class_id')),
                'section_id'     => html_escape($this->input->post('section_id')),
                'father_id'      => html_escape($this->input->post('father_id')),
                
                // Transport Information
                'transport_mode' => html_escape($this->input->post('transport_mode')),
                'transport_id'   => html_escape($this->input->post('transport_id')),
                'pick_area'      => html_escape($this->input->post('pick_area')),
                'pick_stand'     => html_escape($this->input->post('pick_stand')),
                'pick_route_id'  => html_escape($this->input->post('pick_route_id')),
                'pick_driver_id' => html_escape($this->input->post('pick_driver_id')),
                'drop_area'      => html_escape($this->input->post('drop_area')),
                'drop_stand'     => html_escape($this->input->post('drop_stand')),
                'drop_route_id'  => html_escape($this->input->post('drop_route_id')),
                'drop_driver_id' => html_escape($this->input->post('drop_driver_id')),
                
                // Other information
                'dormitory_id'   => html_escape($this->input->post('dormitory_id')),
                'house_id'       => html_escape($this->input->post('house_id')),
                'club_id'        => html_escape($this->input->post('club_id')),
                'student_code'   => html_escape($this->input->post('student_code')),
                'session'        => html_escape($this->input->post('session')),
                
                // Additional IDs
                'apaar_id'       => html_escape($this->input->post('apaar_id')),
                'adhar_no'       => html_escape($this->input->post('adhar_no')),
                'admission_date' => html_escape($this->input->post('admission_date')),
                'date_of_joining' => html_escape($this->input->post('date_of_joining'))
            );
            
            // Handle transport months array
            if ($this->input->post('transport_months')) {
                $page_data['transport_months'] = json_encode($this->input->post('transport_months'));
            } else {
                $page_data['transport_months'] = json_encode(array());
            }
            
            // Update password only if provided
            if ($this->input->post('password') != '') {
                $page_data['password'] = sha1($this->input->post('password'));
            }
        
        $this->db->where('student_id', $param2);
        $this->db->update('student', $page_data);
        
        // Upload document files directory
        $upload_path = 'uploads/student_documents/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
        }
        
        // Upload student photo if provided
        if (!empty($_FILES['userfile']['name'])) {
            $file = $_FILES['userfile'];
            $file_type = $file['type'];
            
            // Validate file type
            $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
            if (in_array($file_type, $allowed_types)) {
                move_uploaded_file($file['tmp_name'], 'uploads/student_image/' . $param2 . '.jpg');
            }
        }
            
        // Upload transfer certificate if provided
        if (!empty($_FILES['transfer_certificate']['name'])) {
            $file = $_FILES['transfer_certificate'];
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $file_path = $upload_path . $param2 . '_transfer_certificate.' . $ext;
            
            if (move_uploaded_file($file['tmp_name'], $file_path)) {
                $this->db->where('student_id', $param2);
                $this->db->update('student', array('transfer_certificate' => $param2 . '_transfer_certificate.' . $ext));
            }
        }
        
        // Upload student signature if provided
        if (!empty($_FILES['signature']['name'])) {
            $file = $_FILES['signature'];
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $file_path = $upload_path . $param2 . '_signature.' . $ext;
            
            if (move_uploaded_file($file['tmp_name'], $file_path)) {
                $this->db->where('student_id', $param2);
                $this->db->update('student', array('signature' => $param2 . '_signature.' . $ext));
            }
        }
        
        // Upload father's adhaar card if provided
        if (!empty($_FILES['father_adharcard']['name'])) {
            $file = $_FILES['father_adharcard'];
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $file_path = $upload_path . $param2 . '_father_adharcard.' . $ext;
            
            if (move_uploaded_file($file['tmp_name'], $file_path)) {
                $this->db->where('student_id', $param2);
                $this->db->update('student', array('father_adharcard' => $param2 . '_father_adharcard.' . $ext));
            }
        }
        
        // Upload mother's adhaar card if provided
        if (!empty($_FILES['mother_adharcard']['name'])) {
            $file = $_FILES['mother_adharcard'];
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $file_path = $upload_path . $param2 . '_mother_adharcard.' . $ext;
            
            if (move_uploaded_file($file['tmp_name'], $file_path)) {
                $this->db->where('student_id', $param2);
                $this->db->update('student', array('mother_adharcard' => $param2 . '_mother_adharcard.' . $ext));
            }
        }
        
        // Upload income certificate if provided
        if (!empty($_FILES['income_certificate']['name'])) {
            $file = $_FILES['income_certificate'];
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $file_path = $upload_path . $param2 . '_income_certificate.' . $ext;
            
            if (move_uploaded_file($file['tmp_name'], $file_path)) {
                $this->db->where('student_id', $param2);
                $this->db->update('student', array('income_certificate' => $param2 . '_income_certificate.' . $ext));
            }
        }
        
        // Upload DOB proof if provided
        if (!empty($_FILES['dob_proof']['name'])) {
            $file = $_FILES['dob_proof'];
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $file_path = $upload_path . $param2 . '_dob_proof.' . $ext;
            
            if (move_uploaded_file($file['tmp_name'], $file_path)) {
                $this->db->where('student_id', $param2);
                $this->db->update('student', array('dob_proof' => $param2 . '_dob_proof.' . $ext));
            }
        }
        
        // Upload migration certificate if provided
        if (!empty($_FILES['migration_certificate']['name'])) {
            $file = $_FILES['migration_certificate'];
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $file_path = $upload_path . $param2 . '_migration_certificate.' . $ext;
            
            if (move_uploaded_file($file['tmp_name'], $file_path)) {
                $this->db->where('student_id', $param2);
                $this->db->update('student', array('migration_certificate' => $param2 . '_migration_certificate.' . $ext));
            }
        }
        
        // Upload caste certificate if provided
        if (!empty($_FILES['caste_certificate']['name'])) {
            $file = $_FILES['caste_certificate'];
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $file_path = $upload_path . $param2 . '_caste_certificate.' . $ext;
            
            if (move_uploaded_file($file['tmp_name'], $file_path)) {
                $this->db->where('student_id', $param2);
                $this->db->update('student', array('caste_certificate' => $param2 . '_caste_certificate.' . $ext));
            }
        }
        
        // Upload aadhar card if provided
        if (!empty($_FILES['aadhar_card']['name'])) {
            $file = $_FILES['aadhar_card'];
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $file_path = $upload_path . $param2 . '_aadhar_card.' . $ext;
            
            if (move_uploaded_file($file['tmp_name'], $file_path)) {
                $this->db->where('student_id', $param2);
                $this->db->update('student', array('aadhar_card' => $param2 . '_aadhar_card.' . $ext));
            }
        }
        
        // Upload address proof if provided
        if (!empty($_FILES['address_proof']['name'])) {
            $file = $_FILES['address_proof'];
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $file_path = $upload_path . $param2 . '_address_proof.' . $ext;
            
            if (move_uploaded_file($file['tmp_name'], $file_path)) {
                $this->db->where('student_id', $param2);
                $this->db->update('student', array('address_proof' => $param2 . '_address_proof.' . $ext));
            }
        }
            
            // Commit transaction
            $this->db->trans_complete();
            
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Database transaction failed');
            }
            
            $this->session->set_flashdata('flash_message', get_phrase('Student information updated successfully'));
            return true;
            
        } catch (Exception $e) {
            // Rollback transaction
            $this->db->trans_rollback();
            
            $this->session->set_flashdata('error_message', get_phrase('Error updating student: ') . $e->getMessage());
            return false;
        }
    }

    // the function below deletes from student table
    function deleteNewStudent($param2){
        $this->db->where('student_id', $param2);
        $this->db->delete('student');
    }

	


	
	
}

