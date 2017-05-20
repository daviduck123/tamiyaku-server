<?php
class FirstDatabase_Model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    public function create_tables(){
       
        $this->create_Kelas();
        $this->create_Kota();
        $this->create_Users();
        $this->create_UsersKelas();
    }

    public function create_Kelas(){
        $sql = "SHOW TABLES LIKE 'Kelas'";
        $exist = $this->db->query($sql);
        if($exist->num_rows() == 0){
             $sql2="CREATE TABLE `Kelas` ( `id` INT NOT NULL AUTO_INCREMENT , `nama` VARCHAR(255) NOT NULL , `deskripsi` TEXT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;";
             $this->db->query($sql2);


             $sql3 ="INSERT INTO `Kelas` (`id`,`nama`, `deskripsi`) VALUES(?,?,?)";
             $this->db->query($sql3, array(1,"STB","Standard Tamiya Box adalah sebuah kelas dimana mobil yang digunakan adalah murni dari box pembelian, tanpa adanya modifikasi berlebihan pada bagian mobil maupun sparepart."));

             $sql3 ="INSERT INTO `Kelas` (`id`,`nama`, `deskripsi`) VALUES(?,?,?)";
             $this->db->query($sql3, array(2,"STO","Standard Tamiya Original adalah sebuah kelas dimana mobil dan sparepart yang digunakan harus bermerk \"Tamiya\". Mobil dapat dimodifikasi sesuai regulasi yang berlaku. Terdiri 
                 dari STO 100, STO 75, dan STO 50."));

             $sql3 ="INSERT INTO `Kelas` (`id`,`nama`, `deskripsi`) VALUES(?,?,?)";
             $this->db->query($sql3, array(3,"Speed","Speed adalah sebuah mobil Mini4WD yang dimodifikasi sedemikian rupa untuk mendapatkan kecepatan yang maksimal. Kelas ini mengakomodasi kelas Speed, Sloop, Drag, dan Nascar."));
        }
    }

    public function create_Kota(){
        $sql = "SHOW TABLES LIKE 'Kota'";
        $exist = $this->db->query($sql);
       if($exist->num_rows() == 0){
            $sql2="CREATE TABLE `Kota` ( `id` INT NOT NULL AUTO_INCREMENT , `nama` VARCHAR(255) NOT NULL , `created_at` DATETIME NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;"; 
            $this->db->query($sql2);
        }
    }

    public function create_Users(){
        $sql = "SHOW TABLES LIKE 'Users'";
        $exist = $this->db->query($sql);
        if($exist->num_rows() == 0){
            $sql2="CREATE TABLE `Users` ( `id` INT NOT NULL AUTO_INCREMENT , `email` VARCHAR(255) NULL , `no_hp` VARCHAR(12) NOT NULL , `nama` VARCHAR(255) NOT NULL ,`password` VARCHAR(255) NOT NULL , `jenis_kelamin` ENUM('Laki-laki','Perempuan') NOT NULL , `foto` BLOB NULL , `lat` FLOAT NULL , `lng` FLOAT NULL , `id_kota` INT NULL , `created_at` DATETIME NOT NULL , PRIMARY KEY (`id`), UNIQUE (`email`), UNIQUE (`no_hp`), UNIQUE(`id_kota`)) ENGINE = InnoDB;";
            $this->db->query($sql2);

            $sql3="ALTER TABLE `users` ADD CONSTRAINT `fk_user_kota` FOREIGN KEY (`id_kota`) REFERENCES `kota`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;";
            $this->db->query($sql3);
        }
    } 

    public function create_UsersKelas(){
        $sql = "SHOW TABLES LIKE 'users_kelas'";
        $exist = $this->db->query($sql);
        if($exist->num_rows() == 0){
            $sql2="CREATE TABLE `users_kelas` ( `id_user` INT NOT NULL , `id_kelas` INT NOT NULL , PRIMARY KEY (`id_user`,`id_kelas`)) ENGINE = InnoDB;";
            $this->db->query($sql2);
            $sql3 = "ALTER TABLE `users_kelas` ADD CONSTRAINT `fk_kelas_userskelas` FOREIGN KEY (`id_kelas`) REFERENCES `kelas`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;";
            $this->db->query($sql3);

            $sql4 ="ALTER TABLE `users_kelas` ADD CONSTRAINT `fk_users_userskelas` FOREIGN KEY (`id_user`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;";
            $this->db->query($sql4);
        }
    }
}