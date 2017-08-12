<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Event extends REST_Controller {

    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        parent::__construct();
        $this->load->model('Event_Model');
    }

    public function index_get(){
       try {
            $this->set_response([], REST_Controller::HTTP_OK);    
        } catch (Exception $ex) {
            $this->response(array('error' => $ex->getMessage()), $ex->getCode());
        }
    }

    public function getEventById_get(){
       try {   
            $id =  $this->get("id");
            $events = $this->Event_Model->get_eventById($id);
            if (count($events) > 0) {
               $this->set_response($events, REST_Controller::HTTP_OK);
            } else {
                $this->set_response(NULL, REST_Controller::HTTP_OK);
            }
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
                $this->set_response([], REST_Controller::HTTP_OK);
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
            $lat = $this->input->post('lat');
            $lng = $this->input->post('lng');
            $id_kelas = $this->input->post('id_kelas');
            $canvas = $this->input->post("canvas");

            $this->load->helper('file');
            if (!file_exists('./assets/images/event/')) {
                //If not found Directory, then Create
                //0777 = Hak Akses (ini coba di cari di google, macma2 hak akses nya, masukin bab 5 mgkn gpp)
                mkdir('./assets/images/event/', 0777, true);
            }

            //canvas
            if(isset($canvas)){
                $this->load->helper('string');
                //Biasanya canvas hasil base64 nya
                //data:image/png;base64,/9j/4AAQSkZJRgABAQAAAQABAA......
                $img = str_replace('data:image/png;base64,', '', $canvas);
                $img = str_replace(' ', '+', $img);

                //Setelah dapat code nya, di decode menjadi object, lalu d simpan
                $data = base64_decode($img);
                //generate uniqueId, jumlah karakter
                $uniqueId = random_string('alnum',32);
                $file = "./assets/images/event/" .$uniqueId. '.png'; //Buat nama file baru

                //taruh file ke server
                $success = file_put_contents($file, $data);

                //save
                $result = $this->Event_Model->insert_event($nama, $tanggal, $tempat, $hadiah1, $hadiah2, $hadiah3, $harga_tiket, $deskripsi, $lat, $lng, $data, $id_user, $id_kota, $id_kelas);
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
            }else{
                //image configuration, lokasi dan jenis file yg bisa d terima
                $config['upload_path'] = './assets/images/event/';
                $config['allowed_types'] =  'gif|jpg|png';
                $config['overwrite'] = TRUE;
                
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                
                if(!empty($_FILES['file']['name'])){
                    //Jika berhasil simpan
                    if($this->upload->do_upload('file'))
                    {
                        $file = $this->upload->data(); //ambil uploaded data

                        $this->load->library('image_lib'); 

                        $config2['image_library'] = 'gd2';
                        $config2['source_image'] = $file['full_path'];
                        $config2['new_image'] = './assets/images/event/';
                        $config2['maintain_ratio'] = TRUE;
                        $config2['create_thumb'] = FALSE;
                        $config2['width'] = 200;
                        $config2['height'] = 400;

                        $this->image_lib->clear();
                        $this->image_lib->initialize($config2);
                        if (!$this->image_lib->resize())
                        {
                            print_r($this->image_lib->display_errors());
                            $this->set_response([
                                            'status' => TRUE,
                                            'message' => 'Failed Resize Image'
                                                ], REST_Controller::HTTP_BAD_REQUEST);
                        }else{
                            $path = file_get_contents($file['full_path']); //simpan gambar ke server
                            $result = $this->Event_Model->insert_event($nama, $tanggal, $tempat, $hadiah1, $hadiah2, $hadiah3, $harga_tiket, $deskripsi, $lat, $lng, $path, $id_user, $id_kota, $id_kelas);
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
                    } else {
                        $error = $this->upload->display_errors();
                        $this->response(array('error' => $error), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                    }
                }else{
                    //jika ga ad gambar yg d upload
                     $result = $this->Event_Model->insert_event($nama, $tanggal, $tempat, $hadiah1, $hadiah2, $hadiah3, $harga_tiket, $deskripsi, NULL, $id_user, $id_kota, $id_kelas);
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
                }
           
        } catch (Exception $ex) {
            $this->response(array('error' => $ex->getMessage()), $ex->getCode());
        }
    }

    public function updateEvent_post(){
        try {
            $id_event = $this->input->post('id_event'); 
            $nama = $this->input->post('nama'); 
            $tanggal = $this->input->post('tanggal');
            $tempat = $this->input->post('tempat');
            $hadiah1 = $this->input->post('hadiah1');
            $hadiah2 = $this->input->post('hadiah2');
            $hadiah3 = $this->input->post('hadiah3');
            $harga_tiket = $this->input->post('harga_tiket');
            $deskripsi = $this->input->post('deskripsi');
            $lat = $this->input->post('lat');
            $lng = $this->input->post('lng');
            $id_user = $this->input->post('id_user');
            $id_kota = $this->input->post('id_kota');
            $id_kelas = $this->input->post('id_kelas');
            $canvas = $this->input->post("canvas");

            $this->load->helper('file');
            if (!file_exists('./assets/images/event/')) {
                mkdir('./assets/images/event/', 0777, true);
            }
            $config['upload_path'] = './assets/images/event/';
            $config['allowed_types'] =  'gif|jpg|png';
            $config['overwrite'] = TRUE;

            if(isset($canvas)){
                $this->load->helper('string');
                $img = str_replace('data:image/png;base64,', '', $canvas);
                $img = str_replace(' ', '+', $img);
                $data = base64_decode($img);
                $uniqueId = random_string('alnum',32);
                $file = "./assets/images/event/" .$uniqueId. '.png';
                $success = file_put_contents($file, $data);
                $result = $this->Event_Model->update_event($id_event, $nama, $tanggal, $tempat, $hadiah1, $hadiah2, $hadiah3, $data, $harga_tiket, $deskripsi, $lat, $lng, $id_kota, $id_kelas, $id_user);
                if(count($result) > 0){
                    $this->set_response([
                        'status' => TRUE,
                        'message' => 'Successfully Update Event'
                        ], REST_Controller::HTTP_OK);
                } else {
                    $this->set_response([
                        'status' => FALSE,
                        'message' => 'Failed Update Event'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
            }else{
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                
                if(!empty($_FILES['file']['name'])){
                    if($this->upload->do_upload('file'))
                    {
                        $file = $this->upload->data();

                        $this->load->library('image_lib'); 

                        $config2['image_library'] = 'gd2';
                        $config2['source_image'] = $file['full_path'];
                        $config2['new_image'] = './assets/images/event/';
                        $config2['maintain_ratio'] = TRUE;
                        $config2['create_thumb'] = FALSE;
                        $config2['width'] = 200;
                        $config2['height'] = 400;

                        $this->image_lib->clear();
                        $this->image_lib->initialize($config2);
                        if (!$this->image_lib->resize())
                        {
                            print_r($this->image_lib->display_errors());
                            $this->set_response([
                                            'status' => TRUE,
                                            'message' => 'Failed Resize Image'
                                                ], REST_Controller::HTTP_BAD_REQUEST);
                        }else{
                            $path = file_get_contents($file['full_path']);
                            $result = $this->Event_Model->update_event($id_event, $nama, $tanggal, $tempat, $hadiah1, $hadiah2, $hadiah3, $path, $harga_tiket, $deskripsi, $lat, $lng, $id_kota, $id_kelas, $id_user);
                            if(count($result) > 0){
                                $this->set_response([
                                    'status' => TRUE,
                                    'message' => 'Successfully Update Event'
                                    ], REST_Controller::HTTP_OK);
                            } else {
                                $this->set_response([
                                    'status' => FALSE,
                                    'message' => 'Failed Update Event'
                                        ], REST_Controller::HTTP_BAD_REQUEST);
                            }
                        }
                    } else {
                        $error = $this->upload->display_errors();
                        $this->response(array('error' => $error), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                    }
                }else{
                     $result = $this->Event_Model->update_event($id_event, $nama, $tanggal, $tempat, $hadiah1, $hadiah2, $hadiah3, NULL, $harga_tiket, $deskripsi, $id_kota, $id_kelas, $id_user);
                        if(count($result) > 0){
                            $this->set_response([
                                'status' => TRUE,
                                'message' => 'Successfully Update Event'
                                ], REST_Controller::HTTP_OK);
                        } else {
                            $this->set_response([
                                'status' => FALSE,
                                'message' => 'Failed Update Event'
                                    ], REST_Controller::HTTP_BAD_REQUEST);
                        }
                }
            }
        } catch (Exception $ex) {
            $this->response(array('error' => $ex->getMessage()), $ex->getCode());
        }
    }

    public function deleteEvent_get(){
        try {   
            $id_event =  $this->get("id_event");
            $result = $this->Event_Model->delete_event($id_event);
            if ($result == true) {
                $this->set_response([
                            'status' => TRUE,
                            'message' => 'Successfully Delete Event'
                            ], REST_Controller::HTTP_OK);
            } else {
                $this->set_response([
                            'status' => TRUE,
                            'message' => 'Failed Delete Event'
                            ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (Exception $ex) {
            $this->response(array('error' => $ex->getMessage()), $ex->getCode());
        }
    }
}
