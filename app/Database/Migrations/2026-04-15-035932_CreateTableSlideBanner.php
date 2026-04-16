<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableSlideBanner extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'IDSLIDEBANNER' => [
                'type' => 'INT',
                'auto_increment' => true,
            ],
            'KONTEN' => [
                'type' => 'MEDIUMTEXT',
                'null' => false,
            ],
            'IMAGE' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'INPUTUSER' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'INPUTTANGGALWAKTU' => [
                'type' => 'DATETIME',
            ],
            'STATUS' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
        ]);
        
        $this->forge->addKey('IDSLIDEBANNER', true);
        $this->forge->createTable('t_slidebanner');
    }

    public function down()
    {
        $this->forge->dropTable('t_slidebanner');
    }
}
