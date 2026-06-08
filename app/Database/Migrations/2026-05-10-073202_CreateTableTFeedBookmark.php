<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableTFeedBookmark extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'IDFEEDBOOKMARK' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => false,
                'auto_increment' => true,
            ],
            'IDCUSTOMER' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => false,
            ],
            'IDFEED' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => false,
            ],
            'INPUTTANGGALWAKTU' => [
                'type' => 'DATETIME',
            ],
        ]);

        $this->forge->addKey('IDFEEDBOOKMARK', true);
        $this->forge->addUniqueKey(['IDCUSTOMER', 'IDFEED']);

        $this->forge->createTable('t_feedbookmark', true, [
            'ENGINE'         => 'InnoDB',
            'AUTO_INCREMENT' => '10000',
            'CHARSET'        => 'utf8mb4',
            'COLLATE'        => 'utf8mb4_unicode_ci',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('t_feedbookmark');
    }
}
