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
        $this->select('A.AVATAR, A.NAMA, A.EMAIL, A.NOMORHP, B.ALAMAT, B.KOTA, B.PROPINSI');
        $this->from('m_customer AS A', true);
        $this->join('m_customeralamat AS B', 'A.IDCUSTOMER = B.IDCUSTOMER AND B.ISALAMATUTAMA = 1', 'LEFT');
        $this->where('A.IDCUSTOMER', $idCustomer);
        $this->groupBy('A.IDCUSTOMER');

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

    public function getDataRegional()
    {
        $this->select('IDREGIONAL AS ID, NAMAREGIONAL AS VALUE');
        $this->from('m_regional', true);
        $this->orderBy('NAMAREGIONAL');

        return $this->get()->getResultObject();
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

    public function getDataEkspedisi()
    {
        $this->select('IDEKSPEDISI AS ID, NAMAEKSPEDISI AS VALUE');
        $this->from('m_ekspedisi', true);
        $this->orderBy('NAMAEKSPEDISI');

        return $this->get()->getResultObject();
    }

    public function getDataKanalPembayaran()
    {
        $this->select('IDKANALPEMBAYARAN AS ID, NAMAKANALPEMBAYARAN AS VALUE');
        $this->from('m_kanalpembayaran', true);
        $this->orderBy('NAMAKANALPEMBAYARAN');

        return $this->get()->getResultObject();
    }

    public function getDataStatusTransaksi()
    {
        $this->select('IDSTATUSTRANSAKSI AS ID, STATUSTRANSAKSI AS VALUE');
        $this->from('m_statustransaksi', true);
        $this->orderBy('STATUSTRANSAKSI');

        return $this->get()->getResultObject();
    }

    public function setLastActivityUserAdmin($idUserAdmin, $datetimeActivity)
    {
        $this->set('DATETIMEACTIVITY', $datetimeActivity);
        $this->where('IDUSERADMIN', $idUserAdmin);
        $this->update();
    }
}
