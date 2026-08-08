<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterUpdateMasterStatusTransaksi extends Migration
{
    public function up()
    {
        $this->forge->addColumn('m_statustransaksi', [
            'COLORCLASSBS' => [
                'type'       => 'CHAR',
                'constraint' => 20,
                'null'       => false,
                'default'    => 'info',
                'after'      => 'DESKRIPSI',
            ],
            'COLORHEXCODE' => [
                'type'       => 'CHAR',
                'constraint' => 8,
                'null'       => false,
                'default'    => '#0d6efd',
                'after'      => 'COLORCLASSBS',
            ],
        ]);

        $statusColors = [
            ['IDSTATUSTRANSAKSI' => 1,  'COLORCLASSBS' => 'info',      'COLORHEXCODE' => '#0dcaf0'],
            ['IDSTATUSTRANSAKSI' => 2,  'COLORCLASSBS' => 'warning',   'COLORHEXCODE' => '#ffc107'],
            ['IDSTATUSTRANSAKSI' => 3,  'COLORCLASSBS' => 'secondary', 'COLORHEXCODE' => '#6c757d'],
            ['IDSTATUSTRANSAKSI' => 4,  'COLORCLASSBS' => 'primary',   'COLORHEXCODE' => '#0d6efd'],
            ['IDSTATUSTRANSAKSI' => 5,  'COLORCLASSBS' => 'primary',   'COLORHEXCODE' => '#0d6efd'],
            ['IDSTATUSTRANSAKSI' => 6,  'COLORCLASSBS' => 'success',   'COLORHEXCODE' => '#198754'],
            ['IDSTATUSTRANSAKSI' => 7,  'COLORCLASSBS' => 'danger',    'COLORHEXCODE' => '#dc3545'],
            ['IDSTATUSTRANSAKSI' => 8,  'COLORCLASSBS' => 'danger',    'COLORHEXCODE' => '#dc3545'],
            ['IDSTATUSTRANSAKSI' => 9,  'COLORCLASSBS' => 'dark',      'COLORHEXCODE' => '#212529'],
            ['IDSTATUSTRANSAKSI' => 10, 'COLORCLASSBS' => 'dark',      'COLORHEXCODE' => '#212529'],
        ];

        foreach ($statusColors as $status) {
            $this->db->table('m_statustransaksi')
                     ->where('IDSTATUSTRANSAKSI', $status['IDSTATUSTRANSAKSI'])
                     ->update([
                         'COLORCLASSBS' => $status['COLORCLASSBS'],
                         'COLORHEXCODE' => $status['COLORHEXCODE'],
                     ]);
        }
    }

    public function down()
    {
        $this->forge->dropColumn('m_statustransaksi', ['COLORCLASSBS', 'COLORHEXCODE']);
    }
}
