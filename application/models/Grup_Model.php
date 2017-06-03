<?php
class Grup_Model extends CI_Model {

    public function __construct()
    {
        $this->load->database();

        $this->load->model("GrupKelas_Model");
        $this->load->model("UsersKelas_Model");
        $this->load->model("UsersGrup_Model");
    }


    public function insert_grup($nama, $lat, $lng, $id_user, $id_kelas){
        $sql = "INSERT INTO `grup` (`nama`, `lat`, `lng`, `id_user`, `id_kelas`, `created_at`) VALUES (?,?,?,?,?,NOW())";
        $this->db->query($sql, array($nama, $lat, $lng, $id_user, $id_kelas));

        $sql2 = "SELECT LAST_INSERT_ID() as id";
        $hasil = $this->db->query($sql2);
        $id_grup = $hasil->row()->id;

        //Save User Grup (Member)
        $this->UsersGrup_Model->insert_usersGrup($id_user, $id_grup);

        return $hasil;
    }

    public function get_allGrup_byLatLng($lat, $lng, $id_user){

        $array_kelas = $this->UsersKelas_Model->get_allKelas_byUser($id_user);
        $values = [];
        foreach ($array_kelas as $id_kelas) {
            $id_kelas_sql = "id_kelas = ? OR";
            array_push($values, $id_kelas);
        }
        $len = strlen($id_kelas_sql);
        substr($id_kelas_sql, 0, $len - 2);

        $sql ="SELECT g.* 
                FROM (SELECT id_grup from grup_kelas WHERE ".$id_kelas_sql." ) as t1, grup g
                WHERE t1.id_grup = g.id AND g.lat = ? AND g.lng = ?";
        array_push($values, $lat, $lng);
        $this->db->query($sql, array($nama, $lat, $lng, $id_user));
    }

    public function get_allGrup_byUser($id_user){

        $array_kelas = $this->UsersKelas_Model->get_allKelas_byUser($id_user);
        $values = [];
        foreach ($array_kelas as $id_kelas) {
            $id_kelas_sql = "id_kelas = ? OR";
            array_push($values, $id_kelas);
        }
        $len = strlen($id_kelas_sql);
        substr($id_kelas_sql, 0, $len - 2);

        $sql ="SELECT g.* 
                FROM (SELECT id_grup from grup_kelas WHERE ".$id_kelas_sql." ) as t1, grup g
                WHERE t1.id_grup = g.id AND g.lat = ? AND g.lng = ?";
        array_push($values, $lat, $lng);
        $this->db->query($sql, array($nama, $lat, $lng, $id_user));
    }
}