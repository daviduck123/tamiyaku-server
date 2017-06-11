<?php
class JualBeli_Model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}

	public function insert_jualBeli($nama, $harga, $foto, $deskripsi, $id_user, $id_kota){
		$sql = "INSERT INTO `jual_beli` (`nama`, `harga`, ";
		$values = "VALUES (?,?,"
		$array = array($nama, $harga);
		if(isset($foto)){
			$sql .= "`foto`, ";
			$values .= "?,";
			array_push($array, $foto);
		}
		$sql .= "`deskripsi`, `created_at`, `id_user`, `id_kota`, `id_kelas`) ".$values ."?,NOW(),?,?,?);";
		array_push($array, $deskripsi, $id_user, $id_kota, $id_kelas);
		$result = $this->db->query($sql, $array);

		$sql2 = "SELECT LAST_INSERT_ID() as id";
		$hasil = $this->db->query($sql2);
		$id = $hasil->row()->id;

		$this->Notifikasi_Model->insert_notifiksai("telah menjual barang "+$nama,"blabl.html?id_jualbeli="+$id, $id_user);

		return $hasil;
	}

	public function get_all_jualBeli($id_user){

		$sql = "SELECT j.*, u.id, u.nama, u.foto as user_foto 
				FROM jual_beli j, users u ";
		$values = [];
		$array_kelas = $this->UsersKelas_Model->get_allKelas_byUser($id_user);
       
        $id_kelas_sql = "";
        foreach ($array_kelas as $id_kelas) {
            $id_kelas_sql .= "j.id_kelas = ? OR ";
            array_push($values, $id_kelas["id_kelas"]);
        }
        $len = strlen($id_kelas_sql);
        $id_kelas_sql = substr($id_kelas_sql, 0, $len - 3);

		$sql .= "WHERE ".$id_kelas_sql;
		$hasil = $this->db->query($sql, $values);

		$jualbeli = $hasil->result_array();
        $jualbeli2 = [];
        foreach($jualbeli as $jb){
          $jb_foto = $jb["foto"];
          $user_foto = $jb["user_foto"];
          $jb['foto'] = base64_encode($jb_foto);
          $jb['user_foto'] = base64_encode($user_foto);
          array_push($jualbeli2, $jb);
       }
       return $jualbeli2;
	}
}