<?php
class Post_Model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }


    public function insert_post($deskripsi, $id_user, $id_grup){
       $sql = "INSERT INTO `post` (`deskripsi`, ";
       $values = "VALUES (?,";
       $array = [];
       if(isset($foto)){
          $sql += " `foto`,";
          $values = "?,";
          array_push($array, $foto);
       }
       if(isset($id_user){
          $sql += " `id_user`,";
          $values = "?,";
          array_push($array, $id_user);
       }
       if(isset($id_grup)){
          $sql += " `id_grup`,";
          $values = "?,";
          array_push($array, $id_grup);
       }
       $sql += "`created_at`) ".$values." NOW());";
       array_push($array, "NOW()");
      
       $this->db->query($sql, $array);

       $sql2 = "SELECT LAST_INSERT_ID() as id";
       $hasil = $this->db->query($sql2);
       return $hasil->row()->id;
    }

    public function get_all_post(){
       $sql = "SELECT * FROM post ORDER BY created_at DESC";
       $hasil = $this->db->query($sql);
       return $hasil->row_array();
    }  

    public function get_post_byIdUser($id_user){
       $sql = "SELECT * FROM post WHERE id_user = ? ORDER BY created_at DESC";
       $hasil = $this->db->query($sql, array($id_user));
       return $hasil->result_array();
    }

    public function get_post_byIdGrup($id_grup){
       $sql = "SELECT * FROM post WHERE id_grup = ? ORDER BY created_at DESC";
       $hasil = $this->db->query($sql, array($id_grup));
       return $hasil->result_array();
    }   
}