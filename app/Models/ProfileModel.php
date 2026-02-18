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

    public function getDataAlamat($idCustomer)
    {	
        $this->select("IDALAMAT, NAMAALAMAT, NAMAPENERIMA, ALAMAT, KELURAHAN, KECAMATAN, KOTA, PROPINSI, NOMOR_TELEPON");
        $this->from('m_customeralamat', true);
        $this->where('IDCUSTOMER', $idCustomer);

        $result     =   $this->get()->getResultObject();

        if(is_null($result)) return false;
        return $result;
	}
}