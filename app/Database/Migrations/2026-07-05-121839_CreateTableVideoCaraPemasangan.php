<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableVideoCaraPemasangan extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'IDVIDEOCARAPEMASANGAN' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'JUDUL' => [
                'type'       => 'VARCHAR',
                'constraint' => 25,
                'null'       => false,
                'default'    => '',
            ],
            'KONTEN' => [
                'type' => 'MEDIUMTEXT',
                'null' => false,
            ],
            'IMAGETHUMBNAIL' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
                'default'    => 'default.jpg',
            ],
            'URLVIDEO' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'INPUTUSER' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
                'default'    => 'Auto System',
            ],
            'INPUTTANGGALWAKTU' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'URUTAN' => [
                'type'       => 'SMALLINT',
                'constraint' => 6,
                'null'       => false,
                'default'    => 99,
            ],
            'STATUS' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => false,
                'default'    => 1,
                'comment'    => '//1:AKTIF, -1:NON AKTIF',
            ],
        ]);
        $this->forge->addKey('IDVIDEOCARAPEMASANGAN', true);
        $this->forge->addUniqueKey('JUDUL');
        $this->forge->createTable('t_videocarapemasangan');
        $this->db->query('ALTER TABLE t_videocarapemasangan AUTO_INCREMENT = 3000');
        $this->db->query("ALTER TABLE t_videocarapemasangan MODIFY COLUMN INPUTTANGGALWAKTU DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP");
    }

    public function down()
    {
        $this->forge->dropTable('t_videocarapemasangan');
    }
}
