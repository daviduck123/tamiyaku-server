<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Komentar extends REST_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('Komentar_Model');
    }

    public function index_get(){
        try {
            $komentar = $this->Komentar_Model->get_komentar_byIdPost($this->get("id_post"));
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
            $id_post = $data->id_post;
            $deskripsi = $data->deskripsi;      

            $result = $this->Komentar_Model->insert_komentar($deskripsi, $id_user, $id_post);
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
}
