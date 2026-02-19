<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableMEkspedisi extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'IDEKSPEDISI' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => false,
                'auto_increment' => true,
            ],
            'NAMAEKSPEDISI' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'STATUS' => [
                'type'       => 'TINYINT',
                'default'    => 1,
                'comment'    => '1:AKTIF, -1:NON AKTIF',
            ],
        ]);

        $this->forge->addKey('IDEKSPEDISI', true);
        $this->forge->addUniqueKey('NAMAEKSPEDISI');

        $this->forge->createTable('m_ekspedisi', true, [
            'ENGINE'         => 'InnoDB',
            'AUTO_INCREMENT' => '15',
            'CHARSET'        => 'utf8mb4',
            'COLLATE'        => 'utf8mb4_unicode_ci',
        ]);

        $data = [
            ['IDEKSPEDISI' => 10, 'NAMAEKSPEDISI' => 'Kurir Internal', 'STATUS' => 1],
            ['IDEKSPEDISI' => 11, 'NAMAEKSPEDISI' => 'POS', 'STATUS' => -1],
            ['IDEKSPEDISI' => 12, 'NAMAEKSPEDISI' => 'JNE', 'STATUS' => -1],
            ['IDEKSPEDISI' => 13, 'NAMAEKSPEDISI' => 'JNT', 'STATUS' => -1],
            ['IDEKSPEDISI' => 14, 'NAMAEKSPEDISI' => 'SiCepat', 'STATUS' => -1]
        ];

        $this->db->table('m_ekspedisi')->insertBatch($data);
    }

    public function down()
    {
        $this->forge->dropTable('m_ekspedisi');
    }
}
