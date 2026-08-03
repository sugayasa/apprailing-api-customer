<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MasterTipeKonten extends Seeder
{
    public function run()
    {
        $data = [
            ['NAMATIPE' => 'Berita'],
            ['NAMATIPE' => 'Galeri Klien'],
            ['NAMATIPE' => 'Galeri Proyek'],
            ['NAMATIPE' => 'Feed'],
        ];

        $this->db->table('m_tipekonten')->insertBatch($data);
    }
}
