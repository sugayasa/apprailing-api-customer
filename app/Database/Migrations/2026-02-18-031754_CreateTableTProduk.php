<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableTProduk extends Migration
{
    public function up()
    {
        $this->db->query("
            CREATE TABLE `t_produk` (
              `IDPRODUK` int NOT NULL AUTO_INCREMENT,
              `IDBARANG` int NOT NULL,
              `IDMERK` int NOT NULL,
              `IDKATEGORI` int NOT NULL,
              `NAMAPRODUK` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
              `DESKRIPSI` text COLLATE utf8mb4_unicode_ci NOT NULL,
              `ARRIMAGE` json NOT NULL,
              `HARGAJUAL` int NOT NULL,
              `TOTALTERJUAL` int NOT NULL,
              `INPUTUSER` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
              `INPUTTANGGALWAKTU` datetime NOT NULL,
              `STATUS` tinyint NOT NULL DEFAULT '1' COMMENT '1:AKTIF, -1:NON AKTIF',
              PRIMARY KEY (`IDPRODUK`),
              UNIQUE KEY `NAMAPRODUK` (`NAMAPRODUK`),
              KEY `IDBARANG` (`IDBARANG`,`IDMERK`,`IDKATEGORI`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
    }

    public function down()
    {
        $this->db->query("DROP TABLE IF EXISTS `t_produk`");
    }
}
