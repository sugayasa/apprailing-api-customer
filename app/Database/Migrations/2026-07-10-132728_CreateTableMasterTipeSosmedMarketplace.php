<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableMasterTipeSosmedMarketplace extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'IDTIPESOSMEDMARKETPLACE' => [
                'type'           => 'INT',
                'auto_increment' => true,
            ],
            'NAMATIPE' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => false,
                'default'    => '',
            ],
            'FILEICON' => [
                'type'       => 'VARCHAR',
                'constraint' => 25,
                'null'       => false,
                'default'    => 'icondefault.png',
            ],
            'URUTAN' => [
                'type'       => 'SMALLINT',
                'null'       => false,
                'default'    => 99,
            ],
            'STATUS' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => false,
                'default'    => -1,
            ],
        ]);
        $this->forge->addKey('IDTIPESOSMEDMARKETPLACE', true);
        $this->forge->addUniqueKey('NAMATIPE');
        $this->forge->createTable('m_tipesosmedmarketplace');
        $this->db->query('ALTER TABLE m_tipesosmedmarketplace AUTO_INCREMENT = 200');
    }

    public function down()
    {
        $this->forge->dropTable('m_tipesosmedmarketplace');
    }
}
