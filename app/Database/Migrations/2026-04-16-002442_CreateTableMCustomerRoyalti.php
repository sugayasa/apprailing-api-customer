<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableMCustomerRoyalti extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'IDCUSTOMERROYALTI' => [
                'type'           => 'INT',
                'auto_increment' => true,
            ],
            'ROYALTITIER' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
            ],
            'DESKRIPSI' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'MINIMALNOMINALPEMBELIAN' => [
                'type' => 'INT',
            ],
            'ICON' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'STATUS' => [
                'type'       => 'TINYINT',
                'constraint' => '1',
                'default'    => '1',
            ],
        ]);
        
        $this->forge->addKey('IDCUSTOMERROYALTI', true);
        $this->forge->createTable('m_customerroyalti', true, ['ENGINE' => 'InnoDB']);
        
        // Set auto_increment starting value to 301
        $this->db->query('ALTER TABLE m_customerroyalti AUTO_INCREMENT = 301');
        
        // Add IDCUSTOMERROYALTI column to m_customer table
        $this->forge->addColumn('m_customer', [
            'IDCUSTOMERROYALTI' => [
                'type'    => 'INT',
                'default' => 301,
                'after'   => 'IDCUSTOMER',
            ],
        ]);
        
        // Add index on IDCUSTOMERROYALTI column in m_customer table
        $this->forge->addKey('IDCUSTOMERROYALTI', false);
        $this->forge->processIndexes('m_customer');
    }

    public function down()
    {
        // Drop index on IDCUSTOMERROYALTI column in m_customer table
        $this->forge->dropKey('m_customer', 'IDCUSTOMERROYALTI');
        
        // Remove IDCUSTOMERROYALTI column from m_customer table
        $this->forge->dropColumn('m_customer', 'IDCUSTOMERROYALTI');
        
        $this->forge->dropTable('m_customerroyalti', true);
    }
}
