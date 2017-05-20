<?php
class UsersKelas_Model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}

	public function insert_users_Kelas($id_user, $id_kelas){
		$sql = "INSERT INTO `users_kelas` (`id_user`,`id_kelas`) VALUES (?,?);";
		$this->db->query($sql, array($id_user, $id_kelas));

		$sql2 = "SELECT LAST_INSERT_ID() as id";
		$hasil = $this->db->query($sql2);
		return $hasil->row()->id;
	}
}