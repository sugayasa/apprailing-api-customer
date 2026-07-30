<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableMasterKlien extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'IDKLIEN' => [
                'type'           => 'INT',
                'auto_increment' => true,
            ],
            'NAMAKLIEN' => [
                'type'       => 'CHAR',
                'constraint' => 64,
                'default'    => '',
            ],
            'LOGO' => [
                'type'       => 'CHAR',
                'constraint' => 48,
                'default'    => 'default.png',
            ],
            'STATUS' => [
                'type'    => 'BOOLEAN',
                'default' => true,
            ],
        ]);
        $this->forge->addKey('IDKLIEN', true);
        $this->forge->addUniqueKey('NAMAKLIEN');
        $this->forge->createTable('m_klien');
        $this->db->query('ALTER TABLE m_klien AUTO_INCREMENT = 900');
    }

    public function down()
    {
        $this->forge->dropTable('m_klien');
    }
}
