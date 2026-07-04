<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableReviewCustomer extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'IDREVIEWMARKETING' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'IDCUSTOMER' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'default'    => 0,
            ],
            'NAMAMARKETING' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'default'    => '-',
            ],
            'RATING' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 5,
            ],
            'KOMENTAR' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'default'    => '',
            ],
            'IMAGEMARKETING' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'default.jpg',
            ],
            'TANGGAL' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'TANGGALWAKTU' => [
                'type'    => 'DATETIME',
                'null'    => false,
            ],
        ]);

        $this->forge->addPrimaryKey('IDREVIEWMARKETING');
        $this->forge->addUniqueKey(['IDCUSTOMER', 'NAMAMARKETING', 'TANGGAL']);
        $this->forge->createTable('t_reviewmarketing');

        $this->db->query("ALTER TABLE `t_reviewmarketing` MODIFY `TANGGALWAKTU` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP");
        $this->db->query("ALTER TABLE `t_reviewmarketing` AUTO_INCREMENT = 2000");
    }

    public function down()
    {
        $this->forge->dropTable('t_reviewmarketing');
    }
}