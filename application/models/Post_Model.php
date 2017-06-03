<?php
class Post_Model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }


    public function insert_post($deskripsi, $id_user, $id_grup, $foto){
       $sql = "INSERT INTO `post` (`deskripsi`, ";
       $values = "VALUES(?,";
       $array = [];
       array_push($array, $deskripsi);
       if(isset($foto)){
          $sql = $sql ." `foto`,";
          $values .= "?,";
          array_push($array, $foto);
       }
       if(isset($id_user)){
         $sql = $sql ." `id_user`,";
          $values .= "?,";
          array_push($array, $id_user);
       }
       if(isset($id_grup)){
         $sql = $sql ." `id_grup`,";
          $values .= "?,";
          array_push($array, $id_grup);
       }
       $sql = $sql ."`created_at`) ".$values."NOW());";

       $this->db->query($sql, $array);

       $sql2 = "SELECT LAST_INSERT_ID() as id";
       $hasil = $this->db->query($sql2);
       return $hasil->row()->id;
    }

    public function get_all_post(){
       $sql = "SELECT * FROM post ORDER BY created_at DESC";
       $hasil = $this->db->query($sql);
       $post = $hasil->result_array();
       $post2 = [];
       foreach($post as $posting){
          $posting_foto = $posting["foto"];
          $posting['foto'] = base64_encode($posting_foto);
          array_push($post2, $posting);
       }
       return $post2;
    }  

    public function get_post_byIdUser($id_user){
       $sql = "SELECT p.*, u.id as user_id, u.nama, u.foto as user_foto
                FROM post p
                WHERE p.id_user = ? 
                ORDER BY p.created_at DESC";
       $hasil = $this->db->query($sql, array($id_user));
       $post = $hasil->result_array();
       $post2 = [];
       foreach($post as $posting){
          $posting_foto = $posting["foto"];
          $user_foto = $posting["user_foto"];
          $posting['foto'] = base64_encode($posting_foto);
          $posting['user_foto'] = base64_encode($user_foto);
          array_push($post2, $posting);
       }
       return $post2;
    }

    public function get_all_friendPost($id_user){
       $sql = "SELECT p.*, u.id as user_id, u.nama, u.foto as user_foto
                FROM post p, users_teman ut, users u
                WHERE p.id_user = ut.id_teman AND ut.id_user = ? AND ut.id_teman = u.id
                ORDER BY p.created_at DESC";
       $hasil = $this->db->query($sql, array($id_user));
       $post2 = [];
       foreach($post as $posting){
          $posting_foto = $posting["foto"];
          $user_foto = $posting["user_foto"];
          $posting['foto'] = base64_encode($posting_foto);
          $posting['user_foto'] = base64_encode($user_foto);
          array_push($post2, $posting);
       }
       return $post2;
    }

    public function get_post_byIdGrup($id_grup){
       $sql = "SELECT p.*, u.id as user_id, u.nama, u.foto as user_foto
                FROM post p, users u 
                WHERE p.id_grup = ? AND p.id_user = u.id 
                ORDER BY p.created_at DESC";
       $hasil = $this->db->query($sql, array($id_grup));
       $post = $hasil->result_array();
       $post2 = [];
       foreach($post as $posting){
          $posting_foto = $posting["foto"];
          $user_foto = $posting["user_foto"];
          $posting['foto'] = base64_encode($posting_foto);
          $posting['user_foto'] = base64_encode($user_foto);
          array_push($post2, $posting);
       }
       return $post2;
    }   
}