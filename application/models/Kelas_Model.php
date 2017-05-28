<?php
class Kelas_Model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }


    public function insert_kelas($nama){
       $sql = "INSERT INTO `kelas` (`nama`, `created_at`) VALUES (?,?);";
       $this->db->query($sql, array($nama,"NOW()"));

       $sql2 = "SELECT LAST_INSERT_ID() as id";
       $hasil = $this->db->query($sql2);
       return $hasil->row()->id;
    }

    public function get_all_kelas(){
       $sql = "SELECT id, nama, deskripsi FROM kelas";
       $hasil = $this->db->query($sql);
       return $hasil->row_array();
    }
}