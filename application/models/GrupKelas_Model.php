<?php
class GrupKelas_Model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}

	public function insert_grupKelas($id_grup, $id_kelas){
		$sql = "INSERT INTO `grup_kelas` (`id_grup`,`id_kelas`) VALUES (?,?);";
		$this->db->query($sql, array($id_grup, $id_kelas));

		$sql2 = "SELECT LAST_INSERT_ID() as id";
		$hasil = $this->db->query($sql2);
		return $hasil->row()->id;
	}
}