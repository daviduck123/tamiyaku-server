<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class JualBeli extends REST_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('Event_Model');
    }

    public function index_get(){
       try {
            $this->set_response([], REST_Controller::HTTP_ACCEPTED);    
        } catch (Exception $ex) {
            $this->response(array('error' => $ex->getMessage()), $ex->getCode());
        }
    }

    public function getAllEvent_get(){
       try {   
            $id_user =  $this->get("id_user");
            $events = $this->Event_Model->get_allEventByUserKelas($id_user);
            if (count($events) > 0) {
               $this->set_response($events, REST_Controller::HTTP_OK);
            } else {
                $this->set_response([], REST_Controller::HTTP_ACCEPTED);
            }
        } catch (Exception $ex) {
            $this->response(array('error' => $ex->getMessage()), $ex->getCode());
        }
    }

    public function createEvent_post(){
        try {
            $nama = $this->input->post('nama'); 
            $tanggal = $this->input->post('tanggal');
            $tempat = $this->input->post('tempat');
            $hadiah1 = $this->input->post('hadiah1');
            $hadiah2 = $this->input->post('hadiah2');
            $hadiah3 = $this->input->post('hadiah3');
            $harga_tiket = $this->input->post('harga_tiket');
            $deskripsi = $this->input->post('deskripsi');
            $id_user = $this->input->post('id_user');
            $id_kota = $this->input->post('id_kota');

            $this->load->helper('file');
            if (!file_exists('./assets/images/event/')) {
                mkdir('./assets/images/event/', 0777, true);
            }
            $config['upload_path'] = './assets/images/event/';
            $config['allowed_types'] =  'gif|jpg|png';

            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            
            if(!empty($_FILES['file']['name'])){
                if($this->upload->do_upload('file'))
                {
                    $file = $this->upload->data();
                    $path = file_get_contents($file['full_path']);
                    $result = $this->Event_Model->insert_event($nama, $tanggal, $tempat, $hadiah1, $hadiah2, $hadiah3, $harga_tiket, $deskripsi, $path, $id_user, $id_kota);
                    if(count($result) > 0){
                        $this->set_response([
                            'status' => TRUE,
                            'message' => 'Successfully Create Event'
                            ], REST_Controller::HTTP_OK);
                    } else {
                        $this->set_response([
                            'status' => FALSE,
                            'message' => 'Failed Create Event'
                                ], REST_Controller::HTTP_BAD_REQUEST);
                    }
                } else {
                    $error = $this->upload->display_errors();
                    $this->response(array('error' => $error), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                }
            }else{
                 $result = $this->Event_Model->insert_event($nama, $tanggal, $tempat, $hadiah1, $hadiah2, $hadiah3, $harga_tiket, $deskripsi, NULL, $id_user, $id_kota);
                    if(count($result) > 0){
                        $this->set_response([
                            'status' => TRUE,
                            'message' => 'Successfully Create Event'
                            ], REST_Controller::HTTP_OK);
                    } else {
                        $this->set_response([
                            'status' => FALSE,
                            'message' => 'Failed Create Event'
                                ], REST_Controller::HTTP_BAD_REQUEST);
                    }
            }
        } catch (Exception $ex) {
            $this->response(array('error' => $ex->getMessage()), $ex->getCode());
        }
    }
}
