<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableMCustomerAddUniqueCode extends Migration
{
    public function up()
    {
        $fields = [
            'KODEUNIK' => [
                'type'      => 'VARCHAR',
                'constraint'=> 8,
                'null'      => true,
                'default'   => null,
                'after'     => 'NOMORHP',
            ],
        ];

        $this->forge->addColumn('m_customer', $fields);

        helper('base');
        $customers = $this->db->table('m_customer')
            ->select('IDCUSTOMER')
            ->where('KODEUNIK', null)
            ->get()
            ->getResult();

        foreach ($customers as $customer) {
            $kodeUnik = generateCustomerCode($customer->IDCUSTOMER);

            $this->db->table('m_customer')
                ->where('IDCUSTOMER', $customer->IDCUSTOMER)
                ->update(['KODEUNIK' => $kodeUnik]);
        }
    }

    public function down()
    {
        $this->forge->dropColumn('m_customer', 'KODEUNIK');
    }
}
