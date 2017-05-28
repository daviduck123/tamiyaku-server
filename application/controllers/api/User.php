<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class User extends REST_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('User_Model');
    }

    public function index_get(){
        echo "Selamat datang di User Controller";
    }

    public function registerNewUser_post(){
        try {        

            $nama = $this->input->post('nama');
            $email = $this->input->post('email');
            $jenis_kelamin = $this->input->post('jenis_kelamin');
            $id_kelas = $this->input->post('id_kelas');
            $id_kota = $this->input->post('id_kota');            
            $password = $this->input->post('password');

            $array_id_kelas = explode(',', $id_kelas);
            
            $this->load->helper('file');
            if (!file_exists('./assets/images/users/')) {
                mkdir('./assets/images/users/', 0777, true);
            }
            $config['upload_path'] = './assets/images/users/';
            $config['allowed_types'] =  'gif|jpg|png';

            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            
            if($this->upload->do_upload('file'))
            {
                $file = $this->upload->data();
                $path = file_get_contents($file['full_path']);
                
                $this->load->helper('security');
                $password = do_hash($password, "md5");

                $result = $this->User_Model->insert_user($nama, $password, $id_kota, $email, $jenis_kelamin, $path, $array_id_kelas);
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
            } else {
                $error = $this->upload->display_errors();
                $this->response(array('error' => $error), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            }

           
        } catch (Exception $ex) {
            $this->response(array('error' => $ex->getMessage()), $ex->getCode());
        }
    }

    public function checkEmail_get(){
         try {            
            $email = $this->get('email');
            $user = $this->User_Model->get_email($email);
            if (count($user) > 0) {
                $this->set_response([
                        'status' => "FALSE",
                        'message' => 'Email sudah ada'
                            ], REST_Controller::HTTP_ACCEPTED);
            } else {
                $this->set_response([
                    'status' => "TRUE",
                    'message' => 'Email Belom ada'
                        ], REST_Controller::HTTP_ACCEPTED);
            }
        } catch (Exception $ex) {
            $this->response(array('error' => $ex->getMessage()), $ex->getCode());
        }
    }
}
