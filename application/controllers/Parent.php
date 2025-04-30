<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Parent extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->library('role_based_access');
        
        // Check if user is logged in and has parent role
        $this->role_based_access->check_access('parent');
    }

    // ... existing code ...
} 