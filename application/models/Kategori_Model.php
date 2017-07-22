<?php
class Kategori_Model extends CI_Model {

    public function __construct()
    {
            $this->load->database();
    }

    public function insert_kategori($nama){
        $sql = "INSERT INTO `Kategori` (`nama`, `created_at`) VALUES (?,NOW());";
        $this->db->query($sql, array($nama));

        $sql2 = "SELECT LAST_INSERT_ID() as id";
        $hasil = $this->db->query($sql2);
        return $hasil->row()->id;
    }

    public function get_allKategori(){
        $sql = "SELECT id, nama FROM kategori ORDER BY nama ASC";
        $hasil = $this->db->query($sql);
        return $hasil->result_array();
    }
}