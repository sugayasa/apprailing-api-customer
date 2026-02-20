<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableTTransaksiRekapSetNullResiEkspedisi extends Migration
{
    public function up()
    {
        $fields = [
            'NOMORRESIEKSPEDISI' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
                'null'       => true,
                'default'    => null,
            ],
        ];

        $this->forge->modifyColumn('t_transaksirekap', $fields);
    }

    public function down()
    {
        $fields = [
            'NOMORRESIEKSPEDISI' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
                'null'       => false,
            ],
        ];

        $this->forge->modifyColumn('t_transaksirekap', $fields);
    }
}
