<?php

namespace App\Models;
use CodeIgniter\Model;

class ProfileModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'm_customer';
    protected $primaryKey       = 'IDCUSTOMER';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['NAMA', 'EMAIL', 'NOMORHP', 'AVATAR', 'STATUS'];

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

    public function getDetailProfile($idCustomer)
    {	
        $this->select("B.ROYALTITIER, B.DESKRIPSI AS ROYALTIDESKRIPSI, IFNULL(COUNT(C.IDTRANSAKSIREKAP), 0) AS JUMLAHTRANSAKSI,
                    SUM(D.JUMLAH) AS TOTALITEMBARANG, B.MINIMALNOMINALPEMBELIAN");
        $this->from('m_customer AS A', true);
        $this->join('m_customerroyalti AS B', 'A.IDCUSTOMERROYALTI = B.IDCUSTOMERROYALTI', 'LEFT');
        $this->join('t_transaksirekap AS C', 'A.IDCUSTOMER = C.IDCUSTOMER AND C.IDCUSTOMER = '.$idCustomer.' AND C.ISPESANANSELESAI = 1', 'LEFT');
        $this->join('t_transaksibarang AS D', 'C.IDTRANSAKSIREKAP = D.IDTRANSAKSIREKAP', 'LEFT');
        $this->where('A.IDCUSTOMER', $idCustomer);
        $this->groupBy('A.IDCUSTOMER');
        $this->limit(1);

        $result     =   $this->get()->getRowArray();

        if(is_null($result)) return [
            "ROYALTITIER"      =>  "-",
            "ROYALTIDESKRIPSI" =>  "Anda baru terdaftar sebagai customer kami"
        ];
        return $result;
	}   

    public function getDataAlamat($idCustomer)
    {	
        $this->select("IDCUSTOMERALAMAT, NAMAALAMAT, NAMAPENERIMA, NOMORHPPENERIMA, ALAMAT, KELURAHAN, KECAMATAN, KOTA,
                    PROPINSI, NOMORHPPENERIMA, ISALAMATUTAMA");
        $this->from('m_customeralamat', true);
        $this->where('IDCUSTOMER', $idCustomer);

        $result     =   $this->get()->getResultObject();

        if(is_null($result)) return false;
        return $result;
	}
}