<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class JualBeli extends REST_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('JualBeli_Model');
    }

    public function index_get(){
       try {
            $this->set_response([], REST_Controller::HTTP_ACCEPTED);    
        } catch (Exception $ex) {
            $this->response(array('error' => $ex->getMessage()), $ex->getCode());
        }
    }

    public function getAllJualBeli_get(){
       try {   
            $id_user =  $this->get("id_user");
            $jualbelis = $this->JualBeli_Model->get_all_jualBeli($id_user);
            if (count($jualbelis) > 0) {
               $this->set_response($jualbelis, REST_Controller::HTTP_OK);
            } else {
                $this->set_response([], REST_Controller::HTTP_ACCEPTED);
            }
        } catch (Exception $ex) {
            $this->response(array('error' => $ex->getMessage()), $ex->getCode());
        }
    }

    public function getUserLapak_get(){
       try {   
            $id_user =  $this->get("id_user");
            $jualbelis = $this->JualBeli_Model->get_userLapak($id_user);
            if (count($jualbelis) > 0) {
               $this->set_response($jualbelis, REST_Controller::HTTP_OK);
            } else {
                $this->set_response([], REST_Controller::HTTP_ACCEPTED);
            }
        } catch (Exception $ex) {
            $this->response(array('error' => $ex->getMessage()), $ex->getCode());
        }
    }

    public function createJualBeli_post(){
        try {
            $nama = $this->input->post('nama'); 
            $harga = $this->input->post('harga');
            $deskripsi = $this->input->post('deskripsi');
            $id_user = $this->input->post('id_user');
            $id_kota = $this->input->post('id_kota');
            $id_kelas = $this->input->post('id_kelas');

            $this->load->helper('file');
            if (!file_exists('./assets/images/jualbeli/'.$id_user.'/')) {
                mkdir('./assets/images/jualbeli/'.$id_user.'/', 0777, true);
            }
            $config['upload_path'] = './assets/images/jualbeli/'.$id_user.'/';
            $config['allowed_types'] =  'gif|jpg|png';

            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            
            if(!empty($_FILES['file']['name'])){
                if($this->upload->do_upload('file'))
                {
                    $file = $this->upload->data();
                    $path = file_get_contents($file['full_path']);
                    $result = $this->JualBeli_Model->insert_jualBeli($nama, $harga, $foto, $deskripsi, $id_user, $id_kota, $id_kelas);
                    if(count($result) > 0){
                        $this->set_response([
                            'status' => TRUE,
                            'message' => 'Successfully Create Jual Beli'
                            ], REST_Controller::HTTP_OK);
                    } else {
                        $this->set_response([
                            'status' => FALSE,
                            'message' => 'Failed Create Jual Beli'
                                ], REST_Controller::HTTP_BAD_REQUEST);
                    }
                } else {
                    $error = $this->upload->display_errors();
                    $this->response(array('error' => $error), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                }
            }else{
                 $result = $this->JualBeli_Model->insert_jualBeli($nama, $harga, NULL, $deskripsi, $id_user, $id_kota, $id_kelas);
                    if(count($result) > 0){
                        $this->set_response([
                            'status' => TRUE,
                            'message' => 'Successfully Create Jual Beli'
                            ], REST_Controller::HTTP_OK);
                    } else {
                        $this->set_response([
                            'status' => FALSE,
                            'message' => 'Failed Create Jual Beli'
                                ], REST_Controller::HTTP_BAD_REQUEST);
                    }
            }
        } catch (Exception $ex) {
            $this->response(array('error' => $ex->getMessage()), $ex->getCode());
        }
    }

    public function updateJualBeli_post(){
        try {
            $id_jualbeli = $this->input->post("id_jualbeli")
            $nama = $this->input->post('nama'); 
            $harga = $this->input->post('tanggal');
            $deskripsi = $this->input->post('tempat');
            $id_user = $this->input->post('id_user');
            $id_kota = $this->input->post('id_kota');
            $id_kelas = $this->input->post('id_kelas');

            $this->load->helper('file');
            $config['upload_path'] = './assets/images/jualbeli/'.$id_user.'/';
            $config['allowed_types'] =  'gif|jpg|png';

            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            
            if(!empty($_FILES['file']['name'])){
                if($this->upload->do_upload('file'))
                {
                    $file = $this->upload->data();
                    $path = file_get_contents($file['full_path']);
                    $result = $this->JualBeli_Model->update_jualBeli($id_jualbeli, $nama, $harga, $path, $deskripsi, $id_kelas, $id_kota, $id_user);
                    if(count($result) > 0){
                        $this->set_response([
                            'status' => TRUE,
                            'message' => 'Successfully Update Jual Beli'
                            ], REST_Controller::HTTP_OK);
                    } else {
                        $this->set_response([
                            'status' => FALSE,
                            'message' => 'Failed Update Jual Beli'
                                ], REST_Controller::HTTP_BAD_REQUEST);
                    }
                } else {
                    $error = $this->upload->display_errors();
                    $this->response(array('error' => $error), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                }
            }else{
                 $result = $this->JualBeli_Model->update_jualBeliupdate_jualBeli($id_jualbeli, $nama, $harga, NULL, $deskripsi, $id_kelas, $id_kota, $id_user);
                    if(count($result) > 0){
                        $this->set_response([
                            'status' => TRUE,
                            'message' => 'Successfully Update Jual Beli'
                            ], REST_Controller::HTTP_OK);
                    } else {
                        $this->set_response([
                            'status' => FALSE,
                            'message' => 'Failed Update Jual Beli'
                                ], REST_Controller::HTTP_BAD_REQUEST);
                    }
            }
        } catch (Exception $ex) {
            $this->response(array('error' => $ex->getMessage()), $ex->getCode());
        }
    }

    public function deleteJualBeli_get(){
        try {   
            $id_jualbeli =  $this->get("id_jualbeli");
            $result = $this->JualBeli_Model->delete_jualBeli($id_jualbeli);
            if ($result->num_rows() > 0) {
                $this->set_response([
                            'status' => TRUE,
                            'message' => 'Successfully Delete Jual Beli'
                            ], REST_Controller::HTTP_OK);
            } else {
                $this->set_response([
                            'status' => TRUE,
                            'message' => 'Failed Delete Jual Beli'
                            ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (Exception $ex) {
            $this->response(array('error' => $ex->getMessage()), $ex->getCode());
        }
    }
}
