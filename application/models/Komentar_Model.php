<?php
class Komentar_Model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
        $this->load->model("Notifikasi_Model");
    }


    public function insert_komentar($deskripsi, $type, $id_user, $id_post, $id_event, $id_jualbeli){
       $sql = "INSERT INTO `komentar` (`deskripsi`, `type`, ";
       $values = "VALUES (?,?,";
       $array = [];
       array_push($array, $deskripsi, $type);
       if(isset($id_user)){
          $sql = $sql ." `id_user`,";
          $values .= "?,";
          array_push($array, $id_user);
       }
       if(isset($id_post)){
          $sql = $sql ." `id_post`,";
          $values .= "?,";
          array_push($array, $id_post);
       }
       if(isset($id_event)){
          $sql = $sql ." `id_event`,";
          $values .= "?,";
          array_push($array, $id_event);
       }
       if(isset($id_jualbeli)){
          $sql = $sql ." `id_jualbeli`,";
          $values .= "?,";
          array_push($array, $id_jualbeli);
       }
       $sql = $sql ."`created_at`) ".$values." NOW());";

       $this->db->query($sql, $array);

       $sql2 = "SELECT LAST_INSERT_ID() as id";
       $hasil = $this->db->query($sql2);

       $id = $hasil->row()->id;
       $this->Notifikasi_Model->insert_notifiksai("telah menulis komentar di post","blabl.html?id_post="+$id, $id_user);

       return $id;
    }

    public function get_komentar_byIdPost($id_post){
       $sql = "SELECT k.*, u.id as user_id, u.nama, u.foto as user_foto 
               FROM komentar k, users u
               WHERE k.id_post = ? AND k.id_user = u.id AND k.type = 1
               ORDER BY created_at DESC";
       $hasil = $this->db->query($sql, array($id_post));
       $komentar = $hasil->result_array();
       $komentar2 = [];
       foreach($komentar as $com){
          $com_foto = $com["foto"];
          $com['foto'] = base64_encode($com_foto);
          array_push($komentar2, $com);
       }
       return $komentar2;
    } 

    public function get_komentar_byIdEvent($id_event){
       $sql = "SELECT k.*, u.id as user_id, u.nama, u.foto as user_foto
               FROM komentar k, users u
               WHERE k.id_event = ? AND k.id_user = u.id AND k.type = 2
               ORDER BY created_at DESC";
       $hasil = $this->db->query($sql, array($id_event));
       $komentar = $hasil->result_array();
       $komentar2 = [];
       foreach($komentar as $com){
          $com_foto = $com["foto"];
          $com['foto'] = base64_encode($com_foto);
          array_push($komentar2, $com);
       }
       return $komentar2;
    } 

    public function get_komentar_byIdJualBeli($id_jualbeli){
       $sql = "SELECT k.*, u.id as user_id, u.nama, u.foto as user_foto
               FROM komentar k, users u
               WHERE k.id_jualbeli = ? AND k.id_user = u.id AND k.type = 3
               ORDER BY created_at DESC";
       $hasil = $this->db->query($sql, array($id_jualbeli));
       $komentar = $hasil->result_array();
       $komentar2 = [];
       foreach($komentar as $com){
          $com_foto = $com["foto"];
          $com['foto'] = base64_encode($com_foto);
          array_push($komentar2, $com);
       }
       return $komentar2;
    } 

    public function update_komentar($id_komentar, $deskripsi, $type, $id_post, $id_event, $id_jualbeli, $id_user){
      $sql="UPDATE `komentar` SET `deskripsi`=?,`type`=?";
      $array=array($deskripsi, $type);
      if(isset($id_post)){
          $sql = $sql .",`id_post`=?";
          array_push($array, $id_post);
      }
      if(isset($id_event)){
          $sql = $sql .",`id_event`=?";
          array_push($array, $id_event);
      }
      if(isset($id_jualbeli)){
          $sql = $sql .",`id_jualbeli`=?";
          array_push($array, $id_jualbeli);
      }
      $sql .= " WHERE id=?";
      array_push($array, $id_komentar);
        
      $result = $this->db->query($sql, $array);
      return $result;
    }
}