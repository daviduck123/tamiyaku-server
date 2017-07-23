<?php
class FirstDatabase_Model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    public function create_tables(){
       
        $this->create_Kelas();
        $this->create_Kota();
        $this->create_Kategori();
        $this->create_Users();
        $this->create_UsersKelas();
        $this->create_Grup();
        $this->create_UsersGrup();
        $this->create_Post();
        $this->create_Event();
        $this->create_JualBeli();
        $this->create_Komentar();
        $this->create_Notifikasi();
    }

    public function create_Kelas(){
        $sql = "SHOW TABLES LIKE 'kelas'";
        $exist = $this->db->query($sql);
        if($exist->num_rows() == 0){
             $sql2="CREATE TABLE `kelas` ( `id` INT NOT NULL AUTO_INCREMENT , `nama` VARCHAR(255) NOT NULL , `deskripsi` TEXT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;";
             $this->db->query($sql2);


             $sql3 ="INSERT INTO `kelas` (`id`,`nama`, `deskripsi`) VALUES(?,?,?)";
             $this->db->query($sql3, array(1,"STB","Standard Tamiya Box adalah sebuah kelas dimana mobil yang digunakan adalah murni dari box pembelian, tanpa adanya modifikasi berlebihan pada bagian mobil maupun sparepart."));

             $sql3 ="INSERT INTO `kelas` (`id`,`nama`, `deskripsi`) VALUES(?,?,?)";
             $this->db->query($sql3, array(2,"STO","Standard Tamiya Original adalah sebuah kelas dimana mobil dan sparepart yang digunakan harus bermerk \"Tamiya\". Mobil dapat dimodifikasi sesuai regulasi yang berlaku. Terdiri 
                 dari STO 100, STO 75, dan STO 50."));

             $sql3 ="INSERT INTO `kelas` (`id`,`nama`, `deskripsi`) VALUES(?,?,?)";
             $this->db->query($sql3, array(3,"Speed","Speed adalah sebuah mobil Mini4WD yang dimodifikasi sedemikian rupa untuk mendapatkan kecepatan yang maksimal. Kelas ini mengakomodasi kelas Speed, Sloop, Drag, dan Nascar."));
        }
    }

    public function create_Kota(){
        $sql = "SHOW TABLES LIKE 'kota'";
        $exist = $this->db->query($sql);
       if($exist->num_rows() == 0){
            $sql2="CREATE TABLE `kota` (`id` INT NOT NULL AUTO_INCREMENT , `nama` VARCHAR(255) NOT NULL , `created_at` DATETIME NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;"; 
            $this->db->query($sql2);

            $kotas = ["Ambon","Balikpapan","Banda Aceh","Bandar Lampung","Bandung","Banjar","Banjarbaru","Banjarmasin","Batam","Batu","Bau-Bau","Bekasi","Bengkulu","Bima","Binjai","Bitung","Blitar","Bogor","Bontang","Bukittinggi","Cilegon","Cimahi","Cirebon","Denpasar","Depok","Dumai","Gorontalo","Jambi","Jayapura","Kediri","Kendari","Jakarta Barat","Jakarta Pusat","Jakarta Selatan","Jakarta Timur","Jakarta Utara","Kotamobagu","Kupang","Langsa","Lhokseumawe","Lubuklinggau","Madiun","Magelang","Makassar","Malang","Manado","Mataram","Medan","Metro","Meulaboh","Mojokerto","Padang","Padang Sidempuan","Padangpanjang","Pagaralam","Palangkaraya","Palembang","Palopo","Palu","Pangkalpinang","Parepare","Pariaman","Pasuruan","Payakumbuh","Pekalongan","Pekanbaru","Pematangsiantar","Pontianak","Prabumulih","Probolinggo","Purwokerto","Sabang","Salatiga","Samarinda","Sawahlunto","Semarang","Serang","Sibolga","Singkawang","Solok","Sorong","Subulussalam","Sukabumi","Sungai Penuh","Surabaya","Surakarta","Tangerang","Tangerang Selatan","Tanjungbalai","Tanjungpinang","Tarakan","Tasikmalaya","Tebingtinggi","Tegal","Ternate","Tidore Kepulauan","Tomohon","Tual","Yogyakarta"];

            foreach ($kotas as $kota) {
                $sql3 = "INSERT INTO `kota` (`nama`, `created_at`) VALUES (?, NOW());";
                $this->db->query($sql3, array($kota));
            }
        }
    }

    public function create_Kategori(){
        $sql = "SHOW TABLES LIKE 'kategori'";
        $exist = $this->db->query($sql);
       if($exist->num_rows() == 0){
            $sql2="CREATE TABLE `kategori` (`id` INT NOT NULL AUTO_INCREMENT , `nama` VARCHAR(255) NOT NULL , `created_at` DATETIME NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;"; 
            $this->db->query($sql2);

            $sql3 = "INSERT INTO `kategori` (`nama`, `created_at`) VALUES (?, NOW());";
            $this->db->query($sql3, array("Ban / Wheel"));
            $sql3 = "INSERT INTO `kategori` (`nama`, `created_at`) VALUES (?, NOW());";
            $this->db->query($sql3, array("Baterai"));
            $sql3 = "INSERT INTO `kategori` (`nama`, `created_at`) VALUES (?, NOW());";
            $this->db->query($sql3, array("Chassis"));
            $sql3 = "INSERT INTO `kategori` (`nama`, `created_at`) VALUES (?, NOW());";
            $this->db->query($sql3, array("Dinamo"));
            $sql3 = "INSERT INTO `kategori` (`nama`, `created_at`) VALUES (?, NOW());";
            $this->db->query($sql3, array("Plate"));
            $sql3 = "INSERT INTO `kategori` (`nama`, `created_at`) VALUES (?, NOW());";
            $this->db->query($sql3, array("Roller"));
            $sql3 = "INSERT INTO `kategori` (`nama`, `created_at`) VALUES (?, NOW());";
            $this->db->query($sql3, array("Tamiya"));
            $sql3 = "INSERT INTO `kategori` (`nama`, `created_at`) VALUES (?, NOW());";
            $this->db->query($sql3, array("Other"));
        }
    }

    public function create_Users(){
        $sql = "SHOW TABLES LIKE 'users'";
        $exist = $this->db->query($sql);
        if($exist->num_rows() == 0){
            $sql2="CREATE TABLE `users` ( `id` INT NOT NULL AUTO_INCREMENT , `email` VARCHAR(255) NOT NULL , `nama` VARCHAR(255) NOT NULL ,`password` VARCHAR(255) NOT NULL , `jenis_kelamin` ENUM('Laki-laki','Perempuan') NOT NULL , `foto` BLOB NULL , `uniqueId` VARCHAR(12) NOT NULL , `verified` ENUM('1','0') NOT NULL , `id_kota` INT NULL , `created_at` DATETIME NOT NULL , PRIMARY KEY (`id`), UNIQUE (`email`), INDEX(`id_kota`)) ENGINE = InnoDB;";
            $this->db->query($sql2);

            $sql3="ALTER TABLE `users` ADD CONSTRAINT `fk_users_kota` FOREIGN KEY (`id_kota`) REFERENCES `kota`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;";
            $this->db->query($sql3);

            //Create User - Teman relationship
            $sql4 = "CREATE TABLE `users_teman` ( `id_user` INT NOT NULL , `id_teman` INT NOT NULL , PRIMARY KEY (`id_user`, `id_teman`)) ENGINE = InnoDB;";
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
            $sql2 = "CREATE TABLE `grup` ( `id` INT NOT NULL AUTO_INCREMENT , `nama` VARCHAR(255) NOT NULL ,  `foto` BLOB NULL , `lat` FLOAT NOT NULL , `lng` FLOAT NOT NULL , `lokasi` TEXT NULL , `created_at` DATETIME NOT NULL , `id_user` INT NOT NULL , `id_kelas` INT NOT NULL , `id_kota` INT NOT NULL , PRIMARY KEY (`id`), INDEX (`id_user`), INDEX (`id_kelas`), INDEX (`id_kota`)) ENGINE = InnoDB;";
            $this->db->query($sql2);

            $sql3 = "ALTER TABLE `grup` ADD CONSTRAINT `fk_grup_user` FOREIGN KEY (`id_user`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;";
            $this->db->query($sql3);

            $sql3 = "ALTER TABLE `grup` ADD CONSTRAINT `fk_grup_kelas` FOREIGN KEY (`id_kelas`) REFERENCES `kelas`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;";
            $this->db->query($sql3);

            $sql3 = "ALTER TABLE `grup` ADD CONSTRAINT `fk_grup_kota` FOREIGN KEY (`id_kota`) REFERENCES `kota`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;";
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

    public function create_Post(){
        $sql = "SHOW TABLES LIKE 'post'";
        $exist = $this->db->query($sql);
        if($exist->num_rows() == 0){
            $sql2 ="CREATE TABLE `post` ( `id` INT NOT NULL AUTO_INCREMENT , `deskripsi` TEXT NOT NULL , `foto` BLOB NULL , `created_at` DATETIME NOT NULL , `id_user` INT NULL , `id_grup` INT NULL , PRIMARY KEY (`id`), INDEX (`id_grup`), INDEX (`id_user`)) ENGINE = InnoDB;";
            $this->db->query($sql2);

            $sql3 = "ALTER TABLE `post` ADD CONSTRAINT `fk_post_user` FOREIGN KEY (`id_user`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;";
            $this->db->query($sql3);

            $sql3 = "ALTER TABLE `post` ADD CONSTRAINT `fk_post_grup` FOREIGN KEY (`id_grup`) REFERENCES `grup`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;";
            $this->db->query($sql3);
        }
    }

    public function create_Event(){
        $sql = "SHOW TABLES LIKE 'event'";
        $exist = $this->db->query($sql);
        if($exist->num_rows() == 0){
           $sql2 = "CREATE TABLE `event` ( `id` INT NOT NULL AUTO_INCREMENT , `nama` VARCHAR(255) NOT NULL , `tanggal` DATE NOT NULL , `tempat` VARCHAR(255) NOT NULL , `hadiah1` INT NOT NULL , `hadiah2` INT NULL , `hadiah3` INT NULL , `harga_tiket` INT NOT NULL , `deskripsi` TEXT NOT NULL , `foto` BLOB NULL , `id_user` INT NOT NULL , `id_kota` INT NOT NULL , `id_kelas` INT NOT NULL , `created_at` DATETIME NOT NULL , PRIMARY KEY (`id`), INDEX (`id_user`), INDEX (`id_kelas`), INDEX (`id_kota`)) ENGINE = InnoDB;";
            $this->db->query($sql2);

            $sql3 = "ALTER TABLE `event` ADD CONSTRAINT `fk_event_user` FOREIGN KEY (`id_user`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;";
            $this->db->query($sql3);

            $sql3 = "ALTER TABLE `event` ADD CONSTRAINT `fk_event_kota` FOREIGN KEY (`id_kota`) REFERENCES `kota`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;";
            $this->db->query($sql3);

            $sql3 = "ALTER TABLE `event` ADD CONSTRAINT `fk_event_kelas` FOREIGN KEY (`id_kelas`) REFERENCES `kelas`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;";
            $this->db->query($sql3);
        }
    }

    public function create_JualBeli(){
        $sql = "SHOW TABLES LIKE 'jual_beli'";
        $exist = $this->db->query($sql);
        if($exist->num_rows() == 0){
           $sql2 = "CREATE TABLE `jual_beli` ( `id` INT NOT NULL AUTO_INCREMENT , `nama` VARCHAR(255) NOT NULL , `harga` INT(20) NOT NULL , `foto` BLOB NOT NULL , `deskripsi` TEXT NOT NULL , `created_at` DATETIME NOT NULL, `id_user` INT NOT NULL, `id_kelas` INT NOT NULL, `id_kategori` INT NOT NULL, `id_kota` INT NOT NULL , PRIMARY KEY (`id`), INDEX (`id_user`), INDEX (`id_kota`), INDEX (`id_kategori`),  INDEX (`id_kelas`)) ENGINE = InnoDB;";
            $this->db->query($sql2);

            $sql3 = "ALTER TABLE `jual_beli` ADD CONSTRAINT `fk_jualbeli_user` FOREIGN KEY (`id_user`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;";
            $this->db->query($sql3);

            $sql3 = "ALTER TABLE `jual_beli` ADD CONSTRAINT `fk_jualbeli_kota` FOREIGN KEY (`id_kota`) REFERENCES `kota`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;";
            $this->db->query($sql3);

            $sql3 = "ALTER TABLE `jual_beli` ADD CONSTRAINT `fk_jualbeli_kelas` FOREIGN KEY (`id_kelas`) REFERENCES `kelas`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;";
            $this->db->query($sql3);

            $sql3 = "ALTER TABLE `jual_beli` ADD CONSTRAINT `fk_jualbeli_kategori` FOREIGN KEY (`id_kategori`) REFERENCES `kategori`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;";
            $this->db->query($sql3);
        }
    }

    public function create_Komentar(){
        $sql = "SHOW TABLES LIKE 'komentar'";
        $exist = $this->db->query($sql);
        if($exist->num_rows() == 0){
           $sql2 = "CREATE TABLE `komentar` ( `id` INT NOT NULL AUTO_INCREMENT ,  `deskripsi` TEXT NOT NULL ,  `type` ENUM('1','2','3') NOT NULL COMMENT '1 = user and grup; 2 = event; 3 = jual beli' ,  `created_at` DATETIME NOT NULL ,  `id_user` INT NOT NULL ,  `id_post` INT NULL COMMENT 'If Post Status User and Grup komentar' ,  `id_event` INT NULL ,  `id_jualbeli` INT NULL ,  PRIMARY KEY  (`id`), INDEX  (`id_jualbeli`),    INDEX  (`id_event`),    INDEX  (`id_post`),    INDEX  (`id_user`)) ENGINE = InnoDB;";
            $this->db->query($sql2);

            $sql3 = "ALTER TABLE `komentar` ADD CONSTRAINT `fk_komentar_user` FOREIGN KEY (`id_user`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;";
            $this->db->query($sql3);

            $sql3 = "ALTER TABLE `komentar` ADD CONSTRAINT `fk_komentar_post` FOREIGN KEY (`id_post`) REFERENCES `post`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;";
            $this->db->query($sql3);

            $sql3 = "ALTER TABLE `komentar` ADD CONSTRAINT `fk_komentar_event` FOREIGN KEY (`id_event`) REFERENCES `event`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;";
            $this->db->query($sql3);

            $sql3 = "ALTER TABLE `komentar` ADD CONSTRAINT `fk_komentar_jualbeli` FOREIGN KEY (`id_jualbeli`) REFERENCES `jual_beli`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;";
            $this->db->query($sql3);
        }
    }

    public function create_Notifikasi(){
        $sql = "SHOW TABLES LIKE 'notifikasi'";
        $exist = $this->db->query($sql);
        if($exist->num_rows() == 0){
           $sql2 = "CREATE TABLE `notifikasi` ( `id` INT NOT NULL AUTO_INCREMENT , `deskripsi` TEXT NOT NULL ,`url` TEXT NULL , `created_at` DATETIME NOT NULL , `id_user` INT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;";
            $this->db->query($sql2);

            $sql3 = "ALTER TABLE `notifikasi` ADD CONSTRAINT `fk_notifkiasi_user` FOREIGN KEY (`id_user`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;";
            $this->db->query($sql3);
        }
    }
}