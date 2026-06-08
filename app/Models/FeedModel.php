<?php

namespace App\Models;
use CodeIgniter\Model;

class FeedModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 't_feed';
    protected $primaryKey       = 'IDFEED';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['URLFEED', 'JUDUL', 'DESKRIPSI', 'TOTALSUKA', 'TOTALSIMPAN', 'INPUTUSER', 'INPUTTANGGALWAKTU'];

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

    public function getDataFeed($idCustomer, $isBookmark = false)
    {	
        $this->select("A.IDFEED, A.URLFEED, A.JUDUL, A.DESKRIPSI, A.TOTALSUKA, A.TOTALSIMPAN,
                    IF(B.IDFEEDSUKA IS NOT NULL, 1, 0) AS ISSUKA, IF(C.IDFEEDBOOKMARK IS NOT NULL, 1, 0) AS ISBOOKMARK");
        $this->from('t_feed AS A', true);
        $this->join('t_feedsuka AS B', 'A.IDFEED = B.IDFEED AND B.IDCUSTOMER = '.$idCustomer, 'LEFT');
        $this->join('t_feedbookmark AS C', 'A.IDFEED = C.IDFEED AND C.IDCUSTOMER = '.$idCustomer, 'LEFT');
        
        if($isBookmark) {
            $this->where('C.IDFEEDBOOKMARK IS NOT NULL');
        }
        return $this;
	}
}