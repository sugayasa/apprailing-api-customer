<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DataVideoCaraPemasangan extends Seeder
{
    public function run()
    {
        $data = [
            [
                'JUDUL'           => 'Pemasangan Single Plat',
                'KONTEN'          => '<p>Itu dia cara pemasangan single plat untuk tiang RRP 302. Jadi, sangat gampang bukan? Cuma 1 menit kalian udah bisa pasang single platnya dengan mudah sekali tanpa harus ada alat bantu yang lainnya. Kira – kira model railing mana nih yang jadi favorite kalian ? oiya, untuk RRP 302 ini ada garansi selama 25 tahun yaa!!<br><br>Untuk info lebih lanjut kalian bisa langsung hubungi kontak dibawah ini yaaa<br>WhatsApp : 081299667472<br>Instagram : Rich.Railing / Richrailing_Tangerang<br>Facebook : Rich Railing Jakarta<br>Tiktok : RichRailing_Official<br>Website : richrailing.com</p>',
                'IMAGETHUMBNAIL'  => 'thumbnail-1.png',
                'URLVIDEO'        => 'https://www.youtube.com/watch?v=z3YIq4dcnX4',
                'URUTAN'          => 1,
                'STATUS'          => 1,
            ],
            [
                'JUDUL'           => 'Membengkokkan Handrailing PVC',
                'KONTEN'          => 'Ternyata membengkokkan handrail PVC memiliki cara dan teknik khusus loh!!<br>Kayak gimana ya tekniknya?<br>_________________________&nbsp;<br>JASA PEMASANGAN RAILING TANGGA STAINLESS PEMBELIAN DAN PEMASANGAN DALAM/LUAR KOTA SELURUH INDONESIA&nbsp;<br><br>Hai Bapak/Ibu yang ingin memasang railing tangga untuk di rumah, kebutuhan kantor atau proyek, atau sudah bosan dengan desain dan model itu-itu saja, bingung gimana cara pemasangannya, atau takut berkarat, jangan khawatir.&nbsp;<br><br>RICH RAILING SOLUSINYA&nbsp;<br><br>Apa aja sih keunggulan dari Jasa Rich Railing?&nbsp;<br>Berkualitas dan diproduksi langsung dari mesin pabrik sudah teruji klinis<br>Bergaransi: Jaminan harga termurah di seluruh Indonesia<br>Beragam model dan pilihan<br>Harga TERMURAH mulai dari Rp.150.000/tiang dan Rp 650.000/m (Material Stainless)<br>Free Konsultasi &amp; Survey',
                'IMAGETHUMBNAIL'  => 'thumbnail-2.png',
                'URLVIDEO'        => 'https://www.youtube.com/watch?v=Mj8C96GZZp0',
                'URUTAN'          => 2,
                'STATUS'          => 1,
            ],
        ];

        $this->db->table('t_videocarapemasangan')->insertBatch($data);
    }
}
