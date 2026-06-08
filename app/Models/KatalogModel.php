<?php

namespace App\Models;
use CodeIgniter\Model;

class KatalogModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 't_produk';
    protected $primaryKey       = 'IDPRODUK';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['IDBARANG', 'IDMERK', 'IDKATEGORI', 'NAMAPRODUK', 'DESKRIPSI', 'ARRIMAGE', 'HARGAJUAL', 'TOTALTERJUAL', 'INPUTUSER', 'INPUTTANGGALWAKTU', 'STATUS'];

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

    public function getDataProduk($idMerk, $arrIdBarangKategori, $searchKeyword)
    {	
        $this->select("A.IDPRODUK, A.ARRIMAGE, A.NAMAPRODUK, A.HARGAJUAL, A.TOTALTERJUAL");
        $this->from('t_produk AS A', true);
        $this->join('m_merk AS B', 'A.IDMERK = B.IDMERK', 'LEFT');
        $this->join('m_kategori AS C', 'A.IDKATEGORI = C.IDKATEGORI', 'LEFT');
        $this->where('A.STATUS', 1);
        if(isset($idMerk) && $idMerk != 0)  $this->where('A.IDMERK', $idMerk);
        if(isset($arrIdBarangKategori) && is_array($arrIdBarangKategori) && count($arrIdBarangKategori) > 0) $this->whereIn('A.IDKATEGORI', $arrIdBarangKategori);
        if(isset($searchKeyword) && $searchKeyword != "") {
            $this->groupStart();
            $this->like('A.NAMAPRODUK', $searchKeyword);
            $this->orLike('A.DESKRIPSI', $searchKeyword);
            $this->orLike('B.NAMAMERK', $searchKeyword);
            $this->orLike('C.NAMAKATEGORI', $searchKeyword);
            $this->groupEnd();
        }
        return $this;
	}

    public function getDetailProduk($idProduk)
    {	
        $this->select("A.IDBARANG, IFNULL(B.NAMAMERK, '-') AS NAMAMERK, IFNULL(C.NAMAKATEGORI, '-') AS NAMAKATEGORI, A.NAMAPRODUK, A.DESKRIPSI,
                    A.ARRIMAGE, A.HARGAJUAL, A.TOTALTERJUAL, '' AS DATASTOK");
        $this->from('t_produk AS A', true);
        $this->join('m_merk AS B', 'A.IDMERK = B.IDMERK', 'LEFT');
        $this->join('m_kategori AS C', 'A.IDKATEGORI = C.IDKATEGORI', 'LEFT');
        $this->where('A.IDPRODUK', $idProduk);
        $this->limit(1);

        $result =   $this->get()->getRowArray();
        if(is_null($result)) return false;
        return $result;
	}

    public function getTotalStokProdukByRegional($namaDatabase, $idBarang)
    {	
        $subQueryB  =   $this->db->table($namaDatabase.'.t_slotbarang');
        $subQueryB->select('IDBARANG, TOTALBARANG, TIPETRANSAKSI');
        $subQueryB->whereIn('TIPETRANSAKSI', [3,4]);
        $subQueryB->where('IDBARANG', $idBarang);
        $subQueryB  =   $subQueryB->getCompiledSelect();
        
        $subQueryC  =   $this->db->table($namaDatabase.'.t_salesorderdetail');
        $subQueryC->select('IDBARANG, SUM(JUMLAHPCS) AS STOKSOTERTAHAN');
        $subQueryC->where('STATUS', 0);
        $subQueryC->where('IDBARANG', $idBarang);
        $subQueryC->groupBy('IDBARANG');
        $subQueryC  =   $subQueryC->getCompiledSelect();
        
        $subQueryD  =   $this->db->table($namaDatabase.'.t_suratjalanitem AS DA');
        $subQueryD->select('DA.IDBARANG, SUM(DA.JUMLAH) AS STOKBELUMKIRIM');
        $subQueryD->join($namaDatabase.'.t_suratjalan AS DB', 'DA.IDSURATJALAN = DB.IDSURATJALAN', 'LEFT');
        $subQueryD->where('DB.STATUS', 0);
        $subQueryD->where('DA.IDBARANG', $idBarang);
        $subQueryD->groupBy('DA.IDBARANG');
        $subQueryD  =   $subQueryD->getCompiledSelect();
        
        $subQueryE  =   $this->db->table($namaDatabase.'.t_slotbarangreject');
        $subQueryE->select('IDBARANG, SUM(JUMLAHBARANG) AS STOKBARANGREJECT');
        $subQueryE->where('IDBARANG', $idBarang);
        $subQueryE->groupBy('IDBARANG');
        $subQueryE  =   $subQueryE->getCompiledSelect();

        $builder = $this->db->table(APP_MAIN_DATABASE_NAME.'.m_barang A');
        $builder->select("IFNULL(SUM(IF(B.TIPETRANSAKSI = 1 OR B.TIPETRANSAKSI = 3, B.TOTALBARANG, B.TOTALBARANG * -1)),0) AS STOKFISIK,
                       IFNULL(C.STOKSOTERTAHAN, 0) AS STOKSOTERTAHAN, 
                       IFNULL(D.STOKBELUMKIRIM, 0) AS STOKBELUMKIRIM, 
                       IFNULL(E.STOKBARANGREJECT, 0) AS STOKBARANGREJECT");
        $builder->join('(' . $subQueryB . ') AS B', 'A.IDBARANG = B.IDBARANG', 'LEFT', false);
        $builder->join('(' . $subQueryC . ') AS C', 'A.IDBARANG = C.IDBARANG', 'LEFT', false);
        $builder->join('(' . $subQueryD . ') AS D', 'A.IDBARANG = D.IDBARANG', 'LEFT', false);
        $builder->join('(' . $subQueryE . ') AS E', 'A.IDBARANG = E.IDBARANG', 'LEFT', false);
        $builder->where('A.IDBARANG', $idBarang);
        $builder->groupBy('A.IDBARANG');
        $builder->limit(1);

        $result = $builder->get()->getRowArray();
        if(is_null($result)) return 0;

        $stokFisik        =   isset($result['STOKFISIK']) ? $result['STOKFISIK'] : 0;
        $stokSoterTertahan=   isset($result['STOKSOTERTAHAN']) ? $result['STOKSOTERTAHAN'] : 0;
        $stokBelumKirim   =   isset($result['STOKBELUMKIRIM']) ? $result['STOKBELUMKIRIM'] : 0;
        $stokBarangReject =   isset($result['STOKBARANGREJECT']) ? $result['STOKBARANGREJECT'] : 0;
        $totalStok        =   ($stokFisik - $stokSoterTertahan - $stokBelumKirim - $stokBarangReject);
        return $totalStok;
	}
}