<?php

namespace App\Models;

use CodeIgniter\Model;

class ScanQRModel extends Model
{
    protected $table            = 'm_customer';
    protected $primaryKey       = 'IDCUSTOMER';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['IDCUSTOMER', 'NAMA', 'KODEUNIK'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

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

    public function getDetailCustomer($idCustomer)
    {	
        $this->select("NAMA, KODEUNIK");
        $this->from('m_customer', true);
        $this->where('IDCUSTOMER', $idCustomer);
        $this->limit(1);

        $result =   $this->get()->getRowArray();
        if(is_null($result)) return [
            "NAMA"      =>  "-",
            "KODEUNIK"  =>  "-"
        ];
        return $result;
	}
}