<?php

namespace App\Models;

use CodeIgniter\Model;

class AccessModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'm_sessions';
    protected $primaryKey       = 'IDSESSION';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['IDCUSTOMER', 'PLATFORM', 'HARDWAREID', 'DATETIMELOGIN', 'DATETIMEACTIVITY', 'DATETIMEEXPIRED'];

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

    public function getDataCustomer($email, $phoneNumber)
    {
        $this->select('IDCUSTOMER, NAMA, EMAIL, NOMORHP');
        $this->from('m_customer', true);
        $this->groupStart();
        $this->where('EMAIL', $email);
        $this->orWhere('NOMORHP', $phoneNumber);
        $this->groupEnd();
        $this->where('STATUS', 1);

        return $this->get()->getRowArray();
    }

    public function getDetailCustomer($idCustomer)
    {
        $this->select('NAMA, EMAIL, NOMORHP');
        $this->from('m_customer', true);
        $this->where('IDCUSTOMER', $idCustomer);

        return $this->get()->getRowArray();
    }

    public function checkHardwareIDUserAdmin($idUserAdmin, $hardwareID)
    {
        $this->select('IDUSERADMIN');
        $this->from('m_useradmin', true);
        $this->where('IDUSERADMIN', $idUserAdmin);
        $this->where('HARDWAREID', $hardwareID);

        if(is_null($this->get()->getRowArray())) return false;
        return true;
    }

    public function getDataMerk()
    {
        $this->select('IDMERK AS ID, NAMAMERK AS VALUE');
        $this->from(APP_MAIN_DATABASE_NAME.'.m_merk', true);
        $this->orderBy('NAMAMERK');

        return $this->get()->getResultObject();
    }

    public function getDataBarangKategori()
    {
        $this->select('IDKATEGORIBARANG AS ID, KATEGORIBARANG AS VALUE');
        $this->from(APP_MAIN_DATABASE_NAME.'.m_barangkategori', true);
        $this->orderBy('KATEGORIBARANG');

        return $this->get()->getResultObject();
    }

    public function setLastActivityUserAdmin($idUserAdmin, $datetimeActivity)
    {
        $this->set('DATETIMEACTIVITY', $datetimeActivity);
        $this->where('IDUSERADMIN', $idUserAdmin);
        $this->update();
    }
}
