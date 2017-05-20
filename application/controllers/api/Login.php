<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Login extends REST_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('User_Model');
    }

    public function index_get(){
        echo "Login Here";
    }

    public function index_post(){
        try {            
            $data = json_decode(file_get_contents('php://input'));
            $no_hp = $data->no_hp;
            $password = $data->password;

            $this->load->helper('security');
            if ($no_hp != null && $password != null) {
                $user = $this->User_Model->get_noHp($no_hp);
                if (count($user) > 0) {
                    $password = do_hash($password, "md5");
                    if ($password == $user["password"]) {
                        $this->set_response([
                            'id' => $user["id"],
                            'status' => "TRUE",
                            'message' => 'Successfuly Login'
                                ], REST_Controller::HTTP_ACCEPTED);
                    } else {
                        $this->set_response([
                            'status' => "FALSE",
                            'message' => 'Password salah'
                                ], REST_Controller::HTTP_ACCEPTED);
                    }
                } else {
                    $this->set_response([
                        'status' => "FALSE",
                        'message' => 'User tidak ditemukan'
                            ], REST_Controller::HTTP_ACCEPTED);
                }
            } else {
                $this->set_response([
                    'status' => "FALSE",
                    'message' => 'Kurang Parameter'
                        ], REST_Controller::HTTP_BAD_REQUEST);
            }
        } catch (Exception $ex) {
            $this->response(array('error' => $ex->getMessage()), $ex->getCode());
        }
    }

}
