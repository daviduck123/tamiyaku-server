<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Notifikasi extends REST_Controller {

    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        parent::__construct();
        $this->load->model('Notifikasi_Model');
    }

    public function getNotifikasiByIdUser_get(){
       try {
            $id_user = $this->get("id_user");
            $notif = $this->Notifikasi_Model->get_notifikasibyIdUser($id_user);
            if(count($notif) > 0){
                $this->set_response($notif, REST_Controller::HTTP_ACCEPTED);    
            }else{
                $this->set_response([], REST_Controller::HTTP_ACCEPTED);
            }
        } catch (Exception $ex) {
            $this->response(array('error' => $ex->getMessage()), $ex->getCode());
        }
    }
}
