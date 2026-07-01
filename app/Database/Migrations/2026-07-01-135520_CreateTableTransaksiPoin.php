<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableTransaksiPoin extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'IDTRANSAKSIPOIN' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'IDCUSTOMER' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'IDTRANSAKSIREKAP' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'NOMINAL' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'NOMINALPERPOIN' => [
                'type'       => 'MEDIUMINT',
                'constraint' => 9,
                'default'    => 1000,
            ],
            'POIN' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
            ],
            'KETERANGAN' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
                'default'    => '-',
            ],
            'TANGGALWAKTU' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('IDTRANSAKSIPOIN', true);
        $this->forge->addUniqueKey(['IDCUSTOMER', 'IDTRANSAKSIREKAP']);
        $this->forge->createTable('t_transaksipoin');
        $this->db->query('ALTER TABLE `t_transaksipoin` AUTO_INCREMENT = 5000');
        $this->db->query("ALTER TABLE `t_transaksipoin` MODIFY `TANGGALWAKTU` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP");
    }

    public function down()
    {
        $this->forge->dropTable('t_transaksipoin');
    }
}
