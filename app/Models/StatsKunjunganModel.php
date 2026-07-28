<?php

namespace App\Models;

use CodeIgniter\Model;

class StatsKunjunganModel extends Model
{
    protected $table            = 'stats_kunjungan';
    protected $primaryKey       = 'IDSTATSKUNJUNGAN';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['IDSTATSKUNJUNGAN', 'IDCUSTOMER', 'HARDWAREID', 'TANGGAL', 'WAKTUAWAL', 'WAKTUAKHIR', 'TOTALWAKTU', 'ISPROSESDAFTAR'];

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

    public function insertLogKunjungan($idCustomer, $hardwareID, $currentDateTime, $urlSegment2)
    {
        $currentDate    =   date('Y-m-d', strtotime($currentDateTime));
        $currentTime    =   date('H:i:s', strtotime($currentDateTime));

        $existingRecord =   $this->where('TANGGAL', $currentDate)
            ->where('HARDWAREID', $hardwareID)
            ->first();

        if ($existingRecord) {
            $totalWaktu =   strtotime($currentTime) - strtotime($existingRecord['WAKTUAWAL']);
            $this->update($existingRecord['IDSTATSKUNJUNGAN'], [
                'IDCUSTOMER'    =>  $idCustomer,
                'WAKTUAKHIR'    =>  $currentTime,
                'TOTALWAKTU'    =>  $totalWaktu,
                'ISPROSESDAFTAR'=>  ($urlSegment2 === 'registerSubmitOTP') ? true : $existingRecord['ISPROSESDAFTAR'],
            ]);
        } else {
            $this->insert([
                'IDCUSTOMER'    =>  $idCustomer,
                'HARDWAREID'    =>  $hardwareID,
                'TANGGAL'       =>  $currentDate,
                'WAKTUAWAL'     =>  $currentTime,
                'WAKTUAKHIR'    =>  $currentTime,
                'TOTALWAKTU'    =>  0,
                'ISPROSESDAFTAR'=>  ($urlSegment2 === 'registerSubmitOTP') ? true : false,
            ]);
        }
    }
}
