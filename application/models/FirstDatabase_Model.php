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
        $this->create_Grup();
        $this->create_UsersGrup();
        $this->create_GrupKelas();
        $this->create_Post();
        $this->create_Komentar();
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
            $sql2="CREATE TABLE `Kota` (`id` INT NOT NULL AUTO_INCREMENT , `nama` VARCHAR(255) NOT NULL , `created_at` DATETIME NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;"; 
            $this->db->query($sql2);

            $sql3 = "INSERT INTO `kota` (`nama`, `created_at`) VALUES (?, NOW());";
            $this->db->query($sql3, array("Surabaya"));
            $sql3 = "INSERT INTO `kota` (`nama`, `created_at`) VALUES (?, NOW());";
            $this->db->query($sql3, array("Jakarta"));
            $sql3 = "INSERT INTO `kota` (`nama`, `created_at`) VALUES (?, NOW());";
            $this->db->query($sql3, array("Semarang"));
            $sql3 = "INSERT INTO `kota` (`nama`, `created_at`) VALUES (?, NOW());";
            $this->db->query($sql3, array("Bandung"));
            $sql3 = "INSERT INTO `kota` (`nama`, `created_at`) VALUES (?, NOW());";
            $this->db->query($sql3, array("Denpasar"));
        }
    }

    public function create_Users(){
        $sql = "SHOW TABLES LIKE 'Users'";
        $exist = $this->db->query($sql);
        if($exist->num_rows() == 0){
            $sql2="CREATE TABLE `Users` ( `id` INT NOT NULL AUTO_INCREMENT , `email` VARCHAR(255) NOT NULL , `nama` VARCHAR(255) NOT NULL ,`password` VARCHAR(255) NOT NULL , `jenis_kelamin` ENUM('Laki-laki','Perempuan') NOT NULL , `foto` BLOB NULL , `lat` FLOAT NULL , `lng` FLOAT NULL , `id_kota` INT NULL , `created_at` DATETIME NOT NULL , PRIMARY KEY (`id`), UNIQUE (`email`), INDEX(`id_kota`)) ENGINE = InnoDB;";
            $this->db->query($sql2);

            $sql3="ALTER TABLE `users` ADD CONSTRAINT `fk_users_kota` FOREIGN KEY (`id_kota`) REFERENCES `kota`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;";
            $this->db->query($sql3);

            //Create User - Teman relationship
            $sql4 = "CREATE TABLE `tamiyaku`.`users_teman` ( `id_user` INT NOT NULL , `id_teman` INT NOT NULL , PRIMARY KEY (`id_user`, `id_teman`)) ENGINE = InnoDB;";
            $this->db->query($sql4);

            $sql4 = "ALTER TABLE `users_teman` ADD CONSTRAINT `fk_usersteman_user` FOREIGN KEY (`id_user`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;";
            $this->db->query($sql4);

            $sql4 = "ALTER TABLE `users_teman` ADD CONSTRAINT `fk_usersteman_teman` FOREIGN KEY (`id_teman`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;";
            $this->db->query($sql4);
        }
    } 

    public function create_UsersKelas(){
        $sql = "SHOW TABLES LIKE 'users_kelas'";
        $exist = $this->db->query($sql);
        if($exist->num_rows() == 0){
            $sql2="CREATE TABLE `users_kelas` ( `id_user` INT NOT NULL , `id_kelas` INT NOT NULL , PRIMARY KEY (`id_user`,`id_kelas`)) ENGINE = InnoDB;";
            $this->db->query($sql2);
            $sql3 = "ALTER TABLE `users_kelas` ADD CONSTRAINT `fk_userskelas_kelas` FOREIGN KEY (`id_kelas`) REFERENCES `kelas`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;";
            $this->db->query($sql3);

            $sql4 ="ALTER TABLE `users_kelas` ADD CONSTRAINT `fk_userskelas_user` FOREIGN KEY (`id_user`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;";
            $this->db->query($sql4);
        }
    }

    public function create_Grup(){
        $sql = "SHOW TABLES LIKE 'grup'";
        $exist = $this->db->query($sql);
        if($exist->num_rows() == 0){
            $sql2 = "CREATE TABLE `grup` ( `id` INT NOT NULL AUTO_INCREMENT , `nama` VARCHAR(255) NOT NULL , `lat` FLOAT NOT NULL , `lng` FLOAT NOT NULL , `created_at` DATE NOT NULL , `id_user` INT NOT NULL , PRIMARY KEY (`id`), INDEX (`id_user`)) ENGINE = InnoDB;";
            $this->db->query($sql2);

            $sql3 = "ALTER TABLE `grup` ADD CONSTRAINT `fk_grup_user` FOREIGN KEY (`id_user`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;";
            $this->db->query($sql3);
        }
    }

    public function create_UsersGrup(){
        $sql = "SHOW TABLES LIKE 'users_grup'";
        $exist = $this->db->query($sql);
        if($exist->num_rows() == 0){
            $sql2 = "CREATE TABLE `users_grup` ( `id_user` INT NOT NULL , `id_grup` INT NOT NULL , PRIMARY KEY (`id_user`, `id_grup`)) ENGINE = InnoDB;";
            $this->db->query($sql2);

            $sql3 = "ALTER TABLE `users_grup` ADD CONSTRAINT `fk_usersgrup_user` FOREIGN KEY (`id_user`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;";
            $this->db->query($sql3);

            $sql3 = "ALTER TABLE `users_grup` ADD CONSTRAINT `fk_usersgrup_grup` FOREIGN KEY (`id_grup`) REFERENCES `grup`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;";
            $this->db->query($sql3);
        }
        
    }

    public function create_GrupKelas(){
        $sql = "SHOW TABLES LIKE 'grup_kelas'";
        $exist = $this->db->query($sql);
        if($exist->num_rows() == 0){
            $sql2 = "CREATE TABLE `grup_kelas` ( `id_grup` INT NOT NULL , `id_kelas` INT NOT NULL , PRIMARY KEY (`id_grup`,`id_kelas`)) ENGINE = InnoDB;";
            $this->db->query($sql2);

            $sql3 = "ALTER TABLE `grup_kelas` ADD CONSTRAINT `fk_grupkelas_grup` FOREIGN KEY (`id_grup`) REFERENCES `grup`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;";
            $this->db->query($sql3);

            $sql3 = "ALTER TABLE `grup_kelas` ADD CONSTRAINT `fk_grupkelas_kelas` FOREIGN KEY (`id_kelas`) REFERENCES `kelas`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;";
            $this->db->query($sql3);
        }

    }

    public function create_Post(){
        $sql = "SHOW TABLES LIKE 'post'";
        $exist = $this->db->query($sql);
        if($exist->num_rows() == 0){
            $sql2 ="CREATE TABLE `post` ( `id` INT NOT NULL AUTO_INCREMENT , `deskripsi` TEXT NOT NULL , `foto` BLOB NULL , `created_at` DATE NOT NULL , `id_user` INT NULL , `id_grup` INT NULL , PRIMARY KEY (`id`), INDEX (`id_grup`), INDEX (`id_user`)) ENGINE = InnoDB;";
            $this->db->query($sql2);

            $sql3 = "ALTER TABLE `post` ADD CONSTRAINT `fk_post_user` FOREIGN KEY (`id_user`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;";
            $this->db->query($sql3);

            $sql3 = "ALTER TABLE `post` ADD CONSTRAINT `fk_post_grup` FOREIGN KEY (`id_grup`) REFERENCES `grup`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;";
            $this->db->query($sql3);
        }
    }

    public function create_Komentar(){
        $sql = "SHOW TABLES LIKE 'komentar'";
        $exist = $this->db->query($sql);
        if($exist->num_rows() == 0){
           $sql2 = "CREATE TABLE `komentar` ( `id` INT NOT NULL AUTO_INCREMENT , `deskripsi` TEXT NOT NULL , `created_at` DATETIME NOT NULL , `id_user` INT NULL , `id_post` INT NULL , PRIMARY KEY (`id`), INDEX (`id_post`), INDEX (`id_user`)) ENGINE = InnoDB;";
            $this->db->query($sql2);

            $sql3 = "ALTER TABLE `komentar` ADD CONSTRAINT `fk_komentar_user` FOREIGN KEY (`id_user`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;";
            $this->db->query($sql3);

            $sql3 = "ALTER TABLE `komentar` ADD CONSTRAINT `fk_komentar_post` FOREIGN KEY (`id_post`) REFERENCES `post`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;";
            $this->db->query($sql3);
        }
    }
}