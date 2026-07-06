<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DataVideoCompanyProfile extends Seeder
{
    public function run()
    {
        $data = [
            [
                'JUDUL'          => 'Company Profile Rich Railing, Railing Tangga Stainless',
                'KONTEN'         => 'Hai Bapak/Ibu yang ingin memasang railing tangga untuk di rumah, kebutuhan kantor atau proyek, atau sudah bosan dengan desain dan model itu-itu saja, bingung gimana cara pemasangannya, atau takut berkarat, jangan khawatir.

RICH RAILING SOLUSINYA
Apa aja sih keunggulan dari Jasa Rich Railing?
Berkualitas dan diproduksi langsung dari mesin pabrik sudah teruji klinis
Bergaransi: Jaminan harga termurah di seluruh Indonesia
Beragam model dan pilihan
-Harga TERMURAH mulai dari Rp.150.000/tiang dan Rp 650.000/m (Material Stainless)
Free Konsultasi & Survey

Jadi, tunggu apalagi? Informasi dan Pemesanan, hubungi kami di ....
Whatsapp: +6281299667472 (Chat/Telf)
feliciarichrailing@gmail.com
Line: @richrailing
Instagram: @rich.railing @testimonial.richrailing',
                'IMAGETHUMBNAIL' => 'example.jpg',
                'URLVIDEO'       => 'https://www.youtube.com/watch?v=XAq6zx2UY1o&t=2s',
                'URUTAN'         => 99,
                'STATUS'         => 1,
            ],
        ];

        $this->db->table('t_videocompanyprofile')->insertBatch($data);
    }
}
