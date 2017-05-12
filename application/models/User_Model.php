<?php
class User_Model extends CI_Model {

        public function __construct()
        {
                $this->load->database();
        }

        public function insert_user(){
        	$sql = "INSERT INTO `User` (`email`, `nama`, `no_hp`, `created_at`) VALUES (?,?,?,NOW());";
        	$this->db->query($sql, array("daviduck1234@gmail.com","David2","085777400100"));

        	$sql2 = "SELECT LAST_INSERT_ID() as id";
        	$hasil = $this->db->query($sql2);
        	return $hasil->row()->id;
        }
}