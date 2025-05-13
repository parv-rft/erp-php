<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Student_payment_model extends CI_Model { 
	
	function __construct(){
        parent::__construct();
    }



        function createStudentSinglePaymentFunction (){

            $page_data['invoice_number']    =   html_escape($this->input->post('invoice_number')) + rand(10000, 1000000);
            $page_data['receipt_number']    =   html_escape($this->input->post('receipt_number'));
            $page_data['student_id']        =   html_escape($this->input->post('student_id'));
            
            // Fetch student details based on student_id
            $student = $this->db->get_where('student', array('student_id' => $page_data['student_id']))->row();
            
            $page_data['admission_number']  =   $student->admission_number;
            $page_data['student_name']      =   $student->name;
            
            // Fetch class name
            $class = $this->db->get_where('class', array('class_id' => $student->class_id))->row();
            $page_data['class']             =   $class->name;
            
            $page_data['title']             =   html_escape($this->input->post('title'));
            $page_data['description']       =   html_escape($this->input->post('description'));
            $page_data['fee_type']          =   html_escape($this->input->post('fee_type'));
            $page_data['amount']            =   html_escape($this->input->post('amount'));
            $page_data['discount']          =   html_escape($this->input->post('discount'));
            $page_data['amount_paid']       =   html_escape($this->input->post('amount_paid'));
            $page_data['due']               =   $page_data['amount']  - $page_data['amount_paid'];
            $page_data['creation_timestamp']    =   html_escape($this->input->post('creation_timestamp'));
            $page_data['payment_method']        =   html_escape($this->input->post('payment_method'));
            $page_data['status']                =   html_escape($this->input->post('status'));
            $page_data['year']                  =  $this->db->get_where('settings', array('type' => 'session'))->row()->description;

            $this->db->insert('invoice', $page_data);
            $invoice_id = $this->db->insert_id();

            $page_data2['invoice_id']   =   $invoice_id;
            $page_data2['student_id']   =   html_escape($this->input->post('student_id'));
            $page_data2['title']        =   html_escape($this->input->post('title'));
            $page_data2['description']  =   html_escape($this->input->post('description'));
            $page_data2['payment_type'] =  'income';
            $page_data2['amount']       =   html_escape($this->input->post('amount'));
            $page_data2['discount']     =   html_escape($this->input->post('discount'));
            $page_data2['timestamp']    =   strtotime($this->input->post('creation_timestamp'));
            $page_data2['year']         =   $this->db->get_where('settings', array('type' => 'session'))->row()->description;
            $page_data2['method']       =   html_escape($this->input->post('payment_method'));

            $this->db->insert('payment', $page_data2);
            $payment_id = $this->db->insert_id();
        }

        function createStudentMassPaymentFunction(){

            $student_array = $this->input->post('student_id');
            $title = html_escape($this->input->post('title'));
            $description = html_escape($this->input->post('description'));
            $fee_type = html_escape($this->input->post('fee_type'));
            $amount = html_escape($this->input->post('amount'));
            $discount = html_escape($this->input->post('discount'));
            $amount_paid = html_escape($this->input->post('amount_paid'));
            $status = html_escape($this->input->post('status'));
            $creation_timestamp = html_escape($this->input->post('creation_timestamp'));
            $payment_method = html_escape($this->input->post('payment_method'));
            $base_invoice_number = html_escape($this->input->post('invoice_number')); 

            foreach($student_array as $key => $student_id){

                $page_data['invoice_number'] = $base_invoice_number + rand(10000, 1000000);
                $page_data['receipt_number'] = html_escape($this->input->post('receipt_number'));
                $page_data['student_id'] = $student_id;
                
                // Fetch student details based on student_id
                $student = $this->db->get_where('student', array('student_id' => $student_id))->row();
                
                $page_data['admission_number'] = $student->admission_number;
                $page_data['student_name'] = $student->name;
                
                // Fetch class name
                $class = $this->db->get_where('class', array('class_id' => $student->class_id))->row();
                $page_data['class'] = $class->name;
                
                $page_data['title'] = $title;
                $page_data['description'] = $description;
                $page_data['fee_type'] = $fee_type;
                $page_data['amount'] = $amount;
                $page_data['discount'] = $discount;
                $page_data['amount_paid'] = $amount_paid;
                $page_data['due'] = $amount - $amount_paid;
                $page_data['creation_timestamp'] = $creation_timestamp;
                $page_data['payment_method'] = $payment_method;
                $page_data['status'] = $status;
                $page_data['year'] = $this->db->get_where('settings', array('type' => 'session'))->row()->description;

                $this->db->insert('invoice', $page_data);
                $invoice_id = $this->db->insert_id();
                
                // Insert into payment table
                $page_data2['invoice_id'] = $invoice_id;
                $page_data2['student_id'] = $student_id;
                $page_data2['title'] = $title;
                $page_data2['description'] = $description;
                $page_data2['payment_type'] = 'income';
                $page_data2['amount'] = $amount;
                $page_data2['discount'] = $discount;
                $page_data2['timestamp'] = strtotime($creation_timestamp);
                $page_data2['year'] = $this->db->get_where('settings', array('type' => 'session'))->row()->description;
                $page_data2['method'] = $payment_method;
                
                $this->db->insert('payment', $page_data2);
            }
            
        }


        function takeNewPaymentFromStudent($param2){
            $page_data['invoice_id']        =   html_escape($this->input->post('invoice_id'));
            $page_data['student_id']        =   html_escape($this->input->post('student_id'));
            $page_data['title']             =   html_escape($this->input->post('title'));
            $page_data['description']       =   html_escape($this->input->post('description'));
            $page_data['amount']            =   html_escape($this->input->post('amount'));
            $page_data['payment_type']      =   'income';
            $page_data['method']            =   html_escape($this->input->post('method'));
            $page_data['timestamp']         =   strtotime($this->input->post('timestamp'));
            $page_data['amount']            =   html_escape($this->input->post('amount'));
            $page_data['year']              =  $this->db->get_where('settings', array('type' => 'session'))->row()->description;
            
            $this->db->insert('payment', $page_data);
            $payment_id = $this->db->insert_id();

            $page_data2['amount_paid'] = html_escape($this->input->post('amount'));
            $this->db->where('invoice_id', $param2);
            $this->db->set('amount_paid', 'amount_paid + ' . $page_data2['amount_paid'], FALSE);
            $this->db->set('due', 'due - ' . $page_data2['amount_paid'], FALSE);
            $this->db->update('invoice');

        }


        function updateStudentPaymentFunction($param2){

            $page_data['student_id']        =  html_escape($this->input->post('student_id'));
            
            // Fetch student details based on student_id
            $student = $this->db->get_where('student', array('student_id' => $page_data['student_id']))->row();
            
            $page_data['admission_number']  =   $student->admission_number;
            $page_data['student_name']      =   $student->name;
            
            // Fetch class name
            $class = $this->db->get_where('class', array('class_id' => $student->class_id))->row();
            $page_data['class']             =   $class->name;
            
            $page_data['title']             =   html_escape($this->input->post('title'));
            $page_data['description']       =   html_escape($this->input->post('description'));
            $page_data['fee_type']          =   html_escape($this->input->post('fee_type'));
            $page_data['amount']            =   html_escape($this->input->post('amount'));
            $page_data['amount_paid']       =   html_escape($this->input->post('amount_paid'));
            $page_data['due']               =   $page_data['amount']  - $page_data['amount_paid'];
            $page_data['creation_timestamp']    =   html_escape($this->input->post('date'));
            $page_data['status']                =   html_escape($this->input->post('status'));
            $page_data['receipt_number']    =   html_escape($this->input->post('receipt_number'));

            $this->db->where('invoice_id', $param2);
            $this->db->update('invoice', $page_data);


        }

        function deleteStudentPaymentFunction($param2){
            $this->db->where('invoice_id', $param2);
            $this->db->delete('invoice');

        }

    



}