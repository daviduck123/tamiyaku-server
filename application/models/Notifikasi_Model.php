<?php
class Notifikasi_Model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
        $this->load->model("User_Model");
    }


    public function insert_notifiksai($deskripsi, $type, $komen, $id_tujuan, $id_user){
      $user = $this->User_Model->get_infoById($id_user);
      $notif = $user["nama"] ." ".$deskripsi;

      $sql = "INSERT INTO `notifikasi` (`deskripsi`, `type`, `komen`, `id_tujuan`, `created_at`, `id_user`) VALUES (?,?,?,?, NOW(),?)";
      $this->db->query($sql, array($notif, $type, $komen, $id_tujuan, $id_user));

      $sql2 = "SELECT LAST_INSERT_ID() as id";
      $hasil = $this->db->query($sql2);
      return $hasil->row()->id;
    }

    public function get_notifikasibyIdUser($id_user){
       $sql = "SELECT n.*
               FROM notifikasi n, users_teman ut
               WHERE ut.id_user = ? AND ut.id_teman = n.id_user
               ORDER BY created_at DESC
               LIMIT 20";
       $hasil = $this->db->query($sql, array($id_user));
       $notif = $hasil->result_array();
       return $notif;
    }
}