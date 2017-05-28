<?php
class User_Model extends CI_Model {

	public function __construct()
	{
		$this->load->database();

		$this->load->model("UsersKelas_Model");
	}

	public function insert_user($nama, $password, $id_kota, $email, $jenis_kelamin, $file, $array_id_kelas){

		$sql = "INSERT INTO `Users` (`nama`, `password`, `id_kota`, `email`, `jenis_kelamin`,`foto`,`created_at`) VALUES (?,?,?,?,?,?,NOW())";
		$this->db->query($sql, array($nama, $password, $id_kota, $email, $jenis_kelamin, $file));

		$sql2 = "SELECT LAST_INSERT_ID() as id";
		$hasil = $this->db->query($sql2);
		$id_user = $hasil->row()->id;

		foreach ($array_id_kelas as $id_kelas) {
			$this->UsersKelas_Model->insert_users_Kelas($id_user, $id_kelas);
		}
		return $hasil;
	}

	public function get_email($email){
		$sql="SELECT * FROM Users u WHERE u.email = ?";
		$hasil = $this->db->query($sql, array($email));
		return $hasil->row_array();
	}
}