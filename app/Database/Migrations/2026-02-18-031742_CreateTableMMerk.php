<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableMMerk extends Migration
{
    public function up()
    {
        $this->db->query("
            CREATE TABLE `m_merk` (
              `IDMERK` int NOT NULL AUTO_INCREMENT,
              `NAMAMERK` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
              `LOGO` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
              `STATUS` tinyint NOT NULL DEFAULT '1' COMMENT '1:AKTIF, -1:NON AKTIF',
              PRIMARY KEY (`IDMERK`),
              UNIQUE KEY `NAMAMERK` (`NAMAMERK`)
            ) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
    }

    public function down()
    {
        $this->db->query("DROP TABLE IF EXISTS `m_merk`");
    }
}
