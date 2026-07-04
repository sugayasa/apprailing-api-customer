<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DataReviewMarketingForDeveloper extends Seeder
{
    public function run()
    {
        $data = [
            [
                'IDCUSTOMER'     => 0,
                'NAMAMARKETING'  => 'Apps Developer',
                'RATING'         => 5,
                'KOMENTAR'       => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since 1966, when designers at Letraset and James Mosley.",
                'IMAGEMARKETING' => 'default.jpg',
                'TANGGAL'        => '2026-07-04',
                'TANGGALWAKTU'   => '2026-07-04 21:34:19',
            ],
        ];

        $this->db->table('t_reviewmarketing')->insertBatch($data);
    }
}
