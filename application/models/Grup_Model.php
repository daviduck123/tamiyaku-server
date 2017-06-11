<?php
class Grup_Model extends CI_Model {

    public function __construct()
    {
        $this->load->database();

        //$this->load->model("GrupKelas_Model");
		$this->load->model("Grup_Model");
        $this->load->model("UsersKelas_Model");
        $this->load->model("UsersGrup_Model");
    }


    public function insert_grup($nama, $lat, $lng, $lokasi, $foto, $id_kota, $id_user, $id_kelas){
        $sql = "INSERT INTO `grup` (`nama`, `lat`, `lng`, `lokasi`,";
        $values = "VALUES (?,?,?,?,";
        $array = array($nama, $lat, $lng, $lokasi);
        if(isset($foto)){
            $sql .= " `foto`,";
            $values .= "?,";
            array_push($array, $foto);
        }
        $sql .= "`id_kota`, `id_user`, `id_kelas`, `created_at`) ".  $values . "?,?,?,NOW());";
        array_push($array, $id_kota, $id_user, $id_kelas);

        $this->db->query($sql, $array);

        $sql2 = "SELECT LAST_INSERT_ID() as id";
        $hasil = $this->db->query($sql2);
        $id_grup = $hasil->row()->id;

        //Save User Grup (Member)
        $this->UsersGrup_Model->insert_usersGrup($id_user, $id_grup);

        return $hasil;
    }

    //for searching purpose
    public function get_allGrup_byLatLng($lat, $lng, $id_user){
        $sql ="SELECT g.*, ( 6371 * acos( cos( radians(?) ) * cos( radians( g.lat ) ) * cos( radians( g.lng ) - radians(?) ) + sin( radians(?) ) * sin( radians( g.lat ) ) ) ) AS distance
                FROM grup g";
        $values = [];
        array_push($values, $lat, $lng, $lat);

        $array_kelas = $this->UsersKelas_Model->get_allKelas_byUser($id_user);
       
        foreach ($array_kelas as $id_kelas) {
            $id_kelas_sql = "g.id_kelas = ? OR ";
            array_push($values, $id_kelas);
        }
        $len = strlen($id_kelas_sql);
        substr($id_kelas_sql, 0, $len - 3);

        $sql .= "WHERE ".$id_kelas_sql."
                HAVING distance < 20"; //in 20 km radius
      
        $hasil = $this->db->query($sql, $values);

        $grups = $hasil -> result_array();
        $grups2 = [];
        foreach($grups as $grup){
          $grup_foto = $grup["foto"];
          $grup['foto'] = base64_encode($grup_foto);
          array_push($grups2, $grup);
        }
        return $grups2;
    }

    public function get_allGrup_byUser($id_user){
        $sql ="SELECT g.*
                FROM grup g, users_grup ug
                WHERE ug.id_grup = g.id AND ug.id_user = ?";
        $hasil = $this->db->query($sql, array($id_user));
        $grups = $hasil -> result_array();
        $grups2 = [];
        foreach($grups as $grup){
          $grup_foto = $grup["foto"];
          $grup['foto'] = base64_encode($grup_foto);
          array_push($grups2, $grup);
        }
        return $grups2;
    }

    public function get_grupInfo($id_grup){
        $sql ="SELECT g.*, IFNULL(count(ug.id_user),0) as count_member
                FROM grup g
                LEFT JOIN users_grup ug ON ug.id_grup = g.id
                WHERE g.id = ?";
        $hasil = $this->db->query($sql, array($id_grup));
        $grups = $hasil -> result_array();
        $grups2 = [];
        foreach($grups as $grup){
          $grup_foto = $grup["foto"];
          $grup['foto'] = base64_encode($grup_foto);
          array_push($grups2, $grup);
        }
        return $grups2;
    }
}