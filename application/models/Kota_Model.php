<?php
class Kota_Model extends CI_Model {

    public function __construct()
    {
            $this->load->database();
    }

    public function insert_kota($nama){
        $sql = "INSERT INTO `Kota` (`nama`, `created_at`) VALUES (?,NOW());";
        $this->db->query($sql, array($nama));

        $sql2 = "SELECT LAST_INSERT_ID() as id";
        $hasil = $this->db->query($sql2);
        return $hasil->row()->id;
    }

    public function get_allKota(){
        $sql = "SELECT id, nama, lat, lng FROM kota";
        $hasil = $this->db->query($sql);
        return $hasil->result_array();
    }
}