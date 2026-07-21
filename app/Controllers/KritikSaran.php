<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\KritikSaranModel;
use App\Models\MainOperation;

class KritikSaran extends ResourceController
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

    public function getDataKritikSaran()
    {
        $rules      =   [
            'page'          =>  ['label' => 'Page', 'rules' => 'required|numeric'],
            'dataPerPage'   =>  ['label' => 'Data Per Page', 'rules' => 'required|numeric']
        ];

        $messages   =   [
            'page'  => [
                'required'=> 'Invalid data sent - Page is required',
                'numeric' => 'Invalid data sent - Page must be a number'
            ],
            'dataPerPage'  => [
                'required'=> 'Invalid data sent - Data Per Page is required',
                'numeric' => 'Invalid data sent - Data Per Page must be a number'
            ]
        ];

        if(!$this->validate($rules, $messages)) return $this->fail($this->validator->getErrors());

        $kritikSaranModel   =   new KritikSaranModel();
        $mainOperation      =   new MainOperation();

        $idCustomer         =   $this->userData->idCustomer;
        $page               =   $this->request->getVar('page');
        $dataPerPage        =   $this->request->getVar('dataPerPage');
        $baseData           =	$kritikSaranModel->getDataKritikSaran($idCustomer);
        $totalNumberData    =   $baseData->countAllResults(false);
        $pageProperty       =   $mainOperation->generatePageProperty($page, $dataPerPage, $totalNumberData);

        if($totalNumberData > 0){
            $dataKritikSaran   =   $baseData->orderBy('INPUTTANGGALWAKTU DESC')->asObject()->findAll($dataPerPage, ($page - 1) * $dataPerPage);
            $dataKritikSaran   =   encodeDatabaseObjectResultKey($dataKritikSaran, ['IDKRITIKSARAN']);

            return $this->setResponseFormat('json')->respond([
                "dataKritikSaran"   =>  $dataKritikSaran,
                "pageProperty"      =>  $pageProperty
            ]);
        } else {
            $dataReturn =   [
                "dataKritikSaran"   =>  [],
                "pageProperty"      =>  $pageProperty
            ];
            return throwResponseNotFound('Tidak ada data yang ditemukan', $dataReturn);
        }
    }

    public function saveKritikSaran()
    {
        $rules      =   [
            'subyek'    =>  ['label' => 'Subyek', 'rules' => 'required|string|min_length[5]|max_length[100]'],
            'pesan'     =>  ['label' => 'Pesan', 'rules' => 'required|string|min_length[20]']
        ];

        $messages   =   [];

        if(!$this->validate($rules, $messages)) return $this->fail($this->validator->getErrors());

        $kritikSaranModel   =   new KritikSaranModel();
        $mainOperation      =   new MainOperation();

        $idCustomer         =   $this->userData->idCustomer;
        $subyek             =   $this->request->getVar('subyek');
        $pesan              =   $this->request->getVar('pesan');
        $isKritikSaranExist =   $kritikSaranModel->detailKritikSaran($idCustomer, $subyek);

        if($isKritikSaranExist) {
            $tanggalKritikSaranDB   =   $isKritikSaranExist['TANGGAL'];
            $subyekKritikSaranDB    =   $isKritikSaranExist['SUBYEK'];

            if($tanggalKritikSaranDB == date('Y-m-d'))  return throwResponseNotAcceptable('Anda sudah mengirimkan kritik/saran pada hari ini. Silakan coba lagi besok');
            if($subyekKritikSaranDB == $subyek)  return throwResponseNotAcceptable('Anda sudah mengirimkan kritik/saran dengan subyek yang sama. Silakan coba dengan subyek yang berbeda');
        }

        $dataInsertKritikSaran =   [
            'IDCUSTOMER'        =>  $idCustomer,
            'SUBYEK'            =>  $subyek,
            'PESAN'             =>  $pesan,
            'INPUTTANGGALWAKTU' =>  $this->currentDateTime
        ];
        $mainOperation->insertDataTable('t_kritiksaran', $dataInsertKritikSaran);
        return throwResponseOK('Kritik/Saran anda telah kami terima. Terima kasih atas masukan anda');
    }
}
