<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterMCustomerRoyalti extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('m_customerroyalti', [
            'IDCUSTOMERROYALTI' => [
                'NAME'          => 'IDCUSTOMERLOYALTI',
                'type'          => 'INT',
                'constraint'    => 11,
                'unsigned'      => true,
                'auto_increment'=> true,
            ],
            'ROYALTITIER'   => [
                'NAME'      => 'LOYALTITIER',
                'type'      => 'VARCHAR',
                'constraint'=> 30,
            ],
        ]);

        $this->forge->renameTable('m_customerroyalti', 'm_customerloyalti');

        $this->forge->modifyColumn('m_customerloyalti', [
            'ICON' => [
                'NAME'       => 'ICONFILE',
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
        ]);

        $this->forge->addColumn('m_customerloyalti', [
            'CARDFILE' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
                'after'      => 'ICONFILE',
            ],
        ]);

        $this->forge->modifyColumn('m_customer', [
            'IDCUSTOMERROYALTI' => [
                'NAME'          => 'IDCUSTOMERLOYALTI',
                'type'          => 'INT',
                'constraint'    => 11,
                'null'          => true,
                'default'       => 301,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->modifyColumn('m_customer', [
            'IDCUSTOMERLOYALTI' => [
                'NAME'    => 'IDCUSTOMERROYALTI',
                'type'    => 'INT',
                'constraint' => 11,
                'null'    => true,
                'default' => 301,
            ],
        ]);

        $this->forge->dropColumn('m_customerloyalti', 'CARDFILE');

        $this->forge->modifyColumn('m_customerloyalti', [
            'ICONFILE' => [
                'NAME'       => 'ICON',
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
        ]);

        $this->forge->renameTable('m_customerloyalti', 'm_customerroyalti');

        $this->forge->modifyColumn('m_customerroyalti', [
            'IDCUSTOMERLOYALTI' => [
                'NAME'          => 'IDCUSTOMERROYALTI',
                'type'          => 'INT',
                'constraint'    => 11,
                'unsigned'      => true,
                'auto_increment'=> true,
            ],
            'LOYALTITIER'   => [
                'NAME'      => 'ROYALTITIER',
                'type'      => 'VARCHAR',
                'constraint'=> 30,
            ],
        ]);
    }
}
