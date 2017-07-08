<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Kota extends REST_Controller {

    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        parent::__construct();
        $this->load->model('Kota_Model');
    }

    public function index_get(){
        try {   
            $kota = $this->Kota_Model->get_allKota();
            if (count($kota) > 0) {
               $this->set_response($kota, REST_Controller::HTTP_OK);
            } else {
                $this->set_response([
                    'status' => "TRUE",
                    'message' => 'Kota kosong'
                        ], REST_Controller::HTTP_OK);
            }
        } catch (Exception $ex) {
            $this->response(array('error' => $ex->getMessage()), $ex->getCode());
        }
    }
}
