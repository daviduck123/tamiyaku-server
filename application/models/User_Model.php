<?php
class User_Model extends CI_Model {

	public function __construct()
	{
		$this->load->database();

		$this->load->model("UsersKelas_Model");
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
		$sql="SELECT * 
				FROM users u 
				WHERE u.nama LIKE '%".$param."%' OR u.email LIKE '%".$param."%'";
		$hasil = $this->db->query($sql);
		$users = $hasil->result_array();
		$users2 = [];
		foreach($users as $user){
		  $user_foto = $user["foto"];
		  $user['foto'] = base64_encode($user_foto);
		  array_push($users2, $user);
		}
		return $users;
	}

	public function insert_user($nama, $password, $id_kota, $email, $jenis_kelamin, $file, $array_id_kelas){
		$sql = "INSERT INTO `users` (`nama`, `password`, `id_kota`, `email`, `jenis_kelamin`,`foto`,`created_at`) VALUES (?,?,?,?,?,?,NOW())";
		$this->db->query($sql, array($nama, $password, $id_kota, $email, $jenis_kelamin, $file));

		$sql2 = "SELECT LAST_INSERT_ID() as id";
		$hasil = $this->db->query($sql2);
		$id_user = $hasil->row()->id;

		foreach ($array_id_kelas as $id_kelas) {
			$this->UsersKelas_Model->insert_users_Kelas($id_user, $id_kelas);
		}
		return $hasil;
	}

	public function insert_Friend($id_user, $id_teman){
		$sql = "INSERT INTO `users_teman` (`id_user`, `id_teman`) VALUES (?,?)";
		$hasil = $this->db->query($id_user, $id_teman);
		return $hasil;
	}
}