<?php

namespace App\Models;
use CodeIgniter\Model;

class KatalogBrosurModel extends Model
{
    protected $table            = 'm_merk';
    protected $primaryKey       = 'm_merk';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['NAMAMERK', 'PDFTHUMBNAIL', 'PDFFILE', 'STATUSKATALOG', 'STATUS'];

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

    public function getDataPDFMerkKatalog()
    {	
        $this->select("NAMAMERK, PDFTHUMBNAIL, PDFFILE");
        $this->from('m_merk', true);
        $this->where('STATUSKATALOG', 1);

        $result =   $this->get()->getResultObject();
        if(is_null($result)) return [];
        return $result;
	}
}
