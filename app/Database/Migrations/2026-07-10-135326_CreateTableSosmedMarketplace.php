<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableSosmedMarketplace extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'IDSOSMEDMARKETPLACE' => [
                'type'           => 'INT',
                'auto_increment' => true,
            ],
            'IDTIPESOSMEDMARKETPLACE' => [
                'type'       => 'INT',
                'null'       => false,
                'default'    => 207,
            ],
            'NAMAAKUN' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
                'default'    => 'Rich Plus',
            ],
            'URL' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
                'null'       => false,
                'default'    => 'https://richrailing.com/',
            ],
            'URUTAN' => [
                'type'       => 'SMALLINT',
                'null'       => false,
                'default'    => 99,
            ],
        ]);
        $this->forge->addKey('IDSOSMEDMARKETPLACE', true);
        $this->forge->addUniqueKey(['IDTIPESOSMEDMARKETPLACE', 'NAMAAKUN']);
        $this->forge->createTable('t_sosmedmarketplace');
        $this->db->query('ALTER TABLE t_sosmedmarketplace AUTO_INCREMENT = 2000');
    }

    public function down()
    {
        $this->forge->dropTable('t_sosmedmarketplace');
    }
}
