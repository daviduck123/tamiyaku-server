<?php
class JualBeli_Model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
        $this->load->model("Notifikasi_Model");
	}

	public function insert_jualBeli($nama, $harga, $foto, $deskripsi, $id_user, $id_kota, $id_kelas, $id_kategori){
		$sql = "INSERT INTO `jual_beli` (`nama`, `harga`, ";
		$values = "VALUES (?,?,";
		$array = array($nama, $harga, $email);
		if(isset($foto)){
			$sql .= "`foto`, ";
			$values .= "?,";
			array_push($array, $foto);
		}
		$sql .= "`deskripsi`, `created_at`, `id_user`, `id_kota`, `id_kelas`, `id_kategori`) ".$values ."?,NOW(),?,?,?,?);";
		array_push($array, $deskripsi, $id_user, $id_kota, $id_kelas, $id_kategori);
		$result = $this->db->query($sql, $array);

		$sql2 = "SELECT LAST_INSERT_ID() as id";
		$hasil = $this->db->query($sql2);
		$id = $hasil->row()->id;

		$this->Notifikasi_Model->insert_notifiksai("telah menjual barang ".$nama,"blabl.html?id_jualbeli=".$id, $id_user);

		return $hasil;
	}

	public function get_all_jualBeli($id_user){

		$sql = "SELECT j.*, ka.name as kategori_name, u.id as user_id, u.nama as user_nama, u.foto as user_foto, IFNULL(count(k.id),0) as count_komentar
				FROM jual_beli j
        LEFT JOIN users u ON  u.id = j.id_user
        LEFT JOIN kategori ka ON  ka.id = j.id_kategori
        LEFT JOIN komentar k ON k.id_jualbeli = j.id";
		$values = [];
		$array_kelas = $this->UsersKelas_Model->get_allKelas_byUser($id_user);
       
        $id_kelas_sql = "";
        foreach ($array_kelas as $id_kelas) {
            $id_kelas_sql .= "j.id_kelas = ? OR ";
            array_push($values, $id_kelas["id_kelas"]);
        }
        $len = strlen($id_kelas_sql);
        $id_kelas_sql = substr($id_kelas_sql, 0, $len - 3);

		$sql .= " WHERE (".$id_kelas_sql.") AND j.id_user = u.id
              GROUP BY j.id
              ORDER BY j.created_at DESC";
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

	public function get_userLapak($id_user){
		$sql = "SELECT j.*, ka.name as kategori_name, u.id as user_id, u.nama as user_nama, u.foto as user_foto, IFNULL(count(k.id),0) as count_komentar
				FROM jual_beli j
				LEFT JOIN users u ON  u.id = j.id_user
        LEFT JOIN kategori ka ON  ka.id = j.id_kategori
        LEFT JOIN komentar k ON k.id_jualbeli = j.id
				WHERE j.id_user = ?
        GROUP BY j.id
        ORDER BY j.created_at DESC";
		$hasil = $this->db->query($sql, array($id_user));

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

	public function update_jualBeli($id_jualbeli, $nama, $harga, $foto, $deskripsi, $id_kelas, $id_kategori, $id_kota, $id_user){
        $sql="UPDATE `jual_beli` SET `nama`=?,`harga`=?,`deskripsi`=?,`id_kelas`=?,`id_kota`=?, `id_kategori` = ?";
        $array=array($nama, $harga, $deskripsi, $id_kelas, $id_kota, $id_kategori);
        if(isset($foto)){
            $sql .= " ,`foto`=?";
            array_push($array, $foto);
        }
        $sql .= " WHERE id=?";
        array_push($array, $id_jualbeli);
        
        $result = $this->db->query($sql, $array);

        $this->Notifikasi_Model->insert_notifiksai("telah mengupdate Lapak ".$nama,"blabl.html?id_jualbeli=".$id_jualbeli,$id_user);

        return $result;
    }

     public function delete_jualBeli($id){
        $sql = "DELETE FROM jual_beli WHERE id = ?";
        return $this->db->query($sql, array($id));
    }
}