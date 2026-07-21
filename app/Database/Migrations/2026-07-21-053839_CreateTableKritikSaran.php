<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableKritikSaran extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'IDKRITIKSARAN' => [
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
            'SUBYEK' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'default'    => '-',
            ],
            'PESAN' => [
                'type' => 'TEXT',
            ],
            'INPUTTANGGALWAKTU' => [
                'type' => 'DATETIME',
            ],
        ]);
        $this->forge->addKey('IDKRITIKSARAN', true);
        $this->forge->addUniqueKey(['IDCUSTOMER', 'SUBYEK']);
        $this->forge->createTable('t_kritiksaran');
        $this->db->query('ALTER TABLE t_kritiksaran AUTO_INCREMENT = 3000');
    }

    public function down()
    {
        $this->forge->dropTable('t_kritiksaran');
    }
}
