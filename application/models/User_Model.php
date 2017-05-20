<?php
class User_Model extends CI_Model {

	public function __construct()
	{
		$this->load->database();

		$this->load->model("UsersKelas_Model");
	}

	public function insert_user($nama, $password, $no_hp, $jenis_kelamin, $id_kelas){
		$sql = "INSERT INTO `Users` (`nama`, `password`, `no_hp`, `jenis_kelamin`,`created_at`) VALUES (?,?,?,?,NOW())";
		$this->db->query($sql, array($nama, $password, $no_hp, $jenis_kelamin));

		$sql2 = "SELECT LAST_INSERT_ID() as id";
		$hasil = $this->db->query($sql2);
		$id_user = $hasil->row()->id;

		$hasil2 = $this->UsersKelas_Model->insert_users_Kelas($id_user, $id_kelas);
		return $hasil2;
	}

	public function get_noHp($no_hp){
		$sql="SELECT * FROM Users u WHERE u.no_hp = ?";
		$hasil = $this->db->query($sql, array($no_hp));
		return $hasil->row_array();
	}
}