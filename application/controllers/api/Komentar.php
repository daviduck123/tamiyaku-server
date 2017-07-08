<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Komentar extends REST_Controller {

    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        parent::__construct();
        $this->load->model('Komentar_Model');
    }

    public function index_get(){
        try {
            $id_post = $this->get("id_post");
            $id_event = $this->get("id_event");
            $id_jualbeli = $this->get("id_jualbeli");
            if(isset($id_post)){
                $komentar = $this->Komentar_Model->get_komentar_byIdPost($id_post);
            }
            if(isset($id_event)){
                $komentar = $this->Komentar_Model->get_komentar_byIdEvent($id_event);
            }
            if(isset($id_jualbeli)){
               $komentar = $this->Komentar_Model->get_komentar_byIdJualBeli($id_jualbeli);
            }
            if (count($komentar) > 0) {
               $this->set_response($komentar, REST_Controller::HTTP_OK);
            } else {
                $this->set_response([
                    'status' => "TRUE",
                    'message' => 'Komentar kosong'
                        ], REST_Controller::HTTP_ACCEPTED);
            }
        } catch (Exception $ex) {
            $this->response(array('error' => $ex->getMessage()), $ex->getCode());
        }
    }

    public function index_post(){
        try {   
            $data = json_decode(file_get_contents('php://input'));
            $id_user = $data->id_user;
            $id_post = NULL;
            $id_event = NULL;
            $id_jualbeli = NULL;
            if(isset($data->id_post)){
                $id_post = $data->id_post;
            }
            if(isset($data->id_event)){
                $id_event = $data->id_event;
            }
            if(isset($data->id_jualbeli)){
                $id_jualbeli = $data->id_jualbeli;
            }
            $deskripsi = $data->deskripsi;
            if(isset($id_post)){
                $type = 1;
            }
            if(isset($id_event)){
                $type = 2;
            }
            if(isset($id_jualbeli)){
                $type = 3;
            }

            $result = $this->Komentar_Model->insert_komentar($deskripsi, $type, $id_user, $id_post, $id_event, $id_jualbeli);
            if(count($result) > 0){
               $this->set_response([
                    'status' => "TRUE",
                    'message' => 'Berhasil Insert Komentar'
                        ], REST_Controller::HTTP_ACCEPTED);
            } else {
                $this->set_response([
                    'status' => "TRUE",
                    'message' => 'Gagal Insert Komentar'
                        ], REST_Controller::HTTP_ACCEPTED);
            }
        } catch (Exception $ex) {
            $this->response(array('error' => $ex->getMessage()), $ex->getCode());
        }
    }

    public function updateKomentar_post(){
        try {   
            $data = json_decode(file_get_contents('php://input'));
            $id_user = $data->id_user;
            $id_komentar = $data->id_komentar;
            $id_post = NULL;
            $id_event = NULL;
            $id_jualbeli = NULL;
            if(isset($data->id_post)){
                $id_post = $data->id_post;
            }
            if(isset($data->id_event)){
                $id_event = $data->id_event;
            }
            if(isset($data->id_jualbeli)){
                $id_jualbeli = $data->id_jualbeli;
            }
            $deskripsi = $data->deskripsi;
            
            $result = $this->Komentar_Model->update_komentar($id_komentar, $deskripsi, $id_post, $id_event, $id_jualbeli, $id_user);
            if(count($result) > 0){
               $this->set_response([
                    'status' => "TRUE",
                    'message' => 'Berhasil Update Komentar'
                        ], REST_Controller::HTTP_ACCEPTED);
            } else {
                $this->set_response([
                    'status' => "TRUE",
                    'message' => 'Gagal Update Komentar'
                        ], REST_Controller::HTTP_ACCEPTED);
            }
        } catch (Exception $ex) {
            $this->response(array('error' => $ex->getMessage()), $ex->getCode());
        }
    }

    public function deleteKomentar_get(){
        try {   
            $id_komentar =  $this->get("id_komentar");
            $result = $this->Komentar_Model->delete_komentar($id_komentar);
            if ($result->num_rows() > 0) {
                $this->set_response([
                            'status' => TRUE,
                            'message' => 'Successfully Update Komentar'
                            ], REST_Controller::HTTP_OK);
            } else {
                $this->set_response([
                            'status' => TRUE,
                            'message' => 'Failed Update Komentar'
                            ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (Exception $ex) {
            $this->response(array('error' => $ex->getMessage()), $ex->getCode());
        }
    }
}
