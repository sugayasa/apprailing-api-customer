<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableMRegional extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'IDREGIONAL' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => false,
                'auto_increment' => true,
            ],
            'NAMAREGIONAL' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'NAMADATABASE' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
        ]);

        $this->forge->addKey('IDREGIONAL', true);
        $this->forge->addUniqueKey('NAMAREGIONAL');
        $this->forge->addUniqueKey('NAMADATABASE');

        $this->forge->createTable('m_regional', true, [
            'ENGINE'         => 'InnoDB',
            'AUTO_INCREMENT' => '104',
            'CHARSET'        => 'utf8mb4',
            'COLLATE'        => 'utf8mb4_unicode_ci',
        ]);

        $data = [
            ['IDREGIONAL' => 100, 'NAMAREGIONAL' => 'Surabaya', 'NAMADATABASE' => 'apprailing_cab_surabaya'],
            ['IDREGIONAL' => 101, 'NAMAREGIONAL' => 'Jakarta', 'NAMADATABASE' => 'apprailing_cab_jakarta'],
            ['IDREGIONAL' => 102, 'NAMAREGIONAL' => 'Bali', 'NAMADATABASE' => 'apprailing_cab_bali'],
            ['IDREGIONAL' => 103, 'NAMAREGIONAL' => 'Semarang', 'NAMADATABASE' => 'apprailing_cab_semarang']
        ];

        $this->db->table('m_regional')->insertBatch($data);
    }

    public function down()
    {
        $this->forge->dropTable('m_regional');
    }
}
