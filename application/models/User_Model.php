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
		if(count($user) > 0){
	        $user_foto = $user["foto"];
        	$user['foto'] = base64_encode($user_foto);
		}
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

	public function insert_user($nama, $password, $id_kota, $email, $uniqueId, $jenis_kelamin, $foto, $array_id_kelas){
		$sql = "INSERT INTO `users` (`nama`, `password`, `id_kota`, `email`, `uniqueId`, `verified`, `jenis_kelamin`,";
		$values = "VALUES (?,?,?,?,?,'0',?,";
		$array = array($nama, $password, $id_kota, $email, $uniqueId, $jenis_kelamin);
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
	
	public function get_isTeman($id_user, $id_teman){
		$sql ="SELECT ut.*
				FROM users_teman ut
				WHERE ut.id_user = ? AND ut.id_teman = ?";
		$hasil = $this->db->query($sql, array($id_user, $id_teman));
		$users = $hasil->result_array();
		return $users;
	}

	public function get_infoById($id_user){
		$sql = "SELECT u.*
				FROM users u
				WHERE u.id = ?";
		$hasil = $this->db->query($sql, array($id_user));
		$user = $hasil->row_array();
		if(count($user) > 0){
	        $user_foto = $user["foto"];
        	$user['foto'] = base64_encode($user_foto);
		}
		return $user;
	}

	public function update_verified($id_user){
		$sql="UPDATE `users` SET `verified`=1 WHERE id=?";        
        $result = $this->db->query($sql, array($id_user));

        return $result;
	}

	public function update_user($id_user, $email, $nama, $password, $jenis_kelamin, $foto, $id_kota, $array_id_kelas){
        $sql="UPDATE `users` SET ";
        $array=array();
        if(isset($foto)){
            $sql .= " `foto`=?,";
            array_push($array, $foto);
        }
         if(isset($email)){
            $sql .= " `email`=?,";
            array_push($array, $email);
        }
         if(isset($password)){
            $sql .= " `password`=?,";
            array_push($array, $password);
        }
         if(isset($jenis_kelamin)){
            $sql .= " `jenis_kelamin`=?,";
            array_push($array, $jenis_kelamin);
        }
        if(isset($id_kota)){
            $sql .= " `id_kota`=?,";
            array_push($array, $id_kota);
        }
        if(isset($nama)){
            $sql .= " `nama`=?,";
            array_push($array, $nama);
        }
        $sql2 = substr($sql, 0, -1);
        $sql2 .= " WHERE id=?";
        array_push($array, $id_user);
        
        $result = $this->db->query($sql2, $array);

        $this->UsersKelas_Model->delete_userAndKelas($id_user);
        
        foreach ($array_id_kelas as $id_kelas) {
			$this->UsersKelas_Model->insert_users_Kelas($id_user, $id_kelas);
		}
        return $result;
    }
	
}