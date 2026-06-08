<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableMSlideBoarding extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'IDSLIDEBOARDING' => [
                'type' => 'INT',
                'auto_increment' => true,
            ],
            'KONTEN' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => false,
                'default' => '',
            ],
            'IMAGE' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
                'default' => 'defaultBoarding.png',
            ],
            'URUTAN' => [
                'type' => 'TINYINT',
                'null' => false,
                'default' => 99,
            ],
            'INPUTUSER' => [
                'type' => 'CHAR',
                'constraint' => 50,
                'null' => false,
                'default' => '',
            ],
            'INPUTTANGGALWAKTU' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'STATUS' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'null' => false,
            ],
        ]);
        
        $this->forge->addKey('IDSLIDEBOARDING', true);
        $this->forge->createTable('t_slideboarding');
    }

    public function down()
    {
        $this->forge->dropTable('t_slideboarding');
    }
}
