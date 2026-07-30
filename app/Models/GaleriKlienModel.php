<?php

namespace App\Models;
use CodeIgniter\Model;

class GaleriKlienModel extends Model
{
    protected $table            = 't_galeriklien';
    protected $primaryKey       = 'IDGALERIKLIEN';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['IDGALERIKLIEN', 'IDKLIEN', 'IDMERKUTAMA', 'DESKRIPSI', 'IMAGE', 'INPUTUSER', 'INPUTTANGGALWAKTU'];

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

    public function getDataKlien()
    {	
        $this->select("A.IDKLIEN, A.NAMAKLIEN, A.LOGO");
        $this->from('m_klien AS A', TRUE);
        $this->join('t_galeriklien AS B', 'A.IDKLIEN = B.IDKLIEN', 'LEFT');
        $this->where('A.STATUS', 1);
        $this->groupBy('A.IDKLIEN');
        $this->groupBy('A.NAMAKLIEN');
        $this->groupBy('A.LOGO');
        $this->having('COUNT(B.IDKLIEN) > 0');

        return $this;
	}

    public function getDetailGaleriKlien($idKlien)
    {	
        $this->select(
            "B.NAMAMERK, CONCAT('".BASE_URL_ASSETS_CUSTOMER_MERK."', B.LOGO) AS LOGOMERK, A.DESKRIPSI, A.IMAGE"
        );
        $this->from('t_galeriklien AS A', TRUE);
        $this->join('m_merk AS B', 'A.IDMERKUTAMA = B.IDMERK', 'LEFT');
        $this->where('A.IDKLIEN', $idKlien);
        $this->orderBy('B.NAMAMERK', 'ASC');

        return $this->get()->getResultObject();
	}
}
