<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Grup extends REST_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('Grup_Model');
    }

    public function index_get(){
       try {   
            $kota = $this->Grup_Model->get_allKota();
            if (count($kota) > 0) {
               $this->set_response($kota, REST_Controller::HTTP_OK);
            } else {
                $this->set_response([
                    'status' => "TRUE",
                    'message' => 'Kota kosong'
                        ], REST_Controller::HTTP_ACCEPTED);
            }
        } catch (Exception $ex) {
            $this->response(array('error' => $ex->getMessage()), $ex->getCode());
        }
    }

    public function createGrup_post(){
        try {
            $data = json_decode(file_get_contents('php://input'));
            $nama = $data->nama;
            $lat = $data->lat;
            $lng = $data->lng;
            $id_user = $data->id_user;
            $id_kelas = $data->id_kelas;

            $result = $this->Grup_Model->insert_grup($nama, $lat, $lng, $id_user, $id_kelas);
            if($result->num_rows() > 0){
                    $this->set_response([
                        'status' => TRUE,
                        'message' => 'Successfully Create Grup'
                            ], REST_Controller::HTTP_OK);
            } else {
                $this->set_response([
                    'status' => FALSE,
                    'message' => 'Failed Insert Grup'
                        ], REST_Controller::HTTP_BAD_REQUEST);
            }
        } catch (Exception $ex) {
            $this->response(array('error' => $ex->getMessage()), $ex->getCode());
        }
    }
}
