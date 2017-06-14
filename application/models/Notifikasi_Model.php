<?php
class Notifikasi_Model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
        $this->load->model("User_Model");
    }


    public function insert_notifiksai($deskripsi, $url, $id_user){
      $user = $this->User_Model->get_infoById($id_user);
      $notif = $user["nama"] ." ".$deskripsi;

      $sql = "INSERT INTO `notifikasi` (`deskripsi`, `url`, `created_at`, `id_user`) VALUES (?,?, NOW(),?)";
      $this->db->query($sql, array($notif, $url, $id_user));

      $sql2 = "SELECT LAST_INSERT_ID() as id";
      $hasil = $this->db->query($sql2);
      return $hasil->row()->id;
    }

    public function get_notifikasibyIdUser($id_user){
       $sql = "SELECT n.*
               FROM notifikasi n, users_teman ut
               WHERE ut.id_user = ? AND ut.id_teman = n.id_user
               ORDER BY created_at DESC";
       $hasil = $this->db->query($sql, array($id_user));
       $notif = $hasil->result_array();
       return $notif;
    }
}