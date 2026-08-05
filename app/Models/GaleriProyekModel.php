<?php

namespace App\Models;

use CodeIgniter\Model;

class GaleriProyekModel extends Model
{
    protected $table            = 't_galeriproyek';
    protected $primaryKey       = 'IDGALERIPROYEK';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['IDGALERIPROYEK', 'IDMERKUTAMA', 'NAMAKLIEN', 'ALAMATPROYEK', 'DESKRIPSI', 'URUTAN', 'IMAGE', 'INPUTUSER', 'INPUTTANGGALWAKTU', 'STATUS'];

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
    
    public function getDataGaleriProyek()
    {	
        $this->select('IDGALERIPROYEK, NAMAKLIEN, IMAGE');
        $this->from('t_galeriproyek', true);
        $this->where('STATUS', 1);
        $this->orderBy('URUTAN', 'DESC');
        
        return $this;
	}

    public function getDetailGaleriProyek($idGaleriProyek)
    {
        $this->select(
            "B.NAMAMERK, A.NAMAKLIEN, A.ALAMATPROYEK, A.DESKRIPSI, A.IMAGE"
        );
        $this->from('t_galeriproyek AS A', TRUE);
        $this->join('m_merk AS B', 'A.IDMERKUTAMA = B.IDMERK', 'LEFT');
        $this->where('IDGALERIPROYEK', $idGaleriProyek);
        $this->limit(1);

        return $this->get()->getRowArray();
    }
}
