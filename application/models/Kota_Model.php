<?php
class Kota_Model extends CI_Model {

        public function __construct()
        {
                $this->load->database();
                $this->create_Kota();
        }

        public function create_Kota(){
                $sql = "SHOW TABLES LIKE 'Kota'";
                $exist = $this->db->query($sql);
               if($exist->num_rows() == 0){
                        $sql2="CREATE TABLE `tamiyaku`.`Kota` ( `id` INT NOT NULL AUTO_INCREMENT , `nama` VARCHAR(255) NOT NULL , `created_at` DATETIME NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;"; 
                        $this->db->query($sql2);
                }
        }

        public function insert_kota($nama){
                $sql = "INSERT INTO `Kota` (`nama`, `created_at`) VALUES (?,NOW());";
                $this->db->query($sql, array($nama));

                $sql2 = "SELECT LAST_INSERT_ID() as id";
                $hasil = $this->db->query($sql2);
                return $hasil->row()->id;
        }

        public function get_all_kota(){
               $sql = "SELECT id, nama FROM kota";
               $hasil = $this->db->query($sql);
               return $hasil->row_array();
        }
}