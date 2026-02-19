<?php

namespace App\Models;
use CodeIgniter\Model;

class TransaksiModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 't_transaksirekap';
    protected $primaryKey       = 'IDTRANSAKSIREKAP';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['IDREGIONAL', 'IDCUSTOMER', 'IDKANALPEMBAYARAN', 'IDEKSPEDISI', 'IDSTATUSTRANSAKSI', 'NOMORTRANSAKSI', 'NOMORRESIEKSPEDISI', 'CATATAN', 'TOTALBARANG', 'TOTALNOMINALBARANG', 'TOTALNOMINALONGKIR', 'TOTALNOMINALDISKON', 'TOTALNOMINALBAYAR', 'INPUTTANGGALWAKTU'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getDataTransaksi($idCustomer, $idStatusTransaksi, $idKategori, $searchKeyword)
    {	
        $this->select("A.IDTRANSAKSIREKAP, A.INPUTTANGGALWAKTU, C.STATUSTRANSAKSI, DATE_FORMAT(A.INPUTTANGGALWAKTU, '%d %b %Y %H:%i') AS INPUTTANGGALWAKTUSTR, B.NAMAREGIONAL,
                    GROUP_CONCAT(D.IDPRODUK) AS ARRIDPRODUK, IF(A.TOTALBARANG > 3, A.TOTALBARANG - 3, 0) AS TOTALBARANGLAIN, A.NOMORTRANSAKSI, A.TOTALNOMINALBAYAR");
        $this->from('t_transaksirekap AS A', true);
        $this->join('m_regional AS B', 'A.IDREGIONAL = B.IDREGIONAL', 'LEFT');
        $this->join('m_statustransaksi AS C', 'A.IDSTATUSTRANSAKSI = C.IDSTATUSTRANSAKSI', 'LEFT');
        $this->join('t_transaksibarang AS D', 'A.IDTRANSAKSIREKAP = D.IDTRANSAKSIREKAP', 'LEFT');
        $this->where('A.IDCUSTOMER', $idCustomer);

        if(issetNotNullAndNotEmptyString($idStatusTransaksi)) $this->where('A.IDSTATUSTRANSAKSI', $idStatusTransaksi);
        if(issetNotNullAndNotEmptyString($idKategori) || issetNotNullAndNotEmptyString($searchKeyword)){
            $arrIdTransaksiRekap    =   $this->getArrIdTransaksiRekapByKategoriAndSearchKeyword($idCustomer, $idKategori, $searchKeyword);
            if(isset($arrIdTransaksiRekap) && is_array($arrIdTransaksiRekap) && count($arrIdTransaksiRekap) > 0) {
                $this->whereIn('A.IDTRANSAKSIREKAP', $arrIdTransaksiRekap);
            } else {
                $this->where('A.IDTRANSAKSIREKAP', 0);
            }
        }

        if(isset($searchKeyword) && $searchKeyword != "") {
            $this->groupStart();
            $this->like('A.NOMORTRANSAKSI', $searchKeyword);
            $this->orLike('A.NOMORRESIEKSPEDISI', $searchKeyword);
            $this->orLike('A.CATATAN', $searchKeyword);
            $this->groupEnd();
        }
        return $this;
	}

    private function getArrIdTransaksiRekapByKategoriAndSearchKeyword($idCustomer, $idKategori, $searchKeyword)
    {	
        $this->select("DISTINCT A.IDTRANSAKSIREKAP");
        $this->from('t_transaksibarang AS A', true);
        $this->join('t_transaksirekap AS B', 'A.IDTRANSAKSIREKAP = B.IDTRANSAKSIREKAP', 'LEFT');
        $this->join('t_produk AS C', 'A.IDPRODUK = C.IDPRODUK', 'LEFT');
        $this->where('B.IDCUSTOMER', $idCustomer);

        if(issetNotNullAndNotEmptyString($idKategori)) $this->where('C.IDKATEGORI', $idKategori);
        if(issetNotNullAndNotEmptyString($searchKeyword)) {
            $this->groupStart();
            $this->like('A.KETERANGAN', $searchKeyword);
            $this->orLike('C.NAMAPRODUK', $searchKeyword);
            $this->orLike('C.DESKRIPSI', $searchKeyword);
            $this->groupEnd();
        }

        $result     =   $this->get()->getResultObject();

        if(is_null($result)) return false;
        $arrIdTransaksiRekap = [];
        foreach($result AS $data) {
            $arrIdTransaksiRekap[] = $data->IDTRANSAKSIREKAP;
        }

        return $arrIdTransaksiRekap;
	}

    public function getDataImageProduk($arrIdProduk)
    {	
        $this->select("ARRIMAGE");
        $this->from('t_produk', true);
        $this->whereIn('IDPRODUK', $arrIdProduk);

        $result     =   $this->get()->getResultObject();

        if(is_null($result)) return false;
        return $result;
	}

    public function getDetailTransaksi($idTransaksiRekap)
    {	
        $this->select("DATE_FORMAT(A.INPUTTANGGALWAKTU, '%d %b %Y %H:%i') AS INPUTTANGGALWAKTUSTR, B.NAMAREGIONAL, C.STATUSTRANSAKSI, D.NAMAKANALPEMBAYARAN,
                A.NOMORTRANSAKSI, A.NOMORRESIEKSPEDISI, E.NAMAEKSPEDISI, A.ALAMATNAMA, A.ALAMATKIRIM, A.PENERIMANAMA, A.PENERIMANOMORTELEPON, A.CATATAN,
                A.TOTALBARANG, A.TOTALNOMINALBARANG, A.TOTALNOMINALONGKIR, A.TOTALNOMINALDISKON, A.TOTALNOMINALBAYAR");
        $this->from('t_transaksirekap AS A', true);
        $this->join('m_regional AS B', 'A.IDREGIONAL = B.IDREGIONAL', 'LEFT');
        $this->join('m_statustransaksi AS C', 'A.IDSTATUSTRANSAKSI = C.IDSTATUSTRANSAKSI', 'LEFT');
        $this->join('m_kanalpembayaran AS D', 'A.IDKANALPEMBAYARAN = D.IDKANALPEMBAYARAN', 'LEFT');
        $this->join('m_ekspedisi AS E', 'A.IDEKSPEDISI = E.IDEKSPEDISI', 'LEFT');
        $this->where('A.IDTRANSAKSIREKAP', $idTransaksiRekap);
        $this->limit(1);

        $result =   $this->get()->getRowArray();
        if(is_null($result)) return false;
        return $result;
	}

    public function getDataProdukTransaksi($idTransaksiRekap)
    {	
        $this->select("C.NAMAMERK, D.NAMAKATEGORI, B.NAMAPRODUK, B.ARRIMAGE, A.KETERANGAN, A.JUMLAH, A.NOMINALSATUAN, A.NOMINALTOTAL");
        $this->from('t_transaksibarang AS A', true);
        $this->join('t_produk AS B', 'A.IDPRODUK = B.IDPRODUK', 'LEFT');
        $this->join('m_merk AS C', 'B.IDMERK = C.IDMERK', 'LEFT');
        $this->join('m_kategori AS D', 'B.IDKATEGORI = D.IDKATEGORI', 'LEFT');
        $this->where('A.IDTRANSAKSIREKAP', $idTransaksiRekap);

        $result     =   $this->get()->getResultObject();

        if(is_null($result)) return false;
        return $result;
	}

    public function getDetailProdukById($idProduk)
    {	
        $this->select("NAMAPRODUK, DESKRIPSI, ARRIMAGE, HARGAJUAL");
        $this->from('t_produk', true);
        $this->where('IDPRODUK', $idProduk);
        $this->limit(1);

        $result =   $this->get()->getRowArray();
        if(is_null($result)) return false;
        return $result;
	}

    public function getDetailCustomerAlamatById($idCustomerAlamat)
    {	
        $this->select("NAMAALAMAT, CONCAT(ALAMAT, ' ', KELURAHAN, ' ', KECAMATAN, ' ', KOTA, ' ', PROVINSI) AS ALAMATKIRIM, NAMAPENERIMA, NOMORHPPENERIMA");
        $this->from('t_alamatpengiriman', true);
        $this->where('IDCUSTOMERALAMAT', $idCustomerAlamat);
        $this->limit(1);

        $result =   $this->get()->getRowArray();
        if(is_null($result)) return false;
        return $result;
	}
}