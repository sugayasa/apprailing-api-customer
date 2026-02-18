<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MKategori extends Seeder
{
    public function run()
    {
        $data = [
            ['NAMAKATEGORI' => 'Elegant Design', 'DESKRIPSI' => '-', 'STATUS' => '1'],
            ['NAMAKATEGORI' => 'American Classic Design', 'DESKRIPSI' => '-', 'STATUS' => '1'],
            ['NAMAKATEGORI' => 'Luxurious Design', 'DESKRIPSI' => '-', 'STATUS' => '1'],
            ['NAMAKATEGORI' => 'Nature Design', 'DESKRIPSI' => '-', 'STATUS' => '1'],
            ['NAMAKATEGORI' => 'Industrial Design', 'DESKRIPSI' => '-', 'STATUS' => '1'],
            ['NAMAKATEGORI' => 'Crystal Design', 'DESKRIPSI' => '-', 'STATUS' => '1'],
            ['NAMAKATEGORI' => 'Classic Design', 'DESKRIPSI' => '-', 'STATUS' => '1'],
            ['NAMAKATEGORI' => 'Glass Design', 'DESKRIPSI' => '-', 'STATUS' => '1'],
            ['NAMAKATEGORI' => 'Glass Colour Series', 'DESKRIPSI' => '-', 'STATUS' => '1'],
            ['NAMAKATEGORI' => 'Spider Glass', 'DESKRIPSI' => '-', 'STATUS' => '1'],
            ['NAMAKATEGORI' => 'Accessories Railing', 'DESKRIPSI' => '-', 'STATUS' => '1'],
            ['NAMAKATEGORI' => 'Pipe Series', 'DESKRIPSI' => '-', 'STATUS' => '1'],
            ['NAMAKATEGORI' => 'Handrailing Stainless Steel Series', 'DESKRIPSI' => '-', 'STATUS' => '1'],
            ['NAMAKATEGORI' => 'Minimalist', 'DESKRIPSI' => '-', 'STATUS' => '1'],
            ['NAMAKATEGORI' => 'Plate Railing', 'DESKRIPSI' => '-', 'STATUS' => '1'],
            ['NAMAKATEGORI' => 'Accesories Railingku', 'DESKRIPSI' => '-', 'STATUS' => '1'],
            ['NAMAKATEGORI' => 'Handrailing Railingku', 'DESKRIPSI' => '-', 'STATUS' => '1'],
            ['NAMAKATEGORI' => 'Bracket Railingku', 'DESKRIPSI' => '-', 'STATUS' => '1'],
            ['NAMAKATEGORI' => 'Bracket Railing', 'DESKRIPSI' => '-', 'STATUS' => '1'],
            ['NAMAKATEGORI' => 'Cleaner', 'DESKRIPSI' => '-', 'STATUS' => '1'],
            ['NAMAKATEGORI' => 'Iron Model', 'DESKRIPSI' => '-', 'STATUS' => '1'],
            ['NAMAKATEGORI' => 'Wood Model', 'DESKRIPSI' => '-', 'STATUS' => '1'],
            ['NAMAKATEGORI' => 'Hollow Model', 'DESKRIPSI' => '-', 'STATUS' => '1'],
            ['NAMAKATEGORI' => 'Handrailing PVC Series', 'DESKRIPSI' => '-', 'STATUS' => '1'],
            ['NAMAKATEGORI' => 'Gold Edition', 'DESKRIPSI' => '-', 'STATUS' => '1'],
            ['NAMAKATEGORI' => 'Minimalist Hollow', 'DESKRIPSI' => '-', 'STATUS' => '1'],
            ['NAMAKATEGORI' => 'Public Area Railing Premium', 'DESKRIPSI' => '-', 'STATUS' => '1'],
            ['NAMAKATEGORI' => 'Stainless Steel Plat Minimalist', 'DESKRIPSI' => '-', 'STATUS' => '1'],
            ['NAMAKATEGORI' => 'Accesories Custom Railingku', 'DESKRIPSI' => '-', 'STATUS' => '1'],
            ['NAMAKATEGORI' => 'Elegant Marmer Iron Design', 'DESKRIPSI' => '-', 'STATUS' => '1'],
            ['NAMAKATEGORI' => 'Vertical Railing', 'DESKRIPSI' => '-', 'STATUS' => '1'],
            ['NAMAKATEGORI' => 'Pipe Railing', 'DESKRIPSI' => '-', 'STATUS' => '1']
        ];

        $this->db->table('m_kategori')->insertBatch($data);
    }
}
