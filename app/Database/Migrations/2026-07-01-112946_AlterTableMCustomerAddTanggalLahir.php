<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableMCustomerAddTanggalLahir extends Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE `m_customer` ADD `TANGGALLAHIR` DATE NULL DEFAULT (CURDATE()) AFTER `NAMA`");
        $this->db->query("ALTER TABLE `m_customer` ADD `TANGGALDAFTAR` DATE NULL DEFAULT (CURDATE()) AFTER `TANGGALLAHIR`");
    }

    public function down()
    {
        $this->forge->dropColumn('m_customer', 'TANGGALLAHIR');
        $this->forge->dropColumn('m_customer', 'TANGGALDAFTAR');
    }
}