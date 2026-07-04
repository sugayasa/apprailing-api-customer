<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableMMerkPDF extends Migration
{
    public function up()
    {
        $fields = [
            'PDFTHUMBNAIL' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'default.jpg',
                'after'      => 'LOGO',
            ],
            'PDFFILE' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'default.pdf',
                'after'      => 'PDFTHUMBNAIL',
            ],
            'STATUSKATALOG' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'comment'    => '//1:MASUK KATALOG, 0:TIDAK MASUK KATALOG',
                'after'      => 'PDFFILE',
            ],
        ];

        $this->forge->addColumn('m_merk', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('m_merk', ['PDFTHUMBNAIL', 'PDFFILE', 'STATUSKATALOG']);
    }
}
