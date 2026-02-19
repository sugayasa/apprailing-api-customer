<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableTTransaksiRiwayat extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'IDTRANSAKSIRIWAYAT' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => false,
                'auto_increment' => true,
            ],
            'IDTRANSAKSIREKAP' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'IDSTATUSTRANSAKSI' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'INPUTUSER' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'TANGGALWAKTU' => [
                'type' => 'DATETIME',
            ],
        ]);

        $this->forge->addKey('IDTRANSAKSIRIWAYAT', true);
        $this->forge->addUniqueKey([
            'IDTRANSAKSIREKAP',
            'IDSTATUSTRANSAKSI'
        ]);

        $this->forge->createTable('t_transaksiriwayat', true, [
            'ENGINE'         => 'InnoDB',
            'AUTO_INCREMENT' => '1000',
            'CHARSET'        => 'utf8mb4',
            'COLLATE'        => 'utf8mb4_unicode_ci',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('t_transaksiriwayat');
    }
}
