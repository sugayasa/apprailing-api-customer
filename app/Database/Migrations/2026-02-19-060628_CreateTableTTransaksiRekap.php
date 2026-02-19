<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableTTransaksiRekap extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'IDTRANSAKSIREKAP' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => false,
                'auto_increment' => true,
            ],
            'IDREGIONAL' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'IDCUSTOMER' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'IDCUSTOMERALAMAT' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'IDKANALPEMBAYARAN' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'IDEKSPEDISI' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'IDSTATUSTRANSAKSI' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'NOMORTRANSAKSI' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
            ],
            'NOMORRESIEKSPEDISI' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
            ],
            'ALAMATNAMA' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
            ],
            'ALAMATKIRIM' => [
                'type'       => 'TEXT'
            ],
            'PENERIMANAMA' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'PENERIMANOMORTELEPON' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
            ],
            'CATATAN' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'TOTALBARANG' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'TOTALNOMINALBARANG' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'TOTALNOMINALONGKIR' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'TOTALNOMINALDISKON' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'TOTALNOMINALBAYAR' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'INPUTTANGGALWAKTU' => [
                'type' => 'DATETIME',
            ],
        ]);

        $this->forge->addKey('IDTRANSAKSIREKAP', true);
        $this->forge->addUniqueKey('NOMORTRANSAKSI');
        $this->forge->addUniqueKey('NOMORRESIEKSPEDISI');
        $this->forge->addKey([
            'IDREGIONAL',
            'IDCUSTOMER',
            'IDCUSTOMERALAMAT',
            'IDKANALPEMBAYARAN',
            'IDEKSPEDISI',
            'IDSTATUSTRANSAKSI'
        ], false, false, 'INDEXTRANSAKSIREKAP');

        $this->forge->createTable('t_transaksirekap', true, [
            'ENGINE'         => 'InnoDB',
            'AUTO_INCREMENT' => '10000',
            'CHARSET'        => 'utf8mb4',
            'COLLATE'        => 'utf8mb4_unicode_ci',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('t_transaksirekap');
    }
}
