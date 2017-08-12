<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Post extends REST_Controller {

    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        parent::__construct();
        $this->load->model('Post_Model');
    }

    public function index_get(){
        try{
            $result = $this->Post_Model->get_all_post();
            if (count($result) > 0) {
               $this->set_response($result, REST_Controller::HTTP_OK);
            } else {
                $this->set_response([
                    'status' => "TRUE",
                    'message' => 'Post kosong'
                        ], REST_Controller::HTTP_OK);
            }
        } catch (Exception $ex) {
            $this->response(array('error' => $ex->getMessage()), $ex->getCode());
        }
    }

    public function getAllPostByUser_get(){
        try{
            $result = $this->Post_Model->get_post_byIdUser($this->get("id_user"));
            if (count($result) > 0) {
               $this->set_response($result, REST_Controller::HTTP_OK);
            } else {
                $this->set_response([], REST_Controller::HTTP_OK);
            }
        } catch (Exception $ex) {
            $this->response(array('error' => $ex->getMessage()), $ex->getCode());
        }
    }

    public function getAllPostFriendByUser_get(){
        try{
            $result = $this->Post_Model->get_all_friendPost($this->get("id_user"));
            if (count($result) > 0) {
               $this->set_response($result, REST_Controller::HTTP_OK);
            } else {
                $this->set_response([], REST_Controller::HTTP_OK);
            }
        } catch (Exception $ex) {
            $this->response(array('error' => $ex->getMessage()), $ex->getCode());
        }
    }

    public function getAllPostByGrup_get(){
        try{
            $result = $this->Post_Model->get_post_byIdGrup($this->get("id_grup"));
            if (count($result) > 0) {
               $this->set_response($result, REST_Controller::HTTP_OK);
            } else {
                $this->set_response([], REST_Controller::HTTP_OK);
            }
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
            $config['overwrite'] = TRUE;

            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            
            if(!empty($_FILES['file']['name'])){
                if($this->upload->do_upload('file'))
                {
                    $file = $this->upload->data();

                    $this->load->library('image_lib'); 

                    $config2['image_library'] = 'gd2';
                    $config2['source_image'] = $file['full_path'];
                    $config2['new_image'] = './assets/images/post/';
                    $config2['maintain_ratio'] = TRUE;
                    $config2['create_thumb'] = FALSE;
                    $config2['width'] = 300;
                    $config2['height'] = 600;

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

                        $result = $this->Post_Model->insert_post($deskripsi, $id_user, $id_grup, $path);
                        if(count($result) > 0){
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
                    }
                } else {
                    $error = $this->upload->display_errors();
                    $this->response(array('error' => $error), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                }
            }else{
                 $result = $this->Post_Model->insert_post($deskripsi, $id_user, $id_grup, null);
                    if(count($result) > 0){
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
            }
        } catch (Exception $ex) {
            $this->response(array('error' => $ex->getMessage()), $ex->getCode());
        }
    }

    public function updatePost_post(){
        try {
            $deskripsi = $this->input->post('deskripsi');
            $id_user = $this->input->post('id_user');
            $id_grup = $this->input->post('id_grup');
            $id_post = $this->input->post('id_post');

            $this->load->helper('file');
            $config['upload_path'] = './assets/images/post/';
            $config['allowed_types'] =  'gif|jpg|png';
            $config['overwrite'] = TRUE;
            
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            
            if(!empty($_FILES['file']['name'])){      
                if($this->upload->do_upload('file'))
                {
                    $file = $this->upload->data();

                    $this->load->library('image_lib'); 

                    $config2['image_library'] = 'gd2';
                    $config2['source_image'] = $file['full_path'];
                    $config2['new_image'] = './assets/images/post/';
                    $config2['maintain_ratio'] = TRUE;
                    $config2['create_thumb'] = FALSE;
                    $config2['width'] = 300;
                    $config2['height'] = 600;

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

                        $result = $this->Post_Model->update_post($id_post, $deskripsi, $path, $id_user, $id_grup);
                        if(count($result) > 0){
                            $this->set_response([
                                'status' => TRUE,
                                'message' => 'Successfully Update Post'
                                ], REST_Controller::HTTP_OK);
                        } else {
                            $this->set_response([
                                'status' => FALSE,
                                'message' => 'Failed Update Post'
                                    ], REST_Controller::HTTP_BAD_REQUEST);
                        }
                    }
                } else {
                    $error = $this->upload->display_errors();
                    $this->response(array('error' => $error), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                }
            }else{
                $result = $this->Post_Model->update_post($id_post, $deskripsi, NULL, $id_user, $id_grup);
                if(count($result) > 0){
                    $this->set_response([
                        'status' => TRUE,
                        'message' => 'Successfully Update Post'
                        ], REST_Controller::HTTP_OK);
                } else {
                    $this->set_response([
                        'status' => FALSE,
                        'message' => 'Failed Update Post'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
            }
        } catch (Exception $ex) {
            $this->response(array('error' => $ex->getMessage()), $ex->getCode());
        }
    }

    public function deletePost_get(){
        try {   
            $id_post =  $this->get("id_post");
            $result = $this->Post_Model->delete_post($id_post);
            if(count($result) > 0){
                $this->set_response([
                            'status' => TRUE,
                            'message' => 'Successfully Update Post'
                            ], REST_Controller::HTTP_OK);
            } else {
                $this->set_response([
                            'status' => TRUE,
                            'message' => 'Failed Update Post'
                            ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (Exception $ex) {
            $this->response(array('error' => $ex->getMessage()), $ex->getCode());
        }
    }
}
