<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableGaleriKlien extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'IDGALERIKLIEN' => [
                'type'           => 'INT',
                'auto_increment' => true,
            ],
            'IDKLIEN' => [
                'type'    => 'INT',
                'default' => 0,
            ],
            'IDMERKUTAMA' => [
                'type'    => 'INT',
                'default' => 100,
            ],
            'DESKRIPSI' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'default'    => '',
            ],
            'IMAGE' => [
                'type' => 'JSON',
            ],
            'INPUTUSER' => [
                'type'       => 'CHAR',
                'constraint' => 64,
                'default'    => '-',
            ],
            'INPUTTANGGALWAKTU' => [
                'type' => 'DATETIME',
            ],
        ]);
        $this->forge->addKey('IDGALERIKLIEN', true);
        $this->forge->addKey(['IDKLIEN', 'IDMERKUTAMA']);
        $this->forge->createTable('t_galeriklien');
        $this->db->query('ALTER TABLE t_galeriklien AUTO_INCREMENT = 70000');
        $this->db->query("ALTER TABLE `t_galeriklien` MODIFY `INPUTTANGGALWAKTU` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP");
        $this->db->query("ALTER TABLE `t_galeriklien` MODIFY `IMAGE` JSON NOT NULL DEFAULT (JSON_ARRAY('noimage.jpg'))");
    }

    public function down()
    {
        $this->forge->dropTable('t_galeriklien');
    }
}
