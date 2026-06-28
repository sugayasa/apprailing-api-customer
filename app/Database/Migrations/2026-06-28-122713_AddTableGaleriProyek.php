<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTableGaleriProyek extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'IDGALERIPROYEK' => [
                'type'           => 'INT',
                'auto_increment' => true,
            ],
            'IDMERKUTAMA' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 100,
            ],
            'NAMAKLIEN' => [
                'type'       => 'VARCHAR',
                'constraint' => 75,
                'default'    => '',
            ],
            'URUTAN' => [
                'type'       => 'SMALLINT',
                'default'    => 999,
            ],
            'IMAGE' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'default.jpg',
            ],
            'INPUTUSER' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => '-',
            ],
            'INPUTTANGGALWAKTU' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'STATUS' => [
                'type'       => 'TINYINT',
                'default'    => 1,
            ],
        ]);

        $this->forge->addKey('IDGALERIPROYEK', true);
        $this->forge->createTable('t_galeriproyek', true, [
            'ENGINE' => 'InnoDB',
        ]);

        $this->db->query("ALTER TABLE `t_galeriproyek` AUTO_INCREMENT = 600");
    }

    public function down()
    {
        $this->forge->dropTable('t_galeriproyek');
    }
}
