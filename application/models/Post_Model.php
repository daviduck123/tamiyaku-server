<?php
class Post_Model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
        $this->load->model("Notifikasi_Model");
    }


    public function insert_post($deskripsi, $id_user, $id_grup, $foto){
       $sql = "INSERT INTO `post` (`deskripsi`, ";
       $values = "VALUES(?,";
       $array = [];
       array_push($array, $deskripsi);
       if(isset($foto)){
          $sql = $sql ." `foto`,";
          $values .= "?,";
          array_push($array, $foto);
       }
       if(isset($id_user)){
         $sql = $sql ." `id_user`,";
          $values .= "?,";
          array_push($array, $id_user);
       }
       if(isset($id_grup)){
         $sql = $sql ." `id_grup`,";
          $values .= "?,";
          array_push($array, $id_grup);
       }
       $sql = $sql ."`created_at`) ".$values."NOW());";

       $this->db->query($sql, $array);

       $sql2 = "SELECT LAST_INSERT_ID() as id";
       $hasil = $this->db->query($sql2);

       $id = $hasil -> row()->id;
       $this->Notifikasi_Model->insert_notifiksai("telah menulis status","blabl.html?id_post="+$id, $id_user);

       return $id;
    }

    public function get_all_post(){
       $sql = "SELECT * FROM post ORDER BY created_at DESC";
       $hasil = $this->db->query($sql);
       $post = $hasil->result_array();
       $post2 = [];
       foreach($post as $posting){
          $posting_foto = $posting["foto"];
          $posting['foto'] = base64_encode($posting_foto);
          array_push($post2, $posting);
       }
       return $post2;
    }  

    public function get_post_byIdUser($id_user){
       $sql = "SELECT p.*, u.id as user_id, u.nama, u.foto as user_foto, IFNULL(count(k.id),0) as count_komentar
                FROM post p
                LEFT JOIN users u ON p.id_user = u.id
                LEFT JOIN komentar k ON k.id_post = p.id
                WHERE p.id_user = ? AND p.id_grup IS NULL
                GROUP BY p.id
                ORDER BY p.created_at DESC";
       $hasil = $this->db->query($sql, array($id_user));
       $post = $hasil->result_array();
       $post2 = [];
       foreach($post as $posting){
          $posting_foto = $posting["foto"];
          $user_foto = $posting["user_foto"];
          $posting['foto'] = base64_encode($posting_foto);
          $posting['user_foto'] = base64_encode($user_foto);
          array_push($post2, $posting);
       }
       return $post2;
    }

    public function get_all_friendPost($id_user){
       $sql = "SELECT p.*, u.id as user_id, u.nama, u.foto as user_foto, IFNULL(count(k.id),0) as count_komentar
                FROM post p
                LEFT JOIN users u ON p.id_user = u.id
                LEFT JOIN users_teman ut ON p.id_user = ut.id_user OR p.id_user = ut.id_user
                LEFT JOIN komentar k ON k.id_post = p.id
                WHERE p.id_user = ? AND p.id_grup IS NULL
                GROUP BY p.id
                ORDER BY p.created_at DESC";
       $hasil = $this->db->query($sql, array($id_user));
       $post = $hasil->result_array();
       $post2 = [];
       foreach($post as $posting){
          $posting_foto = $posting["foto"];
          $user_foto = $posting["user_foto"];
          $posting['foto'] = base64_encode($posting_foto);
          $posting['user_foto'] = base64_encode($user_foto);
          array_push($post2, $posting);
       }
       return $post2;
    }

    public function get_post_byIdGrup($id_grup){
       $sql = "SELECT p.*, u.id as user_id, u.nama, u.foto as user_foto, IFNULL(count(k.id),0) as count_komentar
                FROM post p
                LEFT JOIN users u ON p.id_user = u.id
                LEFT JOIN komentar k ON k.id_post = p.id
                WHERE p.id_grup = ?
                GROUP BY p.id
                ORDER BY p.created_at DESC";
       $hasil = $this->db->query($sql, array($id_grup));
       $post = $hasil->result_array();
       $post2 = [];
       foreach($post as $posting){
          $posting_foto = $posting["foto"];
          $user_foto = $posting["user_foto"];
          $posting['foto'] = base64_encode($posting_foto);
          $posting['user_foto'] = base64_encode($user_foto);
          array_push($post2, $posting);
       }
       return $post2;
    }  
    public function update_post($id_post, $deskripsi, $foto, $id_user, $id_grup){
        $sql="UPDATE `post` SET `deskripsi`=?";
        $array=array($nama, $harga, $deskripsi, $id_kelas, $id_kota);
        if(isset($foto)){
            $sql .= " ,`foto`=?,";
            array_push($array, $foto);
        }
       if(isset($id_user)){
         $sql = $sql .",`id_user`=?";
          array_push($array, $id_user);
       }
       if(isset($id_grup)){
         $sql = $sql .",`id_grup`=?";
          array_push($array, $id_grup);
       }
        $sql .= " WHERE id=?";
        array_push($array, $id_post);
        
        $result = $this->db->query($sql, $array);

        $this->Notifikasi_Model->insert_notifiksai("telah mengupdate Postingan "+$nama,"blabl.html?id_post="+$id_post,$id_user);

        return $result;
    }

    public function delete_post($id){
        $sql = "DELETE FROM post WHERE id = ?";
        return $this->db->query($sql, array($id));
    }

}