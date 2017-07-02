<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Kelas extends REST_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('UsersKelas_Model');
    }

    public function getAllByUserId_get(){
       try {
       		$id_user = $this->get("id_user");
       		$kelas = $this->UsersKelas_Model->get_allKelas_byUser($id_user);
          if (count($kelas) > 0) {
               $this->set_response($kelas, REST_Controller::HTTP_OK);
            } else {
                $this->set_response([], REST_Controller::HTTP_ACCEPTED);
            }
        } catch (Exception $ex) {
            $this->response(array('error' => $ex->getMessage()), $ex->getCode());
        }
    }
}
