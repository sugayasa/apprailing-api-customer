<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\GaleriKlienModel;
use App\Models\MainOperation;

class GaleriKlien extends ResourceController
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

    public function getDataKlien()
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

        $galeriKlienModel   =   new GaleriKlienModel();
        $mainOperation      =   new MainOperation();

        $page               =   $this->request->getVar('page');
        $dataPerPage        =   $this->request->getVar('dataPerPage');
        $baseData           =	$galeriKlienModel->getDataKlien();
        $totalNumberData    =   $baseData->countAllResults(false);
        $pageProperty       =   $mainOperation->generatePageProperty($page, $dataPerPage, $totalNumberData);

        if($totalNumberData > 0){
            $dataKlien  =   $baseData->orderBy('NAMAKLIEN')->asObject()->findAll($dataPerPage, ($page - 1) * $dataPerPage);
            $dataKlien  =   encodeDatabaseObjectResultKey($dataKlien, ['IDKLIEN']);

            return $this->setResponseFormat('json')->respond([
                "dataKlien"     =>  $dataKlien,
                "urlKlienLogo"  =>  BASE_URL_ASSETS_GALERI_KLIEN_LOGO,
                "pageProperty"  =>  $pageProperty
            ]);
        } else {
            return throwResponseNotFound(
                'Tidak ada data yang ditemukan',
                [
                    "dataKlien"     =>  [],
                    "urlKlienLogo"  =>  BASE_URL_ASSETS_GALERI_KLIEN_LOGO,
                    "pageProperty"  =>  $pageProperty
                ]
            );
        }
    }

    public function getDetailGaleri()
    {
        $rules      =   [
            'idKlien'   =>  ['label' => 'Id Klien', 'rules' => 'required|alpha_numeric'],
        ];

        $messages   =   [
            'idKlien'  =>   [
                'required'      => 'Klien yang dipilih tidak valid, silakan coba lagi nanti',
                'alpha_numeric' => 'Klien yang dipilih tidak valid, silakan coba lagi nanti'
            ]
        ];

        if(!$this->validate($rules, $messages)) return $this->fail($this->validator->getErrors());
        
        $galeriKlienModel   =   new GaleriKlienModel();
        $idKlien            =   $this->request->getVar('idKlien');
        $idKlien            =   hashidDecode($idKlien);
        $detailGaleri       =   $galeriKlienModel->getDetailGaleriKlien($idKlien);

        if(is_null($detailGaleri)) {
            return throwResponseNotFound('Detail galeri klien tidak ditemukan');
        } else {
            $mainOperation = new MainOperation();
            
            foreach($detailGaleri as $keyDetailGaleri){
                $keyDetailGaleri->IMAGE =   json_decode($keyDetailGaleri->IMAGE, true);
                $mainOperation->insertDataStatistikKonten(901, $keyDetailGaleri->IDGALERIKLIEN, $this->userData, $this->currentDateTime);
                unset($keyDetailGaleri->IDGALERIKLIEN);
            }

            return $this->setResponseFormat('json')->respond([
                "urlGaleriProyek"   =>  BASE_URL_ASSETS_GALERI_KLIEN_PROYEK,
                "detailGaleri"      =>  $detailGaleri
            ]);
        }
    }
}
