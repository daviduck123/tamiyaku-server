<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class User extends REST_Controller {

    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        parent::__construct();
        $this->load->model('User_Model');
        $this->load->model('UsersTeman_Model');
    }

    public function index_get(){
        echo "Selamat datang di User Controller";
    }

    public function getUserByIdUser_get(){
        try {            
            $id_user = $this->get('id_user');
            $user = $this->User_Model->get_infoById($id_user);
            if (count($user) > 0) {
                $this->set_response($user, REST_Controller::HTTP_OK);
            } else {
                $this->set_response([], REST_Controller::HTTP_OK);
            }
        } catch (Exception $ex) {
            $this->response(array('error' => $ex->getMessage()), $ex->getCode());
        }
    }

    public function getTemanByIdUser_get(){
        try {            
            $id_user = $this->get('id_user');
            $teman = $this->User_Model->get_temanByIdUser($id_user);
            if (count($teman) > 0) {
                $this->set_response($teman, REST_Controller::HTTP_ACCEPTED);
            } else {
                $this->set_response([], REST_Controller::HTTP_ACCEPTED);
            }
        } catch (Exception $ex) {
            $this->response(array('error' => $ex->getMessage()), $ex->getCode());
        }
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
            $user = $this->User_Model->get_userByEmail($email);
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

    public function getFriend_get(){
         try {
            $id_user =  $this->get("id_user");
            $result = $this->User_Model->get_temanByIdUser($id_user);
            if(count($result) > 0){
               $this->set_response([
                            'status' => TRUE,
                            'message' => 'Successfully Add Friend'
                            ], REST_Controller::HTTP_OK);
            } else {
                $this->set_response([
                            'status' => TRUE,
                            'message' => 'Cannot Add Friend'
                            ], REST_Controller::HTTP_OK);
            }
        } catch (Exception $ex) {
            $this->response(array('error' => $ex->getMessage()), $ex->getCode());
        }
    }

    public function addFriend_get(){
        try {
            $id_teman =  $this->get("id_teman");
            $id_user =  $this->get("id_user");
            $result = $this->UsersTeman_Model->insert_usersTeman($id_user, $id_teman);
            if(count($result) > 0){
               $this->set_response([
                            'status' => TRUE,
                            'message' => 'Successfully Add Friend'
                            ], REST_Controller::HTTP_OK);
            } else {
                $this->set_response([
                            'status' => TRUE,
                            'message' => 'Cannot Add Friend'
                            ], REST_Controller::HTTP_OK);
            }
        } catch (Exception $ex) {
            $this->response(array('error' => $ex->getMessage()), $ex->getCode());
        }
    }

    public function deleteFriend_get(){
        try {
            $id_teman =  $this->get("id_teman");
            $id_user =  $this->get("id_user");
            $result = $this->UsersTeman_Model->delete_usersTeman($id_user, $id_teman);
            if($result->mysql_affected_rows() > 0){
               $this->set_response([
                            'status' => TRUE,
                            'message' => 'Successfully Delete Teman'
                            ], REST_Controller::HTTP_OK);
            } else {
                $this->set_response([
                            'status' => TRUE,
                            'message' => 'Cannot Delete Teman'
                            ], REST_Controller::HTTP_OK);
            }
        } catch (Exception $ex) {
            $this->response(array('error' => $ex->getMessage()), $ex->getCode());
        }
    }

    public function checkIsTeman_get(){
        try {
            $id_teman =  $this->get("id_teman");
            $id_user =  $this->get("id_user");
            $result = $this->User_Model->get_isTeman($id_user, $id_teman);
            if(count($result) > 0){
               $this->set_response($result, REST_Controller::HTTP_OK);
            } else {
                $this->set_response([], REST_Controller::HTTP_OK);
            }
        } catch (Exception $ex) {
            $this->response(array('error' => $ex->getMessage()), $ex->getCode());
        }
    }


    public function searchUser_get(){
        try {            
            $param = $this->get('param');
            $users = $this->User_Model->get_userBySearch($param);
            if (count($users) > 0) {
                 $this->set_response([
                    'users' => $users,
                    'status' => "TRUE",
                    'message' => 'Berhasil ketemu'
                        ], REST_Controller::HTTP_OK);
            } else {
                $this->set_response([
                    'status' => "FALSE",
                    'message' => 'Tidak ada yang ketemu'
                        ], REST_Controller::HTTP_OK);
            }
        } catch (Exception $ex) {
            $this->response(array('error' => $ex->getMessage()), $ex->getCode());
        }
    }

    public function updateUser_post(){
        try {
            $id_user = $this->input->post("id_user");
            $nama = $this->input->post('nama');
            $email = $this->input->post('email');
            $jenis_kelamin = $this->input->post('jenis_kelamin');
            $id_kelas = $this->input->post('id_kelas');
            $id_kota = $this->input->post('id_kota');            
            $password = $this->input->post('password');

            $array_id_kelas = explode(',', $id_kelas);
            
            $this->load->helper('file');
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

                $result = $this->User_Model->update_user($id_user, $email, $nama, $password, $jenis_kelamin, $path, $id_kota);
                if($result->num_rows() > 0){
                    $this->set_response([
                        'status' => TRUE,
                        'message' => 'Successfully Update User'
                            ], REST_Controller::HTTP_OK);
                } else {
                    $this->set_response([
                        'status' => FALSE,
                        'message' => 'Failed Update User'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
            } else {
                $result = $this->User_Model->update_user($id_user, $email, $nama, $password, $jenis_kelamin, NULL, $id_kota);
                if(count($result) > 0){
                    $this->set_response([
                        'status' => TRUE,
                        'message' => 'Successfully Update User'
                        ], REST_Controller::HTTP_OK);
                } else {
                    $this->set_response([
                        'status' => FALSE,
                        'message' => 'Failed Update User'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
            }
        } catch (Exception $ex) {
            $this->response(array('error' => $ex->getMessage()), $ex->getCode());
        }
    }
}
