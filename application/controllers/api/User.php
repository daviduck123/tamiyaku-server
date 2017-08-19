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
                $this->set_response($teman, REST_Controller::HTTP_OK);
            } else {
                $this->set_response([], REST_Controller::HTTP_OK);
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
            $config['overwrite'] = TRUE;

            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            
            if($this->upload->do_upload('file'))
            {
                $file = $this->upload->data();

                $this->load->library('image_lib'); 

                $config2['image_library'] = 'gd2';
                $config2['source_image'] = $file['full_path'];
                $config2['new_image'] = './assets/images/users/';
                $config2['maintain_ratio'] = TRUE;
                $config2['create_thumb'] = FALSE;
                $config2['width'] = 200;
                $config2['height'] = 400;
               
                $this->image_lib->clear();
                $this->image_lib->initialize($config2);
                if (!$this->image_lib->resize())
                {
                    print_r($this->image_lib->display_errors());
                    $this->set_response([
                                    'status' => TRUE,
                                    'message' => 'Failed Resize Image'
                                        ], REST_Controller::HTTP_BAD_REQUEST);
                }else{
                    $path = file_get_contents($file['full_path']);
                    
                    $this->load->helper('security');
                    $password = do_hash($password, "md5");

                    $this->load->helper('string');
                    $uniqueId = random_string('numeric',6);

                    $duplicate = $this->User_Model->get_userByemail($email);
                    if(count($duplicate) > 0){
                        $this->set_response([
                            'status' => FALSE,
                            'message' => 'Email telah terdaftar'
                                ], REST_Controller::HTTP_BAD_REQUEST);
                    }else{
                        $result = $this->User_Model->insert_user($nama, $password, $id_kota, $email, $uniqueId, $jenis_kelamin, $path, $array_id_kelas);
                        if($result->num_rows() > 0){
                            $this->load->library('email');

                            $config = Array(
                            'protocol' => 'mail', //GoDaddy use "mail" or "sendmail", smtp not working
                            'smtp_host' => 'ssl://smtp.googlemail.com',
                            'smtp_port' => 465,
                            'smtp_user' => 'mini4wdku@gmail.com', // change it to yours
                            'smtp_pass' => 'namamu123', // change it to yours
                            'mailtype'  => 'html', 
                            'newline' => "\r\n",
                            'starttls'  => true,
                            'charset'   => 'iso-8859-1'
                            );

                            $this->email->initialize($config);
                            $this->email->from($config['smtp_user'], "Admin Mini4WD");
                            $this->email->to($email);

                            $this->email->subject('Email Verifikasi');
                            $this->email->message('Welcome to Website Tamiyaku.<br/>'.$nama.' here is the Activation Code : '. $uniqueId .'.</a><br/><br/>Thank you for joining with us.<br/><br/>Admin Tamiyaku.');
                            
                            if($this->email->send()){
                                $this->set_response([
                                        'status' => TRUE,
                                        'message' => 'Successfully Insert User'
                                            ], REST_Controller::HTTP_OK);
                            }else{
                                print_r($this->email->print_debugger());
                                $this->set_response([
                                        'status' => TRUE,
                                        'message' => 'Failed Send Email'
                                            ], REST_Controller::HTTP_BAD_REQUEST);
                             }
                        } else {
                            $this->set_response([
                                'status' => FALSE,
                                'message' => 'Failed Insert User'
                                    ], REST_Controller::HTTP_BAD_REQUEST);
                        }
                    }
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
                            ], REST_Controller::HTTP_OK);
            } else {
                $this->set_response([
                    'status' => "TRUE",
                    'message' => 'Email Belom ada'
                        ], REST_Controller::HTTP_OK);
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
            $config['overwrite'] = TRUE;

            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if(isset($password)){
                $this->load->helper('security');
                $password = do_hash($password, "md5");
            }
            
            if($this->upload->do_upload('file'))
            {
                $file = $this->upload->data();

                $this->load->library('image_lib'); 

                $config2['image_library'] = 'gd2';
                $config2['source_image'] = $file['full_path'];
                $config2['new_image'] = './assets/images/users/';
                $config2['maintain_ratio'] = TRUE;
                $config2['create_thumb'] = FALSE;
                $config2['width'] = 200;
                $config2['height'] = 400;
               
                $this->image_lib->clear();
                $this->image_lib->initialize($config2);
                if (!$this->image_lib->resize())
                {
                    print_r($this->image_lib->display_errors());
                    $this->set_response([
                                    'status' => TRUE,
                                    'message' => 'Failed Resize Image'
                                        ], REST_Controller::HTTP_BAD_REQUEST);
                }else{
                    $path = file_get_contents($file['full_path']);
                
                    $result = $this->User_Model->update_user($id_user, $email, $nama, $password, $jenis_kelamin, $path, $id_kota);
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

    public function updateVerified_get(){
         try {            
            $id_user = $this->get('id_user');
            $user = $this->User_Model->get_infoById($id_user);
            if (count($user) > 0) {
                $uniqueId = $this->get('uniqueId');
                if($uniqueId == $user["uniqueId"]){
                    $result = $this->User_Model->update_verified($id_user);
                    if(count($result) > 0){
                        $this->set_response([
                            'status' => TRUE,
                            'message' => 'Successfully Verified User'
                            ], REST_Controller::HTTP_OK);
                    } else {
                         $this->set_response([
                            'status' => FALSE,
                            'message' => 'Gagal melakukan verifikasi User'
                            ], REST_Controller::HTTP_OK);
                    }
                }else{
                       $this->set_response([
                            'status' => FALSE,
                            'message' => 'Kode verifikasi tidak benar.'
                                ], REST_Controller::HTTP_OK);
                }
            } else {
                $this->set_response([
                            'status' => FALSE,
                            'message' => 'User not found'
                                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        } catch (Exception $ex) {
            $this->response(array('error' => $ex->getMessage()), $ex->getCode());
        }
    }
}
