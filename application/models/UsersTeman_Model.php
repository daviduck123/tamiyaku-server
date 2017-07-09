<?php
class UsersTeman_Model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}

	public function insert_usersTeman($id_user, $id_teman){
		$sql = "INSERT INTO `users_teman` (`id_user`,`id_teman`) VALUES (?,?);";
		$result = $this->db->query($sql, array($id_user, $id_teman));

		$sql = "INSERT INTO `users_teman` (`id_user`,`id_teman`) VALUES (?,?);";
		$result = $this->db->query($sql, array($id_teman, $id_user));

		$sql2 = "SELECT LAST_INSERT_ID() as id";
		$hasil = $this->db->query($sql2);

		return $result;
	}

	public function delete_usersTeman($id_user, $id_teman){
		$sql = "DELETE FROM `users_teman` WHERE id_user=? AND id_teman=?;";
		$result = $this->db->query($sql, array($id_user, $id_teman));

		$sql = "DELETE FROM `users_teman` WHERE id_user=? AND id_teman=?;";
		$result = $this->db->query($sql, array($id_teman, $id_user));
		return $result;
	}

	public function get_isTeman($id_user, $id_teman){
        $sql = "SELECT * FROM users_teman WHERE id_user=? AND id_teman=?;";
        $result = $this->db->query($sql, array($id_user, $id_teman));
		return $result->result_array();
    }
}