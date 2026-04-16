<?php

namespace App\Models;
use CodeIgniter\Model;

class DashboardModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 't_slidebanner';
    protected $primaryKey       = 'IDSLIDEBANNER';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['IDSLIDEBANNER', 'JUDUL', 'DESKRIPSI', 'IMAGE', 'INPUTUSER', 'INPUTTANGGALWAKTU', 'STATUS'];

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
    
    public function getDataSlideBanner()
    {	
        $this->select('IDSLIDEBANNER, IMAGE');
        $this->from('t_slidebanner', true);
        $this->where('STATUS', 1);
        $this->orderBy('INPUTTANGGALWAKTU', 'DESC');
        $this->limit(8);

        $result =   $this->get()->getResultObject();
        if(is_null($result)) return [];
        return $result;
	}
    
    public function getDataMerk()
    {	
        $this->select('IDMERK, NAMAMERK, LOGO');
        $this->from('m_merk', true);
        $this->where('STATUS', 1);

        $result =   $this->get()->getResultObject();
        if(is_null($result)) return [];
        return $result;
	}
    
    public function getDataOrderTerakhir($idCustomer)
    {	
        $this->select('A.IDTRANSAKSIREKAP, B.STATUSTRANSAKSI, DATE_FORMAT(A.INPUTTANGGALWAKTU, "%d %M %Y %H:%i") AS TANGGALORDER,
                        A.TOTALBARANG, A.NOMORTRANSAKSI, A.TOTALNOMINALBAYAR');
        $this->from('t_transaksirekap AS A', true);
        $this->join('m_statustransaksi AS B', 'A.IDSTATUSTRANSAKSI = B.IDSTATUSTRANSAKSI', 'LEFT');
        $this->where('STATUS', 1);
        $this->where('IDCUSTOMER', $idCustomer);
        $this->orderBy('INPUTTANGGALWAKTU', 'DESC');
        $this->limit(5);

        $result =   $this->get()->getResultObject();
        if(is_null($result)) return false;
        return $result;
	}
    
    public function getDataBarangOrder($idTransaksiRekap)
    {	
        $this->select('B.ARRIMAGE');
        $this->from('t_transaksibarang AS A', true);
        $this->join('t_produk AS B', 'A.IDPRODUK = B.IDPRODUK', 'LEFT');
        $this->where('A.IDTRANSAKSIREKAP', $idTransaksiRekap);

        $result =   $this->get()->getResultObject();
        if(is_null($result)) return false;
        return $result;
	}

    public function getDetailSlideBanner($idSlideBanner)
    {
        $this->select('KONTEN');
        $this->from('t_slidebanner', true);
        $this->where('IDSLIDEBANNER', $idSlideBanner);
        $this->limit(1);

        return $this->get()->getRowArray();
    }
}