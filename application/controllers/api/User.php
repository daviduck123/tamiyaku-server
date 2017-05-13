<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class User extends REST_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('User_Model');
    }

    public function index_get(){
        echo "Starting from Here";
    }

    public function test_get(){
        $id = $this->User_Model->insert_user();
        echo $id;
    }

    public function registerNewUser_post(){
        try {            
            $data = json_decode(file_get_contents('php://input'));
            $nama = $data->nama;
            $id_kota = $data->id_kota;
            $no_hp = $data->no_hp;
            $jenis_kelamin = $data->jenis_kelamin;
            $id_kelas = $data->id_kelas;
            $password = $data->password;
            $result = $this->User_Model->insert_user($nama, $password, $id_kota, $no_hp, $jenis_kelamin, $id_kelas);

            if ($result > 0) {
                $this->set_response([
                    'status' => TRUE,
                    'message' => 'Successfully Insert User'
                        ], REST_Controller::HTTP_OK);
            } else {
                $this->set_response([
                    'status' => FALSE,
                    'message' => 'Failed Insert User'
                        ], REST_Controller::HTTP_BAD_REQUEST);
            }
        } catch (Exception $ex) {
            $this->response(array('error' => $ex->getMessage()), $ex->getCode());
        }
    }
}
