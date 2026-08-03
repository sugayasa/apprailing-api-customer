<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableMasterTipeKonten extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'IDTIPEKONTEN'  =>  [
                'type'          =>  'INT',
                'auto_increment'=>  true,
            ],
            'NAMATIPE'      =>  [
                'type'      =>  'VARCHAR',
                'constraint'=>  25,
                'default'   =>  '',
            ],
        ]);
        $this->forge->addKey('IDTIPEKONTEN', true);
        $this->forge->addUniqueKey('NAMATIPE');
        $this->forge->createTable('m_tipekonten');
        $this->db->query('ALTER TABLE m_tipekonten AUTO_INCREMENT = 900');
    }

    public function down()
    {
        $this->forge->dropTable('m_tipekonten');
    }
}
