<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\ProfileModel;
use App\Models\MainOperation;

class Profile extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    use ResponseTrait;
    protected $userData, $currentDateTime;
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
        parent::initController($request, $response, $logger);

        try {
            $this->userData         =   $request->userData;
            $this->currentDateTime  =   $request->currentDateTime;
        } catch (\Throwable $th) {
        }
    }

    public function index()
    {
        return $this->failForbidden('[E-AUTH-000] Forbidden Access');
    }

    public function getDetailProfile()
    {
        $idCustomer     =   $this->userData->idCustomer;
        $isRegistered   =   $idCustomer != 0 ? true : false;
        $profileData    =   [
            "avatar"    =>  $this->userData->avatar,
            "nama"      =>  $this->userData->nama,
            "kota"      =>  $this->userData->kota,
            "propinsi"  =>  $this->userData->propinsi
        ];

        $royaltiLevel   =   [
            "royaltiTier"       =>  "-",
            "royaltiDeskripsi"  =>  "-"
        ];

        $statistikTransaksi =   [
            "totalTransaksiSelesai" =>  0,
            "totalItemBarang"       =>  "-",
            "totalNominalBeli"      =>  "-"
        ];

        $informasiPribadi   =   [
            "namaCustomer"  =>  "-",
            "email"         =>  "-",
            "nomorTelepon"  =>  "-"
        ];

        $informasiAlamat    =   [
            "namaAlamat"        =>  "-",
            "namaPenerima"      =>  "-",
            "alamatDetail"      =>  "-",
            "kelurahan"         =>  "-",
            "kecamatan"         =>  "-",
            "kotaKabupaten"     =>  "-",
            "propinsi"          =>  "-",
            "nomorTelepon"      =>  "-",
            "totalAlamatLain"   =>  0
        ];

        if($isRegistered){
            $profileModel   =   new ProfileModel();
            $profileData    =   $profileModel->getDetailProfile($idCustomer);
            $royaltiLevel   =   [
                "royaltiTier"       =>  $profileData['ROYALTITIER'] ?? "-",
                "royaltiDeskripsi"  =>  $profileData['ROYALTIDESKRIPSI'] ?? "Anda baru terdaftar sebagai customer kami"
            ];

            $strTotalItemBarang     =   '0';
            $strTotalNominalBeli    =   '-';
            $totaItemBarangDB       =   $profileData['TOTALITEMBARANG'] ?? 0;
            $minimalNomimalBeliDB   =   $profileData['MINIMALNOMINALPEMBELIAN'] ?? 0;

            switch (true) {
                case ($totaItemBarangDB > 1000) :   $strTotalItemBarang =   '1K+'; break;
                case ($totaItemBarangDB > 500)  :   $strTotalItemBarang =   '500+'; break;
                case ($totaItemBarangDB > 200)  :   $strTotalItemBarang =   '200+'; break;
                case ($totaItemBarangDB > 100)  :   $strTotalItemBarang =   '100+'; break;
                case ($totaItemBarangDB > 50)   :   $strTotalItemBarang =   '50+'; break;
                case ($totaItemBarangDB > 20)   :   $strTotalItemBarang =   '20+'; break;
                case ($totaItemBarangDB > 5)    :   $strTotalItemBarang =   '5+'; break;
                default                         :   $strTotalItemBarang =   $totaItemBarangDB;
            }

            if(!is_null($profileData['MINIMALNOMINALPEMBELIAN']) && $profileData['MINIMALNOMINALPEMBELIAN'] > 0) {
                switch (true) {
                    case ($minimalNomimalBeliDB > 500000000):   $strTotalNominalBeli =   '500Jt+'; break;
                    case ($minimalNomimalBeliDB > 200000000):   $strTotalNominalBeli =   '200Jt+'; break;
                    case ($minimalNomimalBeliDB > 100000000):   $strTotalNominalBeli =   '100Jt+'; break;
                    case ($minimalNomimalBeliDB > 50000000) :   $strTotalNominalBeli =   '50Jt+'; break;
                    case ($minimalNomimalBeliDB > 20000000) :   $strTotalNominalBeli =   '20Jt+'; break;
                    case ($minimalNomimalBeliDB > 10000000) :   $strTotalNominalBeli =   '10Jt+'; break;
                    case ($minimalNomimalBeliDB > 5000000)  :   $strTotalNominalBeli =   '5Jt+'; break;
                    case ($minimalNomimalBeliDB > 2000000)  :   $strTotalNominalBeli =   '2Jt+'; break;
                    case ($minimalNomimalBeliDB > 1000000)  :   $strTotalNominalBeli =   '1Jt+'; break;
                    default                                 :   $strTotalNominalBeli =   '~1Jt';
                }
            }

            $statistikTransaksi     =   [
                "totalTransaksiSelesai" =>  $profileData['JUMLAHTRANSAKSI'] ?? 0,
                "totalItemBarang"       =>  $strTotalItemBarang,
                "totalNominalBeli"      =>  $strTotalNominalBeli
            ];

            $informasiPribadi   =   [
                "namaCustomer"  =>  $this->userData->nama,
                "email"         =>  $this->userData->email,
                "nomorTelepon"  =>  $this->userData->nomorHP
            ];

            $dataAlamat         =   $profileModel->getDataAlamat($idCustomer);
            $informasiAlamat    =   [
                "namaAlamat"        =>  "-",
                "namaPenerima"      =>  "-",
                "alamatDetail"      =>  "-",
                "kelurahan"         =>  "-",
                "kecamatan"         =>  "-",
                "kotaKabupaten"     =>  "-",
                "propinsi"          =>  "-",
                "nomorTelepon"      =>  "-",
                "totalAlamatLain"   =>  0
            ];

            if($dataAlamat){
                $totalAlamatLain    =   count($dataAlamat) - 1;
                foreach ($dataAlamat as $keyAlamat) {
                    if($keyAlamat['ISALAMATUTAMA'] == 1){
                        $informasiAlamat    =   [
                            "namaAlamat"        =>  $keyAlamat['NAMAALAMAT'] ?? "Alamat Utama",
                            "namaPenerima"      =>  $keyAlamat['NAMAPENERIMA'] ?? "-",
                            "alamatDetail"      =>  $keyAlamat['ALAMAT'] ?? "-",
                            "kelurahan"         =>  $keyAlamat['KELURAHAN'] ?? "-",
                            "kecamatan"         =>  $keyAlamat['KECAMATAN'] ?? "-",
                            "kotaKabupaten"     =>  $keyAlamat['KOTA'] ?? "-",
                            "propinsi"          =>  $keyAlamat['PROPINSI'] ?? "-",
                            "nomorTelepon"      =>  $keyAlamat['NOMORHPPENERIMA'] ?? "-",
                            "totalAlamatLain"   =>  $totalAlamatLain
                        ];
                    }
                }
            }
        }

        return $this->setResponseFormat('json')->respond([
            "isRegistered"          =>  $isRegistered,
            "profileData"           =>  $profileData,
            "royaltiLevel"          =>  $royaltiLevel,
            "statistikTransaksi"    =>  $statistikTransaksi,
            "informasiPribadi"      =>  $informasiPribadi,
            "informasiAlamat"       =>  $informasiAlamat
        ]);
    }

    public function getDataAlamat()
    {
        $idCustomer     =   $this->userData->idCustomer;
        $isRegistered   =   $idCustomer != 0 ? true : false;
        
        if(!$isRegistered) return throwResponseForbidden('Anda tidak memiliki akses, harap masuk atau registrasi terlebih dahulu');
        $profileModel   =   new ProfileModel();
        $dataAlamat     =   $profileModel->getDataAlamat($idCustomer);
        
        if(!$dataAlamat) return throwResponseNotFound('Data Alamat Tidak Ditemukan');
        $dataAlamat     =   encodeDatabaseObjectResultKey($dataAlamat, ['IDCUSTOMERALAMAT']);

        return $this->setResponseFormat('json')->respond([
            "dataAlamat"    =>  $dataAlamat
        ]);
    }

    public function saveDataAlamat()
    {
        $idCustomer     =   $this->userData->idCustomer;
        $isRegistered   =   $idCustomer != 0 ? true : false;
        
        if(!$isRegistered) return throwResponseForbidden('Anda tidak memiliki akses, harap masuk atau registrasi terlebih dahulu');

        $idCustomerAlamat   =   $this->request->getVar('idCustomerAlamat');
        $idCustomerAlamat   =   isset($idCustomerAlamat) && $idCustomerAlamat != "" ? hashidDecode($idCustomerAlamat) : 0;
        $validation         =   $idCustomerAlamat == 0 ? $this->parametersValidatorAlamatCustomer() : $this->parametersValidatorAlamatCustomer(true);

        if($validation !== true) return $this->fail($validation);
        return $idCustomerAlamat == 0 ? $this->insertDataAlamatCustomer($idCustomer) : $this->updateDataAlamatCustomer($idCustomerAlamat, $idCustomer);
    }

    private function insertDataAlamatCustomer($idCustomer)
    {
        $mainOperation  =   new MainOperation();
        $arrInsertData  =   $this->generateArrayInsertUpdateAlamatCustomer($idCustomer);
        $procInsertData =   $mainOperation->insertDataTable('m_customeralamat', $arrInsertData);

        if(!$procInsertData['status']) return switchMySQLErrorCode($procInsertData['errCode']);
        return throwResponseOK(
            'Data alamat telah disimpan'
        );
    }

    private function updateDataAlamatCustomer($idCustomerAlamat, $idCustomer)
    {
        $mainOperation  =   new MainOperation();
        $arrUpdateData  =   $this->generateArrayInsertUpdateAlamatCustomer($idCustomer);
        $procUpdateData =   $mainOperation->updateDataTable('m_customeralamat', $arrUpdateData, ['IDCUSTOMERALAMAT' => $idCustomerAlamat]);

        if(!$procUpdateData['status']) return switchMySQLErrorCode($procUpdateData['errCode']);
        return throwResponseOK(
            'Data alamat telah diperbarui'
        );
    }

    private function parametersValidatorAlamatCustomer($isUpdate = false)
    {
        $rules  =   [
            'namaAlamat'        =>  ['label' => 'Nama Alamat', 'rules' => 'required|regex_match[/^[a-zA-Z0-9\s\p{P}]+$/u]|min_length[5]|max_length[100]'],
            'namaPenerima'      =>  ['label' => 'Nama Penerima', 'rules' => 'required|regex_match[/^[a-zA-Z0-9\s\p{P}]+$/u]|min_length[3]|max_length[50]'],
            'nomorHPPenerima'   =>  ['label' => 'Nomor HP Penerima', 'rules' => 'required|numeric|min_length[10]|max_length[16]'],
            'alamat'            =>  ['label' => 'Alamat', 'rules' => 'required|regex_match[/^[a-zA-Z0-9\s\p{P}]+$/u]|min_length[5]|max_length[150]'],
            'kodePOS'           =>  ['label' => 'Kode POS', 'rules' => 'required|numeric|min_length[5]|max_length[10]'],
            'kelurahan'         =>  ['label' => 'Kelurahan', 'rules' => 'required|alpha_numeric_punct|min_length[2]|max_length[100]'],
            'kecamatan'         =>  ['label' => 'Kecamatan', 'rules' => 'required|alpha_numeric_punct|min_length[2]|max_length[100]'],
            'kota'              =>  ['label' => 'Kota', 'rules' => 'required|alpha_numeric_punct|min_length[2]|max_length[100]'],
            'propinsi'          =>  ['label' => 'Propinsi', 'rules' => 'required|alpha_numeric_punct|min_length[2]|max_length[50]'],
            'alamatUtama'       =>  ['label' => 'Alamat Utama', 'rules' => 'required|in_list[0,1]'],
        ];

        $messages   =   [
            'alamatUtama'  => [
                'in_list'   => 'Harap tentukan apakah alamat ini akan dijadikan alamat utama atau tidak'
            ]
        ];

        if($isUpdate) {
            $rules['idCustomerAlamat']['rules']             =   'required|alpha_numeric';
            $messages['idCustomerAlamat']['required']       =   'Data kiriman tidak lengkap, silakan periksa kembali';
            $messages['idCustomerAlamat']['alpha_numeric']  =   'Data kiriman tidak lengkap, silakan periksa kembali';
        }

        if(!$this->validate($rules, $messages)) return $this->validator->getErrors();
        return true;
    }

    private function generateArrayInsertUpdateAlamatCustomer($idCustomer = 0): array
    {
        $namaAlamat         =   $this->request->getVar('namaAlamat');
        $namaPenerima       =   $this->request->getVar('namaPenerima');
        $nomorHPPenerima    =   $this->request->getVar('nomorHPPenerima');
        $alamat             =   $this->request->getVar('alamat');
        $kodePOS            =   $this->request->getVar('kodePOS');
        $kelurahan          =   $this->request->getVar('kelurahan');
        $kecamatan          =   $this->request->getVar('kecamatan');
        $kota               =   $this->request->getVar('kota');
        $propinsi           =   $this->request->getVar('propinsi');
        $alamatUtama        =   $this->request->getVar('alamatUtama');

        $arrInsertUpdateData    =   [
            'IDCUSTOMER'        =>  $idCustomer,
            'NAMAALAMAT'        =>  $namaAlamat,
            'NAMAPENERIMA'      =>  $namaPenerima,
            'NOMORHPPENERIMA'   =>  $nomorHPPenerima,
            'ALAMAT'            =>  $alamat,
            'KODEPOS'           =>  $kodePOS,
            'KELURAHAN'         =>  $kelurahan,
            'KECAMATAN'         =>  $kecamatan,
            'KOTA'              =>  $kota,
            'PROPINSI'          =>  $propinsi,
            'ISALAMATUTAMA'     =>  $alamatUtama
        ];

        return $arrInsertUpdateData;
    }
}