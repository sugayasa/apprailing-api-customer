<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableTFeed extends Migration
{
    public function up()
    {
        $this->db->query("
            CREATE TABLE `t_feed` (
              `IDFEED` int NOT NULL AUTO_INCREMENT,
              `URLFEED` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
              `JUDUL` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
              `DESKRIPSI` text COLLATE utf8mb4_unicode_ci NOT NULL,
              `TOTALSUKA` int NOT NULL,
              `TOTALSIMPAN` int NOT NULL,
              `INPUTUSER` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
              `INPUTTANGGALWAKTU` datetime NOT NULL,
              PRIMARY KEY (`IDFEED`),
              UNIQUE KEY `URLFEED` (`URLFEED`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
    }

    public function down()
    {
        $this->db->query("DROP TABLE IF EXISTS `t_feed`");
    }
}
