<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DataSosmedMarketplace extends Seeder
{
    public function run()
    {
        $data = [
            [
                'IDTIPESOSMEDMARKETPLACE' => 200,
                'NAMAAKUN'                => 'Rich Railing',
                'URL'                     => 'https://www.facebook.com/Richrailingofficial/',
                'URUTAN'                  => 1,
            ],
            [
                'IDTIPESOSMEDMARKETPLACE' => 201,
                'NAMAAKUN'                => 'Rich Railing',
                'URL'                     => 'https://www.instagram.com/richrailing.official',
                'URUTAN'                  => 1,
            ],
            [
                'IDTIPESOSMEDMARKETPLACE' => 202,
                'NAMAAKUN'                => 'Rich Railing',
                'URL'                     => 'https://www.tiktok.com/@richrailingindonesia',
                'URUTAN'                  => 1,
            ],
            [
                'IDTIPESOSMEDMARKETPLACE' => 203,
                'NAMAAKUN'                => 'Rich Railing',
                'URL'                     => 'https://www.youtube.com/@richrailingofficial2204',
                'URUTAN'                  => 1,
            ],
            [
                'IDTIPESOSMEDMARKETPLACE' => 204,
                'NAMAAKUN'                => 'Rich Railing',
                'URL'                     => 'https://shopee.co.id/richrailingofficial_',
                'URUTAN'                  => 1,
            ],
            [
                'IDTIPESOSMEDMARKETPLACE' => 205,
                'NAMAAKUN'                => 'Rich Railing',
                'URL'                     => 'https://www.tokopedia.com/richrailing-233',
                'URUTAN'                  => 1,
            ],
            [
                'IDTIPESOSMEDMARKETPLACE' => 206,
                'NAMAAKUN'                => 'Rich Railing',
                'URL'                     => 'https://www.tiktok.com/@richrailingindonesia',
                'URUTAN'                  => 1,
            ],
            [
                'IDTIPESOSMEDMARKETPLACE' => 207,
                'NAMAAKUN'                => 'Rich Railing',
                'URL'                     => 'https://www.richrailing.com',
                'URUTAN'                  => 1,
            ],
        ];

        $this->db->table('t_sosmedmarketplace')->insertBatch($data);
    }
}
