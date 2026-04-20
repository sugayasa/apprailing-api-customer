<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableTTransaksiRekapAddIsColumn extends Migration
{
    public function up()
    {
        $this->forge->addColumn('t_transaksirekap', [
            'ISPEMBAYARANLUNAS' => [
                'type'       => 'TINYINT',
                'default'    => 0,
                'after'      => 'TOTALNOMINALBAYAR',
            ],
            'ISPENGIRIMANDIPROSES' => [
                'type'       => 'TINYINT',
                'default'    => 0,
                'after'      => 'ISPEMBAYARANLUNAS',
            ],
            'ISPESANANSELESAI' => [
                'type'       => 'TINYINT',
                'default'    => 0,
                'after'      => 'ISPENGIRIMANDIPROSES',
            ],
            'ISREFUNDDANA' => [
                'type'       => 'TINYINT',
                'default'    => 0,
                'after'      => 'ISPESANANSELESAI',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('t_transaksirekap', [
            'ISPEMBAYARANLUNAS',
            'ISPENGIRIMANDIPROSES',
            'ISPESANANSELESAI',
            'ISREFUNDDANA',
        ]);
    }
}
