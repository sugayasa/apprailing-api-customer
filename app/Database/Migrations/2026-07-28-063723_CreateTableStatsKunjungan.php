<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableStatsKunjungan extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'IDSTATSKUNJUNGAN' => [
                'type'           => 'INT',
                'auto_increment' => true,
            ],
            'IDCUSTOMER' => [
                'type'    => 'INT',
                'default' => 0,
            ],
            'HARDWAREID' => [
                'type'       => 'CHAR',
                'constraint' => 48,
                'default'    => '-',
            ],
            'TANGGAL' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'WAKTUAWAL' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'WAKTUAKHIR' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'TOTALWAKTU' => [
                'type'     => 'MEDIUMINT',
                'unsigned' => true,
                'default'  => 0,
            ],
            'ISPROSESDAFTAR' => [
                'type'    => 'BOOLEAN',
                'default' => false,
            ],
        ]);
        $this->forge->addKey('IDSTATSKUNJUNGAN', true);
        $this->forge->addUniqueKey(['TANGGAL', 'HARDWAREID']);
        $this->forge->createTable('stats_kunjungan');
        $this->db->query('ALTER TABLE stats_kunjungan AUTO_INCREMENT = 70000');
    }

    public function down()
    {
        $this->forge->dropTable('stats_kunjungan');
    }
}
