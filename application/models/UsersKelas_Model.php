<?php
class UsersKelas_Model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}

	public function create_UsersKelas(){
		$sql = "SHOW TABLES LIKE 'users_kelas'";
		$exist = $this->db->query($sql);
		if($exist > 0){
			$sql2="CREATE TABLE `tamiyaku`.`users_kelas` ( `id_user` INT NOT NULL , `id_kelas` INT NOT NULL , PRIMARY KEY (`id_user`, `id_kelas`)) ENGINE = InnoDB;";
			$this->db->query($sql2);
			$sql3 = "ALTER TABLE `users_kelas` ADD CONSTRAINT `fk_users_userskelas` FOREIGN KEY (`id_user`) REFERENCES `tamiyaku`.`users`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT; ALTER TABLE `users_kelas` ADD CONSTRAINT `fk_kelas_userskelas` FOREIGN KEY (`id_kelas`) REFERENCES `tamiyaku`.`kelas`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;";
			$this->db->query($sql3);
		}

	}

	public function insert_users_Kelas($id_user, $id_kelas){
		$sql = "INSERT INTO `users_kelas` (`id_user`,`id_kelas`) VALUES (?,?);";
		$this->db->query($sql, array($id_user, $id_kelas));

		$sql2 = "SELECT LAST_INSERT_ID() as id";
		$hasil = $this->db->query($sql2);
		return $hasil->row()->id;
	}
}