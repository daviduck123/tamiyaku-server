<?php
class Kota_Model extends CI_Model {

        public function __construct()
        {
                $this->load->database();

                $this->create_Kelas();
        }

        public function create_Kelas(){
                $sql = "SHOW TABLES LIKE 'Kelas'";
                $exist = $this->db->query($sql);
                if($exist->num_rows() == 0){
                         $sql2="CREATE TABLE `tamiyaku`.`Kelas` ( `id` INT NOT NULL AUTO_INCREMENT , `nama` VARCHAR(255) NOT NULL , `deskripsi` TEXT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;";
                         $this->db->query($sql2);
                }
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