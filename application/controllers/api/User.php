<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('User_Model');
    }

    public function index(){
        echo "Starting from Here";
    }

    public function test(){
        $id = $this->User_Model->insert_user();
        echo $id;
    }
}
