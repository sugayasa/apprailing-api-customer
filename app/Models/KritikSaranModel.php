<?php

namespace App\Models;
use CodeIgniter\Model;

class KritikSaranModel extends Model
{
    protected $table            = 't_kritiksaran';
    protected $primaryKey       = 'IDKRITIKSARAN';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['IDKRITIKSARAN', 'IDCUSTOMER', 'SUBYEK', 'PESAN', 'INPUTTANGGALWAKTU'];

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

    public function getDataKritikSaran($idCustomer)
    {	
        $this->select("SUBYEK, PESAN, DATE_FORMAT(INPUTTANGGALWAKTU, '%d %m %Y %H:%i') AS INPUTTANGGALWAKTUSTR");
        return $this;
	}

    public function detailKritikSaran($idCustomer, $subyek)
    {
        $this->select("SUBYEK, DATE_FORMAT(INPUTTANGGALWAKTU, '%d %m %Y') AS TANGGALSTR, DATE(INPUTTANGGALWAKTU) AS TANGGAL");
        $this->from('t_kritiksaran', TRUE);
        $this->where('IDCUSTOMER', $idCustomer);
        $this->groupStart();
            $this->where('SUBYEK', $subyek);
            $this->where('DATE(INPUTTANGGALWAKTU) = ', date('Y-m-d'));
        $this->groupEnd();
        $this->orderBy('INPUTTANGGALWAKTU', 'DESC');
        $this->limit(1);

        $result =   $this->get()->getRowArray();
        if(!empty($result) && !is_null($result)) return $result;
        return false;
    }
}