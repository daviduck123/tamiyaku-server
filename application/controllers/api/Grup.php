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
            $id_user =  $this->get("id_user");
            $lat =  $this->get("lat");
            $lng =  $this->get("lng");
            $grup = $this->Grup_Model->get_allGrup_byUser($id_user, $lat, $lng);
            if (count($grup) > 0) {
               $this->set_response($grup, REST_Controller::HTTP_OK);
            } else {
                $this->set_response([
                    'status' => "TRUE",
                    'message' => 'Grup kosong'
                        ], REST_Controller::HTTP_ACCEPTED);
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
            $data = json_decode(file_get_contents('php://input'));
            
        } catch (Exception $ex) {
            $this->response(array('error' => $ex->getMessage()), $ex->getCode());
        }
    }
}
