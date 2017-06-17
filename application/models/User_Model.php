<?php
class User_Model extends CI_Model {

	public function __construct()
	{
		$this->load->database();

		$this->load->model("UsersKelas_Model");
		$this->load->model("Notifikasi_Model");
	}

	public function get_userByemail($email){
		$sql="SELECT u.*, count(t1.id_post) as count_post, count(t2.id_teman) as count_teman 
		FROM users u 
		LEFT JOIN ( SELECT p.id as id_post, u.id as id_user FROM users u, post p WHERE u.id = p.id_user ) as t1 ON t1.id_user = u.id 
		LEFT JOIN ( SELECT ut.id_teman, u.id as id_user FROM users u, users_teman ut WHERE u.id = ut.id_user ) as t2 ON t2.id_user = u.id 
		WHERE u.email = ? 
		HAVING u.id IS NOT NULL";
		$hasil = $this->db->query($sql, array($email));
		$user = $hasil->row_array();
		$user_foto = $user["foto"];
        $user['foto'] = base64_encode($user_foto);
		return $user;
	}

	public function get_userBySearch($param){
		$sql="SELECT u.* 
				FROM users u 
				WHERE u.nama LIKE '%".$param."%' OR u.email LIKE '%".$param."%'
				ORDER BY u.nama ASC";
		$hasil = $this->db->query($sql);
		$users = $hasil->result_array();
		$users2 = [];
		foreach($users as $user){
		  $user_foto = $user["foto"];
		  $user['foto'] = base64_encode($user_foto);
		  array_push($users2, $user);
		}
		return $users2;
	}

	public function insert_user($nama, $password, $id_kota, $email, $jenis_kelamin, $file, $array_id_kelas){
		$sql = "INSERT INTO `users` (`nama`, `password`, `id_kota`, `email`, `jenis_kelamin`,";
		$values = "VALUES (?,?,?,?,?,";
		$array = array($nama, $password, $id_kota, $email, $jenis_kelamin);
		if(isset($foto)){
			$sql .= "`foto`, ";
			$values .= "?,";
			array_push($array, $foto);
		}

		$sql .= "`created_at`) ".$values ."NOW());";
		$this->db->query($sql, $array);

		$sql2 = "SELECT LAST_INSERT_ID() as id";
		$hasil = $this->db->query($sql2);
		$id_user = $hasil->row()->id;

		foreach ($array_id_kelas as $id_kelas) {
			$this->UsersKelas_Model->insert_users_Kelas($id_user, $id_kelas);
		}
		return $hasil;
	}

	public function get_temanByIdUser($id_user){
		$sql = "SELECT u.*
				FROM users u, users_teman ut
				WHERE ut.id_user = ? AND ut.id_teman = u.id
				ORDER BY u.nama ASC";
		$hasil = $this->db->query($sql, array($id_user));
		$users = $hasil->result_array();
		$users2 = [];
		foreach($users as $user){
		  $user_foto = $user["foto"];
		  $user['foto'] = base64_encode($user_foto);
		  array_push($users2, $user);
		}
		return $users2;
	}

	public function get_infoById($id_user){
		$sql = "SELECT u.*
				FROM users u
				WHERE u.id = ?";
		$hasil = $this->db->query($sql, array($id_user));
		$user = $hasil->row_array();
		$user_foto = $user["foto"];
        $user['foto'] = base64_encode($user_foto);
		return $user;
	}

	public function update_user($id_user, $email, $nama, $password, $jenis_kelamin, $foto, $id_kota){
        $sql="UPDATE `users` SET `email`=?,`nama`=?,`password`=?,`jenis_kelamin`=?,`id_kota`=?";
        $array=array($nama, $harga, $deskripsi, $id_kelas, $id_kota);
        if(isset($foto)){
            $sql .= " ,`foto`=?,";
            array_push($array, $foto);
        }
        $sql .= " WHERE id=?";
        array_push($array, $id_user);
        
        $result = $this->db->query($sql, $array);

        return $result;
    }
	
}