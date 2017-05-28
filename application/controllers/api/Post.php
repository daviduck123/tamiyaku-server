<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Post extends REST_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('Post_Model');
    }

    public function index_get(){
        try{
            
        } catch (Exception $ex) {
            $this->response(array('error' => $ex->getMessage()), $ex->getCode());
        }
    }

    public function createPost_post(){
        try {
            $deskripsi = $this->input->post('deskripsi');
            $id_user = $this->input->post('id_user');
            $id_grup = $this->input->post('id_grup');

            $this->load->helper('file');
            if (!file_exists('./assets/images/post/')) {
                mkdir('./assets/images/post/', 0777, true);
            }
            $config['upload_path'] = './assets/images/post/';
            $config['allowed_types'] =  'gif|jpg|png';

            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            
            if($this->upload->do_upload('file'))
            {
                $file = $this->upload->data();
                $path = file_get_contents($file['full_path']);

                $result = $this->Post_Model->insert_post($deskripsi, $id_user, $id_grup);
                if($result->num_rows() > 0){
                    $this->set_response([
                        'status' => TRUE,
                        'message' => 'Successfully Create Post'
                            ], REST_Controller::HTTP_OK);
                } else {
                    $this->set_response([
                        'status' => FALSE,
                        'message' => 'Failed Create Post'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
            } else {
                $error = $this->upload->display_errors();
                $this->response(array('error' => $error), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            }

           
        } catch (Exception $ex) {
            $this->response(array('error' => $ex->getMessage()), $ex->getCode());
        }
    }
}
