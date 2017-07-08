<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class FirstLoad extends REST_Controller {

    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        parent::__construct();
        $this->load->model('FirstDatabase_Model');
    }

    public function index_get(){
        //Run this once if tables not created yet
        //Setting Database first at application/config/database.php
        try {
            $this->FirstDatabase_Model->create_tables();
            $this->set_response([
                'status' => TRUE,
                'message' => 'Successfully Create Database And Tables'
                    ], REST_Controller::HTTP_OK);
        } catch (Exception $e) {
            $this->response(array('error' => $ex->getMessage()), $ex->getCode());
        }
    }
}
