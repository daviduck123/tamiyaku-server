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
            $no_hp = $data->no_hp;
            $jenis_kelamin = $data->jenis_kelamin;
            $id_kelas = $data->id_kelas;
            $password = $data->password;

            $this->load->helper('security');
            $password = do_hash($password, "md5");
            $result = $this->User_Model->insert_user($nama, $password, $no_hp, $jenis_kelamin, $id_kelas);
            if($result->num_rows() > 0){
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

    public function checkNoHp_get(){
         try {            
            $no_hp = $this->get('no_hp');
            $user = $this->User_Model->get_noHp($no_hp);
            if (count($user) > 0) {
                $this->set_response([
                        'status' => "FALSE",
                        'message' => 'No HP sudah ada'
                            ], REST_Controller::HTTP_ACCEPTED);
            } else {
                $this->set_response([
                    'status' => "TRUE",
                    'message' => 'No HP Belom ada'
                        ], REST_Controller::HTTP_ACCEPTED);
            }
        } catch (Exception $ex) {
            $this->response(array('error' => $ex->getMessage()), $ex->getCode());
        }
    }
}
