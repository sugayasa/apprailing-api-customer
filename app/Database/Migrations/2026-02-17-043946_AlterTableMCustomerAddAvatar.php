<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableMCustomerAddAvatar extends Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE `m_customer` ADD `AVATAR` VARCHAR(100) NOT NULL DEFAULT 'default.jpg' AFTER `NOMORHP`");
    }

    public function down()
    {
        $this->db->query("ALTER TABLE `m_customer` DROP COLUMN `AVATAR`");
    }
}
