<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableMCustomerAlamat extends Migration
{
    public function up()
    {
        $this->db->query("
            CREATE TABLE `m_customeralamat` (
              `IDCUSTOMERALAMAT` int NOT NULL AUTO_INCREMENT,
              `IDCUSTOMER` int NOT NULL,
              `NAMAALAMAT` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
              `NAMAPENERIMA` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
              `NOMORHPPENERIMA` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
              `ALAMAT` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
              `KODEPOS` mediumint NOT NULL,
              `KELURAHAN` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
              `KECAMATAN` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
              `KOTA` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
              `PROPINSI` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
              `ISALAMATUTAMA` tinyint(1) NOT NULL DEFAULT '1',
              `STATUS` tinyint NOT NULL COMMENT '1:AKTIF, -1:NON AKTIF',
              PRIMARY KEY (`IDCUSTOMERALAMAT`),
              KEY `IDCUSTOMER` (`IDCUSTOMER`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
    }

    public function down()
    {
        $this->db->query("DROP TABLE IF EXISTS `m_customeralamat`");
    }
}
