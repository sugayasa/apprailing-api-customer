<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableTTransaksiBarang extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'IDTRANSAKSIBARANG' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => false,
                'auto_increment' => true,
            ],
            'IDTRANSAKSIREKAP' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'IDPRODUK' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'KETERANGAN' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'JUMLAH' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'NOMINALSATUAN' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'NOMINALTOTAL' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
        ]);

        $this->forge->addKey('IDTRANSAKSIBARANG', true);
        $this->forge->addUniqueKey([
            'IDTRANSAKSIREKAP',
            'IDPRODUK'
        ]);

        $this->forge->createTable('t_transaksibarang', true, [
            'ENGINE'         => 'InnoDB',
            'AUTO_INCREMENT' => '10000',
            'CHARSET'        => 'utf8mb4',
            'COLLATE'        => 'utf8mb4_unicode_ci',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('t_transaksibarang');
    }
}
