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

    public function get_all_event(){
        $sql = "SELECT e.*, u.id, u.nama
                FROM event e, users u
                WHERE e.id_user = u.id
                ORDER BY e.created_at DESC";
        $hasil = $this->db->query($sql);
        $events = $hasil->row_array();
        $events2 = [];
        foreach($events as $event){
          $event_foto = $event["foto"];
          $event['foto'] = base64_encode($event_foto);
          array_push($events2, $event);
        }
        return $events2;
    }
}