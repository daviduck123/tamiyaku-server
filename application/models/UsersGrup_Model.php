<?php
class UsersGrup_Model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
		$this->load->model("Notifikasi_Model");
	}

	public function insert_usersGrup($id_user, $id_grup){
		$sql = "INSERT INTO `users_grup` (`id_grup`,`id_user`) VALUES (?,?);";
		$this->db->query($sql, array($id_grup, $id_user));

		$sql2 = "SELECT LAST_INSERT_ID() as id";
		$hasil = $this->db->query($sql2);

		$id = $hasil >row()->id;
        $this->Notifikasi_Model->insert_notifiksai("telah bergabung ke grup","blabl.html?id_grup="+$id, $id_user);

		return $id;
	}

	public function delete_usersGrup($id_user, $id_grup){
		$sql = "DELETE FROM `users_grup` WHERE id_grup=? AND id_user=?;";
		$result = $this->db->query($sql, array($id_grup, $id_user));

        $this->Notifikasi_Model->insert_notifiksai("telah bergabung ke grup","home.html", $id_user);

		return $result;
	}

	public function get_isJoinedGrup($id_user, $id_grup){
        $sql = "SELECT * FROM users_grup WHERE id_grup=? AND id_user=?;";
        $result = $this->db->query($sql, array($id_grup, $id_user));
		return $result->result_array();
    }
}