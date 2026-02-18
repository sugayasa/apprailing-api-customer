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

    public function getDataProduk($idMerk, $searchKeyword)
    {	
        $this->select("A.IDPRODUK, A.ARRIMAGE, A.NAMAPRODUK, A.HARGAJUAL, A.TOTALTERJUAL");
        $this->from('t_produk AS A', true);
        $this->join('m_merk AS B', 'A.IDMERK = B.IDMERK', 'LEFT');
        $this->join('m_kategori AS C', 'A.IDKATEGORI = C.IDKATEGORI', 'LEFT');
        $this->where('A.STATUS', 1);
        if(isset($idMerk) && $idMerk != 0)  $this->where('A.IDMERK', $idMerk);
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
        $this->select("IFNULL(B.NAMAMERK, '-') AS NAMAMERK, IFNULL(C.NAMAKATEGORI, '-') AS NAMAKATEGORI, A.NAMAPRODUK, A.DESKRIPSI, A.ARRIMAGE, A.HARGAJUAL, A.TOTALTERJUAL, '' AS DATASTOK");
        $this->from('t_produk AS A', true);
        $this->join('m_merk AS B', 'A.IDMERK = B.IDMERK', 'LEFT');
        $this->join('m_kategori AS C', 'A.IDKATEGORI = C.IDKATEGORI', 'LEFT');
        $this->where('A.IDPRODUK', $idProduk);
        $this->limit(1);

        $result =   $this->get()->getRowArray();
        if(is_null($result)) return false;
        return $result;
	}
}