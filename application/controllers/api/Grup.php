<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Grup extends REST_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('Grup_Model');
        $this->load->model('UsersGrup_Model');
    }

    public function index_get(){
       try {   
            $id_user =  $this->get("id_user");
            $grup = $this->Grup_Model->get_allGrup_byUser($id_user);
            if (count($grup) > 0) {
               $this->set_response($grup, REST_Controller::HTTP_OK);
            } else {
                $this->set_response([], REST_Controller::HTTP_ACCEPTED);
            }
        } catch (Exception $ex) {
            $this->response(array('error' => $ex->getMessage()), $ex->getCode());
        }
    }
	
	public function getGrupNearBy_get(){
       try {  
			$id_user =  $this->get("id_user");	   
            $lat =  $this->get("lat");
			$lng =  $this->get("lng");
            $grup = $this->Grup_Model->get_allGrup_byLatLng($lat, $lng, $id_user);
            if (count($grup) > 0) {
               $this->set_response($grup, REST_Controller::HTTP_OK);
            } else {
                $this->set_response([], REST_Controller::HTTP_ACCEPTED);
            }
        } catch (Exception $ex) {
            $this->response(array('error' => $ex->getMessage()), $ex->getCode());
        }
    }

    public function getGrupInfo_get(){
       try {   
            $id_grup =  $this->get("id_grup");
            $grup = $this->Grup_Model->get_grupInfo($id_grup);
            if (count($grup) > 0) {
               $this->set_response($grup, REST_Controller::HTTP_OK);
            } else {
                $this->set_response([], REST_Controller::HTTP_ACCEPTED);
            }
        } catch (Exception $ex) {
            $this->response(array('error' => $ex->getMessage()), $ex->getCode());
        }
    }

    public function createGrup_post(){
        try {
            $nama = $this->input->post('nama');
            $lat = $this->input->post('lat');
            $lng = $this->input->post('lng');
            $lokasi = $this->input->post('lokasi');
            $id_kota = $this->input->post('id_kota');
            $id_user = $this->input->post('id_user');
            $id_kelas = $this->input->post('id_kelas');

            $this->load->helper('file');
            if (!file_exists('./assets/images/grup/')) {
                mkdir('./assets/images/grup/', 0777, true);
            }
            $config['upload_path'] = './assets/images/grup/';
            $config['allowed_types'] =  'gif|jpg|png';

            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            
            if(!empty($_FILES['file']['name'])){

                   
                if($this->upload->do_upload('file'))
                {
                    $file = $this->upload->data();
                    $path = file_get_contents($file['full_path']);

                    $result = $this->Grup_Model->insert_grup($nama, $lat, $lng, $lokasi, $path, $id_kota, $id_user, $id_kelas);
                    if(count($result) > 0){
                        $this->set_response([
                            'status' => TRUE,
                            'message' => 'Successfully Create Grup'
                            ], REST_Controller::HTTP_OK);
                    } else {
                        $this->set_response([
                            'status' => FALSE,
                            'message' => 'Failed Create Grup'
                                ], REST_Controller::HTTP_BAD_REQUEST);
                    }
                } else {
                    $error = $this->upload->display_errors();
                    $this->response(array('error' => $error), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                }
            }else{
                 $result = $this->Grup_Model->insert_grup($nama, $lat, $lng, $lokasi, null, $id_kota, $id_user, $id_kelas);
                    if(count($result) > 0){
                        $this->set_response([
                            'status' => TRUE,
                            'message' => 'Successfully Create Grup'
                            ], REST_Controller::HTTP_OK);
                    } else {
                        $this->set_response([
                            'status' => FALSE,
                            'message' => 'Failed Create Grup'
                                ], REST_Controller::HTTP_BAD_REQUEST);
                    }
            }
        } catch (Exception $ex) {
            $this->response(array('error' => $ex->getMessage()), $ex->getCode());
        }
    }

    public function joinGrup_get(){
        try {
            $id_grup =  $this->get("id_grup");
            $id_user =  $this->get("id_user");
            $result = $this->UsersGrup_Model->insert_usersGrup($id_user, $id_grup);
            if(count($result)){
               $this->set_response([
                            'status' => TRUE,
                            'message' => 'Successfully Join Grup'
                            ], REST_Controller::HTTP_OK);
            } else {
                $this->set_response([
                            'status' => TRUE,
                            'message' => 'Cannot Join Grup'
                            ], REST_Controller::HTTP_OK);
            }
        } catch (Exception $ex) {
            $this->response(array('error' => $ex->getMessage()), $ex->getCode());
        }
    }

    public function leaveGrup_get(){
        try {
            $id_grup =  $this->get("id_grup");
            $id_user =  $this->get("id_user");
            $result = $this->UsersGrup_Model->delete_usersGrup($id_user, $id_grup);
            if(count($result)){
               $this->set_response([
                            'status' => TRUE,
                            'message' => 'Successfully Leave Grup'
                            ], REST_Controller::HTTP_OK);
            } else {
                $this->set_response([
                            'status' => TRUE,
                            'message' => 'Cannot Leave Grup'
                            ], REST_Controller::HTTP_OK);
            }
        } catch (Exception $ex) {
            $this->response(array('error' => $ex->getMessage()), $ex->getCode());
        }
    }

    public function checkJoinedGrup_get(){
        try {
            $id_grup =  $this->get("id_grup");
            $id_user =  $this->get("id_user");
            $result = $this->UsersGrup_Model->get_isJoinedGrup($id_user, $id_grup);
            if(count($result) > 0){
               $this->set_response($result, REST_Controller::HTTP_OK);
            } else {
                $this->set_response([], REST_Controller::HTTP_OK);
            }
        } catch (Exception $ex) {
            $this->response(array('error' => $ex->getMessage()), $ex->getCode());
        }
    }

    public function updateGrup_post(){
        try {
            $id_grup = $this->input->post("id_grup")
            $nama = $this->input->post('nama');
            $lat = $this->input->post('lat');
            $lng = $this->input->post('lng');
            $lokasi = $this->input->post('lokasi');
            $id_kota = $this->input->post('id_kota');
            $id_user = $this->input->post('id_user');
            $id_kelas = $this->input->post('id_kelas');

            $this->load->helper('file');
            $config['upload_path'] = './assets/images/grup/';
            $config['allowed_types'] =  'gif|jpg|png';

            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            
            if(!empty($_FILES['file']['name'])){
                if($this->upload->do_upload('file'))
                {
                    $file = $this->upload->data();
                    $path = file_get_contents($file['full_path']);

                    $result = $this->Grup_Model->update_grup($id_grup, $nama, $lat, $lng, $path, $lokasi, $id_kelas, $id_kota, $id_user);
                    if(count($result) > 0){
                        $this->set_response([
                            'status' => TRUE,
                            'message' => 'Successfully Update Grup'
                            ], REST_Controller::HTTP_OK);
                    } else {
                        $this->set_response([
                            'status' => FALSE,
                            'message' => 'Failed Update Grup'
                                ], REST_Controller::HTTP_BAD_REQUEST);
                    }
                } else {
                    $error = $this->upload->display_errors();
                    $this->response(array('error' => $error), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                }
            }else{
                 $result = $this->Grup_Model->update_grup($id_grup,$nama, $lat, $lng, NULL, $lokasi, $id_kelas, $id_kota, $id_user);
                    if(count($result) > 0){
                        $this->set_response([
                            'status' => TRUE,
                            'message' => 'Successfully Update Grup'
                            ], REST_Controller::HTTP_OK);
                    } else {
                        $this->set_response([
                            'status' => FALSE,
                            'message' => 'Failed Update Grup'
                                ], REST_Controller::HTTP_BAD_REQUEST);
                    }
            }
        } catch (Exception $ex) {
            $this->response(array('error' => $ex->getMessage()), $ex->getCode());
        }
    }

    public function deleteGrup_get(){
        try {   
            $id_grup =  $this->get("id_grup");
            $result = $this->Grup_Model->delete_grup($id_grup);
            if ($result->num_rows() > 0) {
                $this->set_response([
                            'status' => TRUE,
                            'message' => 'Successfully Delete Grup'
                            ], REST_Controller::HTTP_OK);
            } else {
                $this->set_response([
                            'status' => TRUE,
                            'message' => 'Failed Delete Grup'
                            ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (Exception $ex) {
            $this->response(array('error' => $ex->getMessage()), $ex->getCode());
        }
    }
}
