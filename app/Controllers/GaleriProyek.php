<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\GaleriProyekModel;
use App\Models\MainOperation;

class GaleriProyek extends ResourceController
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

    public function getDataProyek()
    {
        $rules      =   [
            'page'          =>  ['label' => 'Page', 'rules' => 'required|numeric'],
            'dataPerPage'   =>  ['label' => 'Data Per Page', 'rules' => 'required|numeric']
        ];

        $messages   =   [
            'page'      =>  [
                'required'  =>  'Invalid data sent - Page is required',
                'numeric'   =>  'Invalid data sent - Page must be a number'
            ],
            'dataPerPage'   =>   [
                'required'  =>  'Invalid data sent - Data Per Page is required',
                'numeric'   =>  'Invalid data sent - Data Per Page must be a number'
            ]
        ];

        if(!$this->validate($rules, $messages)) return $this->fail($this->validator->getErrors());

        $galeriProyekModel  =   new GaleriProyekModel();
        $mainOperation      =   new MainOperation();

        $baseData           =   $galeriProyekModel->getDataGaleriProyek();
        $totalNumberData    =   $baseData->countAllResults(false);
        $page               =   $this->request->getVar('page');
        $dataPerPage        =   $this->request->getVar('dataPerPage');
        $pageProperty       =   $mainOperation->generatePageProperty($page, $dataPerPage, $totalNumberData);

        if($totalNumberData > 0){
            $dataGaleriProyek   =   $baseData->asObject()->findAll($dataPerPage, ($page - 1) * $dataPerPage);

            foreach ($dataGaleriProyek as &$galeriProyek) {
                $arrImage   =   json_decode($galeriProyek->IMAGE, true);
                if(is_array($arrImage) && count($arrImage) > 0){
                    $galeriProyek->IMAGE    =   BASE_URL_ASSETS_CUSTOMER_PRODUK.$arrImage[0];
                } else {
                    $galeriProyek->IMAGE    =   BASE_URL_ASSETS_CUSTOMER_PRODUK.'noimage.jpg';
                }
            }

            $dataGaleriProyek   =   encodeDatabaseObjectResultKey($dataGaleriProyek, ['IDGALERIPROYEK']);
            return $this->setResponseFormat('json')->respond([
                "dataGaleriProyek"  =>  $dataGaleriProyek,
                "pageProperty"      =>  $pageProperty
            ]);
        } else {
            $dataReturn =   [
                "dataGaleriProyek"  =>  [],
                "pageProperty"      =>  $pageProperty
            ];
            return throwResponseNotFound('Tidak ada data yang ditemukan', $dataReturn);
        }
    }

    public function getDetailGaleri()
    {
        $rules      =   [
            'idGaleriProyek'    =>  ['label' => 'Id Galeri Proyek', 'rules' => 'required|alpha_numeric'],
        ];

        $messages   =   [
            'idGaleriProyek'    =>   [
                'required'      =>  'Galeri proyek yang dipilih tidak valid, silakan coba lagi nanti',
                'alpha_numeric' =>  'Galeri proyek yang dipilih tidak valid, silakan coba lagi nanti'
            ]
        ];

        if(!$this->validate($rules, $messages)) return $this->fail($this->validator->getErrors());

        $galeriProyekModel  =   new GaleriProyekModel();
        $idGaleriProyek     =   $this->request->getVar('idGaleriProyek');
        $idGaleriProyek     =   hashidDecode($idGaleriProyek);
        $detailGaleriProyek =   $galeriProyekModel->getDetailGaleriProyek($idGaleriProyek);

        if(is_null($detailGaleriProyek)) {
            return throwResponseNotFound(
                'Tidak ada detail yang ditemukan',
                [
                    "detailGaleriProyek"    =>  []
                ]
            );
        }

        $mainOperation = new MainOperation();
        $mainOperation->insertDataStatistikKonten(902, $idGaleriProyek, $this->userData, $this->currentDateTime);
            
        $arrImage   =   json_decode($detailGaleriProyek['IMAGE'], true);
        if(is_array($arrImage) && count($arrImage) > 0){
            $detailGaleriProyek['IMAGE']    =   array_map(function($image) {
                return BASE_URL_ASSETS_CUSTOMER_PRODUK.$image;
            }, $arrImage);
        } else {
            $detailGaleriProyek['IMAGE']    =   [BASE_URL_ASSETS_CUSTOMER_PRODUK.'noimage.jpg'];
        }

        return $this->setResponseFormat('json')->respond([
            "detailGaleriProyek"    =>  $detailGaleriProyek
        ]);
    }
}
