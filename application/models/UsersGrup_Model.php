<?php
class UsersGrup_Model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}

	public function insert_usersGrup($id_user, $id_grup){
		$sql = "INSERT INTO `users_grup` (`id_grup`,`id_user`) VALUES (?,?);";
		$this->db->query($sql, array($id_grup, $id_user));

		$sql2 = "SELECT LAST_INSERT_ID() as id";
		$hasil = $this->db->query($sql2);
		return $hasil->row()->id;
	}

	public function delete_usersGrup($id_user, $id_grup){
		$sql = "DELETE FROM `users_grup` WHERE id_grup=? AND id_user=?;";
		$result = $this->db->query($sql, array($id_grup, $id_user));
		return $result;
	}
}