<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableStatsKonten extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'IDSTATSKONTEN' => [
                'type'           => 'INT',
                'auto_increment' => true,
            ],
            'IDTIPEKONTEN' => [
                'type'    => 'INT',
                'default' => 0,
            ],
            'IDPRIMARYKONTEN' => [
                'type'    => 'INT',
                'default' => 0,
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
            'TANGGALWAKTU'=> [
                'type' => 'DATETIME',
            ],
        ]);
        $this->forge->addKey('IDSTATSKONTEN', true);
        $this->forge->addUniqueKey(['TANGGALWAKTU', 'HARDWAREID', 'IDPRIMARYKONTEN']);
        $this->forge->createTable('stats_konten');
        $this->db->query('ALTER TABLE stats_konten AUTO_INCREMENT = 50000');
        $this->db->query("ALTER TABLE `stats_konten` MODIFY `TANGGALWAKTU` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP");
    }

    public function down()
    {
        $this->forge->dropTable('stats_konten');
    }
}
