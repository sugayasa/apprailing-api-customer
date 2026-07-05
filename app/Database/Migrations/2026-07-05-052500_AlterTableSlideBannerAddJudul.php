<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableSlideBannerAddJudul extends Migration
{
    public function up()
    {
        $this->forge->addColumn('t_slidebanner', [
            'JUDUL' => [
                'type'       => 'VARCHAR',
                'constraint' => 75,
                'null'       => false,
                'default'    => '',
                'after'      => 'IDSLIDEBANNER',
            ],
        ]);

        // Update JUDUL dari KONTEN yang sudah ada
        $db     =   \Config\Database::connect();
        $builder=   $db->table('t_slidebanner');
        $rows   =   $builder->select('IDSLIDEBANNER, KONTEN')->get()->getResult();

        foreach ($rows as $row) {
            $text   =   strip_tags($row->KONTEN);
            $text   =   html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $text   =   trim($text);

            // Ambil kalimat pertama sampai tanda titik
            $pos    =   mb_strpos($text, '.');
            if ($pos !== false) {
                $text = mb_substr($text, 0, $pos);
            }

            // Maksimal 75 karakter
            $judul  =   mb_substr(trim($text), 0, 75);

            $builder->where('IDSLIDEBANNER', $row->IDSLIDEBANNER)->update(['JUDUL' => $judul]);
        }
    }

    public function down()
    {
        $this->forge->dropColumn('t_slidebanner', 'JUDUL');
    }
}
