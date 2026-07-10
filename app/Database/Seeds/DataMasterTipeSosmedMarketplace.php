<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DataMasterTipeSosmedMarketplace extends Seeder
{
    public function run()
    {
        $data = [
            [
                'NAMATIPE'  => 'Facebook',
                'FILEICON'  => 'facebook.png',
                'URUTAN'    => 1,
                'STATUS'    => 1,
            ],
            [
                'NAMATIPE'  => 'Instagram',
                'FILEICON'  => 'instagram.png',
                'URUTAN'    => 2,
                'STATUS'    => 1,
            ],
            [
                'NAMATIPE'  => 'Tiktok',
                'FILEICON'  => 'tiktok.png',
                'URUTAN'    => 3,
                'STATUS'    => 1,
            ],
            [
                'NAMATIPE'  => 'Youtube',
                'FILEICON'  => 'youtube.png',
                'URUTAN'    => 4,
                'STATUS'    => 1,
            ],
            [
                'NAMATIPE'  => 'Shopee',
                'FILEICON'  => 'shopee.png',
                'URUTAN'    => 5,
                'STATUS'    => 1,
            ],
            [
                'NAMATIPE'  => 'Tokopedia',
                'FILEICON'  => 'tokopedia.png',
                'URUTAN'    => 6,
                'STATUS'    => 1,
            ],
            [
                'NAMATIPE'  => 'Tiktok Shop',
                'FILEICON'  => 'tiktokshop.png',
                'URUTAN'    => 7,
                'STATUS'    => 1,
            ],
            [
                'NAMATIPE'  => 'Website',
                'FILEICON'  => 'website.png',
                'URUTAN'    => 8,
                'STATUS'    => 1,
            ],
        ];

        $this->db->table('m_tipesosmedmarketplace')->insertBatch($data);
    }
}
