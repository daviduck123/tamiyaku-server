<?php
class User_Model extends CI_Model {

	public function __construct()
	{
		$this->load->database();

		$this->load->model("UsersKelas_Model");
	}

	public function create_Users(){
		$sql = "SHOW TABLES LIKE 'Kelas'";
		$exist = $this->db->query($sql);
		if($exist > 0){
			$sql2="CREATE TABLE `tamiyaku`.`Users` ( `id` INT NOT NULL AUTO_INCREMENT , `email` VARCHAR(255) NULL , `hp` VARCHAR(12) NOT NULL , `nama` VARCHAR(255) NOT NULL ,`password` VARCHAR(12) NOT NULL , `jenis_kelamin` ENUM('Laki-laki','Perempuan') NOT NULL , `foto` BLOB NULL , `lat` FLOAT NULL , `lng` FLOAT NULL , `id_kota` INT NOT NULL , `created_at` DATE NOT NULL , PRIMARY KEY (`id`), UNIQUE (`email`), UNIQUE (`hp`)) ENGINE = InnoDB;";
			$this->db->query($sql2);
		}

	}

	public function insert_user($nama, $password, $id_kota, $no_hp, $jenis_kelamin, $id_kelas){
		$sql = "INSERT INTO `Users` (`nama`, `password`, `hp`, `jenis_kelamin`, `id_kota`,`created_at`) VALUES (?,?,?,?,?,?)";
		$this->db->query($sql, array($nama, $password, $no_hp, $jenis_kelamin, $id_kota,"NOW()"));

		$sql2 = "SELECT LAST_INSERT_ID() as id";
		$hasil = $this->db->query($sql2);
		$id_user = $hasil->row()->id;

		$hasil2 = $this->UsersKelas_Model->insert_users_Kelas($id_user, $id_kelas);
		return $hasil2;
	}
}