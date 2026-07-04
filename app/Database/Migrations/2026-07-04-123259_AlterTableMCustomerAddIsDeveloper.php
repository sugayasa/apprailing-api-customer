<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableMCustomerAddIsDeveloper extends Migration
{
    public function up()
    {
        $fields = [
            'ISDEVELOPER' => [
                'type'    => 'BOOLEAN',
                'null'    => false,
                'default' => false,
                'after'   => 'AVATAR',
            ],
        ];

        $this->forge->addColumn('m_customer', $fields);

        $this->db->table('m_customer')
            ->update(['ISDEVELOPER' => true]);
    }

    public function down()
    {
        $this->forge->dropColumn('m_customer', 'ISDEVELOPER');
    }
}
