<?php
class Komentar_Model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }


    public function insert_komentar($deskripsi, $id_user, $id_post){
       $sql = "INSERT INTO `post` (`deskripsi`, ";
       $values = "VALUES (?,";
       $array = [];
       if(isset($id_user)){
          $sql += " `id_user`,";
          $values = "?,";
          array_push($array, $id_user);
       }
       if(isset($id_post)){
          $sql += " `id_post`,";
          $values = "?,";
          array_push($array, $id_post);
       }
       $sql += "`created_at`) ".$values." NOW());";
       array_push($array, "NOW()");
      
       $this->db->query($sql, $array);

       $sql2 = "SELECT LAST_INSERT_ID() as id";
       $hasil = $this->db->query($sql2);
       return $hasil->row()->id;
    }

    public function get_komentar_byIdPost($id_post){
       $sql = "SELECT k.*, u.id, u.nama, u.foto 
               FROM komentar k, users u
               WHERE k.id_post = ? AND k.id_user = u.id 
               ORDER BY created_at DESC";
       $hasil = $this->db->query($sql, array($id_post));
       $komentar = $hasil->result_array();
       foreach($komentar as $com){
          $com_foto = $com["foto"];
          $com['foto'] = base64_encode($com);
       }
       return $komentar;
    }   
}