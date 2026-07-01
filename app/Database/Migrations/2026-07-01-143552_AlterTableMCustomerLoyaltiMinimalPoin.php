<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableMCustomerLoyaltiMinimalPoin extends Migration
{
    public function up()
    {
        $this->forge->addColumn('m_customerloyalti', [
            'MINIMALPOIN' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'after'      => 'DESKRIPSI',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('m_customerloyalti', 'MINIMALPOIN');
    }
}
