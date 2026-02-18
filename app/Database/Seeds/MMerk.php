<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MMerk extends Seeder
{
    public function run()
    {
        $data = [
            [
                'NAMAMERK' => 'Rich Railing',
                'LOGO' => 'richrailing.png',
                'STATUS' => '1'
            ],
            [
                'NAMAMERK' => 'Railingku',
                'LOGO' => 'railingku.png',
                'STATUS' => '1'
            ],
            [
                'NAMAMERK' => 'Weezy',
                'LOGO' => 'weezy.jpg',
                'STATUS' => '1'
            ]
        ];

        $this->db->table('m_merk')->insertBatch($data);
    }
}
