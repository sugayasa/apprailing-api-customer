<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableMKanalPembayaran extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'IDKANALPEMBAYARAN' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => false,
                'auto_increment' => true,
            ],
            'NAMAKANALPEMBAYARAN' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'DESKRIPSI' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'STATUS' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
                'comment'    => '1:AKTIF, -1:NON AKTIF',
            ],
        ]);

        $this->forge->addKey('IDKANALPEMBAYARAN', true);
        $this->forge->addUniqueKey('NAMAKANALPEMBAYARAN');

        $this->forge->createTable('m_kanalpembayaran', true, [
            'ENGINE'         => 'InnoDB',
            'AUTO_INCREMENT' => '11',
            'CHARSET'        => 'utf8mb4',
            'COLLATE'        => 'utf8mb4_unicode_ci',
        ]);

        $data = [
            [
                'IDKANALPEMBAYARAN' => 10,
                'NAMAKANALPEMBAYARAN' => 'Transfer',
                'DESKRIPSI' => 'Transfer ke rekening dengan tambahan nominal unik',
                'STATUS' => 1
            ]
        ];

        $this->db->table('m_kanalpembayaran')->insertBatch($data);
    }

    public function down()
    {
        $this->forge->dropTable('m_kanalpembayaran');
    }
}
