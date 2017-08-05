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
            $sql2="CREATE TABLE `kota` (`id` INT NOT NULL AUTO_INCREMENT , `nama` VARCHAR(255) NOT NULL , `lat` FLOAT NOT NULL , `lng` FLOAT NOT NULL , `created_at` DATETIME NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;"; 
            $this->db->query($sql2);

            $kota = ["Ambon","Balikpapan","Banda Aceh","Bandar Lampung","Bandung","Banjar","Banjarbaru","Banjarmasin","Batam","Batu","Bau-Bau","Bekasi","Bengkulu","Bima","Binjai","Bitung","Blitar","Bogor","Bontang","Bukittinggi","Cilegon","Cimahi","Cirebon","Denpasar","Depok","Dumai","Gorontalo","Jambi","Jayapura","Kediri","Kendari","Jakarta Barat","Jakarta Pusat","Jakarta Selatan","Jakarta Timur","Jakarta Utara","Kotamobagu","Kupang","Langsa","Lhokseumawe","Lubuklinggau","Madiun","Magelang","Makassar","Malang","Manado","Mataram","Medan","Metro","Meulaboh","Mojokerto","Padang","Padang Sidempuan","Padangpanjang","Pagaralam","Palangkaraya","Palembang","Palopo","Palu","Pangkalpinang","Parepare","Pariaman","Pasuruan","Payakumbuh","Pekalongan","Pekanbaru","Pematangsiantar","Pontianak","Prabumulih","Probolinggo","Purwokerto","Sabang","Salatiga","Samarinda","Sawahlunto","Semarang","Serang","Sibolga","Singkawang","Solok","Sorong","Subulussalam","Sukabumi","Sungai Penuh","Surabaya","Surakarta","Tangerang","Tangerang Selatan","Tanjungbalai","Tanjungpinang","Tarakan","Tasikmalaya","Tebingtinggi","Tegal","Ternate","Tidore Kepulauan","Tomohon","Tual","Yogyakarta"];

            $lat = [-3.69543,-1.26916,5.553584,-5.45,-6.905977,-7.374585,-3.457242,-3.316694,1.045626,-7.8671,-5.444868,-6.241586,-3.788892,-8.463992,3.598401,-2.88818,-8.101379,-6.595038,0.133333,-0.305164,-6.003474,-6.899541,-6.737246,-8.65,-6.385589,1.694394,0.556174,-1.609972,-2.593946,-7.82284,-3.972201,-6.1352,-6.185213,-6.300641,-6.264451,-7.300831,0.724404,-10.178757,4.472729,5.180434,-3.2811,-7.630060,-7.595599,-5.147665,-7.983908,1.47483,-8.577518,3.597031,-5.118177,4.143765,-7.653204,-0.914518,1.370175 ,-0.466594,-4.042195,-2.215877,-2.990934,-3.002467,-0.8917,-2.133333,-4.009369,-0.626439,-7.646841,-0.230697,-6.888701,0.533505,2.970042,0,-3.422297,-7.756928,-7.431391,5.892589,-7.331259,-0.502106,-0.597615,-6.966667,-6.12,1.749987,0.901359,-7.00992,-0.877459,2.640423,-6.9237,-2.063621,-7.250445,-7.550676,-6.178306,-6.320138,0.993311,0.917748,3.327394,-7.319563,3.327381,-6.879704,0.773502,0.674844,1.322934,-5.620837,-7.797068];

            $lng = [128.18141,116.825264,95.317276,105.26667,107.613144,108.558189,114.810318,114.590111,104.030457,112.523903,122.645520,106.992416,102.266579,118.745690,98.489166,107.926941,112.147751,106.816635,117.5,100.369319,106.012063,107.533867,108.550659,115.216667,106.830711,101.445007,123.058548,103.607254,140.668665,112.011864,122.5149,106.813301,106.840928,106.814095,106.895859,112.76825,124.320422,123.597603,97.974106,97.134069,102.910988,111.531827,110.280243,119.432732,112.621391,124.842079,116.104411,98.678513,105.308096,96.127472,112.558868,100.459526,99.271142,100.397760,103.230901,113.908874,104.756554,120.200842,119.8707,106.116669,119.626817,100.117958,112.897799,100.633095,109.668289,101.447403,99.068169,109.333336,104.244064,113.211502,109.247833,95.324773,110.508974,117.153709,100.724345,110.416664,106.150276,98.776703,109.000148,107.745346,131.256839,98.011901,106.928726,101.399896,112.768845,110.828316,106.631889,106.665596,103.432170,104.469319,117.580274,108.202972,99.164769,109.125595,127.372127,127.404831,124.840508,132.306417,110.370529];

            for($i = 0 ; $i < count($kota); $i++) {
                $sql3 = "INSERT INTO `kota` (`nama`, `lat`, `lng`, `created_at`) VALUES (?, ?, ?, NOW());";
                $this->db->query($sql3, array($kota[$i], $lat[$i], $lng[$i]));
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
            $sql2="CREATE TABLE `users` ( `id` INT NOT NULL AUTO_INCREMENT , `email` VARCHAR(255) NOT NULL , `nama` VARCHAR(255) NOT NULL ,`password` VARCHAR(255) NOT NULL , `jenis_kelamin` ENUM('Laki-laki','Perempuan') NOT NULL , `foto` MEDIUMBLOB NULL , `uniqueId` VARCHAR(6) NOT NULL , `verified` ENUM('1','0') NOT NULL , `id_kota` INT NULL , `created_at` DATETIME NOT NULL , PRIMARY KEY (`id`), UNIQUE (`email`), INDEX(`id_kota`)) ENGINE = InnoDB;";
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
            $sql2 = "CREATE TABLE `grup` ( `id` INT NOT NULL AUTO_INCREMENT , `nama` VARCHAR(255) NOT NULL ,  `foto` MEDIUMBLOB NULL , `lat` FLOAT NOT NULL , `lng` FLOAT NOT NULL , `lokasi` TEXT NULL , `created_at` DATETIME NOT NULL , `id_user` INT NOT NULL , `id_kelas` INT NOT NULL , `id_kota` INT NOT NULL , PRIMARY KEY (`id`), INDEX (`id_user`), INDEX (`id_kelas`), INDEX (`id_kota`)) ENGINE = InnoDB;";
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
            $sql2 ="CREATE TABLE `post` ( `id` INT NOT NULL AUTO_INCREMENT , `deskripsi` TEXT NOT NULL , `foto` MEDIUMBLOB NULL , `created_at` DATETIME NOT NULL , `id_user` INT NULL , `id_grup` INT NULL , PRIMARY KEY (`id`), INDEX (`id_grup`), INDEX (`id_user`)) ENGINE = InnoDB;";
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
           $sql2 = "CREATE TABLE `event` ( `id` INT NOT NULL AUTO_INCREMENT , `nama` VARCHAR(255) NOT NULL , `tanggal` DATE NOT NULL , `tempat` TEXT NOT NULL , `hadiah1` INT(20) NOT NULL , `hadiah2` INT(20) NULL , `hadiah3` INT(20) NULL , `harga_tiket` INT(20) NOT NULL , `deskripsi` TEXT NOT NULL , `lat` FLOAT NOT NULL , `lng` FLOAT NOT NULL , `foto` MEDIUMBLOB NULL , `id_user` INT NOT NULL , `id_kota` INT NOT NULL , `id_kelas` INT NOT NULL , `created_at` DATETIME NOT NULL , PRIMARY KEY (`id`), INDEX (`id_user`), INDEX (`id_kelas`), INDEX (`id_kota`)) ENGINE = InnoDB;";
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
           $sql2 = "CREATE TABLE `jual_beli` ( `id` INT NOT NULL AUTO_INCREMENT , `nama` VARCHAR(255) NOT NULL , `harga` INT(20) NOT NULL , `foto` MEDIUMBLOB NOT NULL , `deskripsi` TEXT NOT NULL , `created_at` DATETIME NOT NULL, `id_user` INT NOT NULL, `id_kelas` INT NOT NULL, `id_kategori` INT NOT NULL, `id_kota` INT NOT NULL , PRIMARY KEY (`id`), INDEX (`id_user`), INDEX (`id_kota`), INDEX (`id_kategori`),  INDEX (`id_kelas`)) ENGINE = InnoDB;";
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