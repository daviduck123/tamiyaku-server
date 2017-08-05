<?php
class Event_Model extends CI_Model {

    public function __construct()
    {
        $this->load->database();

        $this->load->model("Notifikasi_Model");
    }


    public function insert_event($nama, $tanggal, $tempat, $hadiah1, $hadiah2, $hadiah3, $harga_tiket, $deskripsi,  $lat, $lng,
      $foto, $id_user, $id_kota, $id_kelas){
        $sql = "INSERT INTO `event` (`nama`, `tanggal`, `tempat`, `lat`, `lng`, `hadiah1`, ";
        $values = "VALUES (?,?,?,?,";
        $array = array($nama, $tanggal, $tempat, $lat, $lng, $hadiah1);
        if(isset($hadiah2)){
            $sql .= "`hadiah2`,";
            $values .= "?,";
            array_push($array, $hadiah2);
        }
        if(isset($hadiah3)){
            $sql .= "`hadiah3`,";
            $values .= "?,";
            array_push($array, $hadiah3);
        }
        if(isset($foto)){
            $sql .= "`foto`,";
            $values .= "?,";
            array_push($array, $foto);
        }
        $sql .= "`harga_tiket`, `deskripsi`, `id_user`, `id_kota`, `id_kelas`, `created_at`) ".  $values . "?,?,?,?,?,NOW());";
        array_push($array, $harga_tiket, $deskripsi, $id_user, $id_kota, $id_kelas);

         $this->db->query($sql, $array);

         $sql2 = "SELECT LAST_INSERT_ID() as id";
         $hasil = $this->db->query($sql2);
         $id = $hasil->row()->id;
         
         $this->Notifikasi_Model->insert_notifiksai("telah membuat Lomba ".$nama,"blabl.html?id_event=".$id,$id_user);

         return $id;
    }

    public function get_allEventByUserKelas($id_user){
        $sql = "SELECT e.*, u.id as user_id, u.nama as user_nama, u.foto as user_foto, IFNULL(count(k.id),0) as count_komentar
                FROM event e
                LEFT JOIN users u ON e.id_user = u.id
                LEFT JOIN komentar k ON k.id_event = e.id
                LEFT JOIN (SELECT uk.* from users_kelas uk WHERE uk.id_user = ?) as t1 ON t1.id_kelas = e.id_kelas
                GROUP BY e.id
                ORDER BY e.created_at DESC";
        $hasil = $this->db->query($sql, array($id_user));
        $events = $hasil->result_array();
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
    public function update_event($id_event, $nama, $tanggal, $tempat, $hadiah1, $hadiah2, $hadiah3, $foto, $harga_tiket, $deskripsi, $lat, $lng, $id_kota, $id_kelas, $id_user){
      $sql = "UPDATE `event` SET `nama`=?,`tanggal`=?,`tempat`=?,`hadiah1`=?,`harga_tiket`=?,`deskripsi`=?,`id_kota`=?,`id_kelas`=?";
      $array=array($nama, $tanggal, $tempat, $hadiah1, $harga_tiket, $deskripsi, $id_kota, $id_kelas);
       if(isset($hadiah2)){
            $sql .= ",`hadiah2`=?";
            array_push($array, $hadiah2);
        }
        if(isset($hadiah3)){
            $sql .= ",`hadiah3`=?";
            array_push($array, $hadiah3);
        }
        if(isset($lat)){
            $sql .= ",`lat`=?";
            array_push($array, $lat);
        }
        if(isset($lng)){
            $sql .= ",`lng`=?";
            array_push($array, $lng);
        }
        if(isset($foto)){
            $sql .= ",`foto`=?";
            array_push($array, $foto);
        }
        $sql .= " WHERE id = ?";
        array_push($array, $id_event);

        $result = $this->db->query($sql, $array);

        $this->Notifikasi_Model->insert_notifiksai("telah mengupdate Lomba ".$nama,"blabl.html?id_event=".$id_event,$id_user);

        return $result;
    }

    public function delete_event($id){
        $sql = "DELETE FROM event WHERE id = ?";
        $result = $this->db->query($sql, array($id));
        return $result;
    }
}