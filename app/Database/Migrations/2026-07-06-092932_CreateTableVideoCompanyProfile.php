<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableVideoCompanyProfile extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'IDVIDEOCOMPANYPROFILE' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'JUDUL' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
                'default'    => '',
            ],
            'KONTEN' => [
                'type'    => 'MEDIUMTEXT',
                'null'    => false,
                'default' => '',
            ],
            'IMAGETHUMBNAIL' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
                'default'    => 'thumbnailDefault.png',
            ],
            'URLVIDEO' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
                'default'    => '',
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
        $this->forge->addKey('IDVIDEOCOMPANYPROFILE', true);
        $this->forge->addUniqueKey('JUDUL');
        $this->forge->addUniqueKey('URLVIDEO');
        $this->forge->createTable('t_videocompanyprofile');
        $this->db->query("ALTER TABLE t_videocompanyprofile MODIFY COLUMN INPUTTANGGALWAKTU DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP");
        $this->db->query('ALTER TABLE t_videocompanyprofile AUTO_INCREMENT = 2000');
    }

    public function down()
    {
        $this->forge->dropTable('t_videocompanyprofile');
    }
}
