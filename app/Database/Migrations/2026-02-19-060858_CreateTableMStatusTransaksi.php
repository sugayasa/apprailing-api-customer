<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableMStatusTransaksi extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'IDSTATUSTRANSAKSI' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => false,
                'auto_increment' => true,
            ],
            'STATUSTRANSAKSI' => [
                'type'       => 'VARCHAR',
                'constraint' => '40',
            ],
            'DESKRIPSI' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'URUTAN' => [
                'type'       => 'TINYINT',
                'default'    => 99,
            ],
            'STATUS' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
                'comment'    => '1:AKTIF, -1:NON AKTIF',
            ],
        ]);

        $this->forge->addKey('IDSTATUSTRANSAKSI', true);
        $this->forge->addUniqueKey('STATUSTRANSAKSI');

        $this->forge->createTable('m_statustransaksi', true, [
            'ENGINE'         => 'InnoDB',
            'AUTO_INCREMENT' => '11',
            'CHARSET'        => 'utf8mb4',
            'COLLATE'        => 'utf8mb4_unicode_ci',
        ]);

        $data = [
            ['IDSTATUSTRANSAKSI' => 1, 'STATUSTRANSAKSI' => 'Pesanan Dibuat', 'DESKRIPSI' => 'Pesanan berhasil dibuat, menunggu pembayaran', 'URUTAN' => 1, 'STATUS' => 1],
            ['IDSTATUSTRANSAKSI' => 2, 'STATUSTRANSAKSI' => 'Pembayaran Diterima', 'DESKRIPSI' => 'Pembayaran diterima, barang sedang disiapkan', 'URUTAN' => 2, 'STATUS' => 1],
            ['IDSTATUSTRANSAKSI' => 3, 'STATUSTRANSAKSI' => 'Barang Siap Dikirim', 'DESKRIPSI' => 'Barang selesai disiapkan, menunggu pengiriman', 'URUTAN' => 3, 'STATUS' => 1],
            ['IDSTATUSTRANSAKSI' => 4, 'STATUSTRANSAKSI' => 'Pengiriman Kurir Internal', 'DESKRIPSI' => 'Barang sedang dikirim oleh kurir internal', 'URUTAN' => 4, 'STATUS' => 1],
            ['IDSTATUSTRANSAKSI' => 5, 'STATUSTRANSAKSI' => 'Pengiriman Kurir Eksternal', 'DESKRIPSI' => 'Barang sudah diserahkan ke kurir eksternal dan sedang dikirim', 'URUTAN' => 5, 'STATUS' => 1],
            ['IDSTATUSTRANSAKSI' => 6, 'STATUSTRANSAKSI' => 'Transaksi Selesai', 'DESKRIPSI' => 'Barang sudah diterima konsumen, transaksi selesai', 'URUTAN' => 6, 'STATUS' => 1],
            ['IDSTATUSTRANSAKSI' => 7, 'STATUSTRANSAKSI' => 'Barang Diretur - Tukar', 'DESKRIPSI' => 'Barang dikembalikan untuk ditukar', 'URUTAN' => 7, 'STATUS' => 1],
            ['IDSTATUSTRANSAKSI' => 8, 'STATUSTRANSAKSI' => 'Barang Diretur - Dana Dikembalikan', 'DESKRIPSI' => 'Barang dikembalikan dan dana dikembalikan', 'URUTAN' => 8, 'STATUS' => 1],
            ['IDSTATUSTRANSAKSI' => 9, 'STATUSTRANSAKSI' => 'Pesanan Dibatalkan Konsumen', 'DESKRIPSI' => 'Pesanan dibatalkan oleh konsumen', 'URUTAN' => 9, 'STATUS' => 1],
            ['IDSTATUSTRANSAKSI' => 10, 'STATUSTRANSAKSI' => 'Pesanan Dibatalkan - Kadaularsa', 'DESKRIPSI' => 'Pesanan dibatalkan karena melebihi batas waktu pembayaran', 'URUTAN' => 10, 'STATUS' => 1]
        ];

        $this->db->table('m_statustransaksi')->insertBatch($data);
    }

    public function down()
    {
        $this->forge->dropTable('m_statustransaksi');
    }
}
