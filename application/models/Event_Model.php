<?php
class Event_Model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }


    public function insert_event($nama, $tanggal, $tempat, $hadiah1, $hadiah2, $hadiah3, $harga_tiket, $deskripsi, 
      $foto, $id_user, $id_kota){
        $sql = "INSERT INTO `event` (`nama`, `tanggal`, `tempat`, `hadiah1`, ";
        $values = "VALUES (?,?,?,?,";
        $array = array($nama, $tanggal, $tempat, $hadiah1);
        if(isset($hadiah2)){
            $sql .= "`hadiah2`,";
            $values .= "?,"
            array_push($array, $hadiah2)
        }
        if(isset($hadiah3)){
            $sql .= "`hadiah3`,";
            $values .= "?,"
            array_push($array, $hadiah3)
        }
        if(isset($foto)){
            $sql .= "`foto`,";
            $values .= "?,"
            array_push($array, $foto)
        }
        $sql .= "`harga_tiket`, `deskripsi`, `id_user`, `id_kota`, `created_at`) ".  $values . "?,?,?,?,NOW());";
        array_push($array, $harga_tiket, $deskripsi, $id_user, $id_kota);

         $this->db->query($sql, $array);

         $sql2 = "SELECT LAST_INSERT_ID() as id";
         $hasil = $this->db->query($sql2);
         return $hasil->row()->id;
    }

    public function get_allEventByUserKelas($id_user){
        $sql = "SELECT e.*, u.id as user_id, u.nama, u.foto as user_foto
                FROM event e, users u, (SELECT uk.* from users_kelas uk WHERE uk.id_user = ?) as t1
                WHERE u.id = e.id_user AND t1.id_kelas = e.id_kelas
                ORDER BY e.created_at DESC";
        $hasil = $this->db->query($sql, array($id_user));
        $events = $hasil->row_array();
        $events2 = [];
        foreach($events as $event){
          $event_foto = $event["foto"];
          $event['foto'] = base64_encode($event_foto);

          $user_foto = $event["user_foto"];
          $event['user_foto'] = base64_encode($user_foto);
          array_push($events2, $event);
        }
        return $events2;
    }
}