<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableMKategori extends Migration
{
    public function up()
    {
        $this->db->query("
            CREATE TABLE `m_kategori` (
              `IDKATEGORI` int NOT NULL AUTO_INCREMENT,
              `NAMAKATEGORI` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
              `DESKRIPSI` text COLLATE utf8mb4_unicode_ci NOT NULL,
              `STATUS` tinyint NOT NULL DEFAULT '1' COMMENT '1:AKTIF, -1:NON AKTIF',
              PRIMARY KEY (`IDKATEGORI`),
              UNIQUE KEY `NAMAKATEGORI` (`NAMAKATEGORI`)
            ) ENGINE=InnoDB AUTO_INCREMENT=200 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
    }

    public function down()
    {
        $this->db->query("DROP TABLE IF EXISTS `m_kategori`");
    }
}
