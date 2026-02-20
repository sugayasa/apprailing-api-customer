<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\KatalogModel;
use App\Models\MainOperation;

class Katalog extends ResourceController
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

    public function getDataProduk()
    {
        $rules      =   [
            'searchKeyword' =>  ['label' => 'Kata Kunci Pencarian', 'rules' => 'permit_empty|alpha_numeric_punct'],
            'idMerk'        =>  ['label' => 'Id Merk', 'rules' => 'permit_empty|alpha_numeric'],
            'page'          =>  ['label' => 'Page', 'rules' => 'required|numeric'],
            'dataPerPage'   =>  ['label' => 'Data Per Page', 'rules' => 'required|numeric']
        ];

        $messages   =   [
            'idMerk'    => [
                'alpha_numeric' => 'Merk yang dipilih tidak valid, silakan coba lagi nanti'
            ],
            'page'      => [
                'required'=> 'Invalid data sent - Page is required',
                'numeric' => 'Invalid data sent - Page must be a number'
            ],
            'dataPerPage'  => [
                'required'=> 'Invalid data sent - Data Per Page is required',
                'numeric' => 'Invalid data sent - Data Per Page must be a number'
            ]
        ];

        if(!$this->validate($rules, $messages)) return $this->fail($this->validator->getErrors());

        $katalogModel   =   new KatalogModel();
        $mainOperation  =   new MainOperation();
        $searchKeyword  =   $this->request->getVar('searchKeyword');
        $idMerk         =   $this->request->getVar('idMerk');
        $idMerk         =   isset($idMerk) && $idMerk != "" ? hashidDecode($idMerk) : 0;
        $page           =   $this->request->getVar('page');
        $dataPerPage    =   $this->request->getVar('dataPerPage');
        $baseData       =	$katalogModel->getDataProduk($idMerk, $searchKeyword);
        $totalNumberData=   $baseData->countAllResults(false);
        $pageProperty   =   $mainOperation->generatePageProperty($page, $dataPerPage, $totalNumberData);

        if($totalNumberData > 0){
            $dataProduk   =   $baseData->orderBy('NAMAPRODUK')->asObject()->findAll($dataPerPage, ($page - 1) * $dataPerPage);
            foreach ($dataProduk as &$produk) {
                $arrImage   =   json_decode($produk->ARRIMAGE, true);
                if(is_array($arrImage) && count($arrImage) > 0){
                    $produk->URLIMAGE   =   BASE_URL_ASSETS_CUSTOMER_PRODUK.$arrImage[0];
                } else {
                    $produk->URLIMAGE   =   BASE_URL_ASSETS_CUSTOMER_PRODUK.'noimage.jpg';
                }
                unset($produk->ARRIMAGE);
            }

            $dataProduk   =   encodeDatabaseObjectResultKey($dataProduk, ['IDPRODUK']);
            return $this->setResponseFormat('json')->respond([
                "dataProduk"    =>  $dataProduk,
                "pageProperty"  =>  $pageProperty
            ]);
        } else {
            $dataReturn =   [
                "dataProduk"    =>  [],
                "pageProperty"  =>  $pageProperty
            ];
            return throwResponseNotFound('Tidak ada data yang ditemukan', $dataReturn);
        }
    }

    public function getDetailProduk()
    {
        $rules      =   [
            'idProduk'  =>  ['label' => 'Id Produk', 'rules' => 'required|alpha_numeric'],
        ];

        $messages   =   [
            'idProduk'    => [
                'required'      => 'Produk yang dipilih tidak valid, silakan coba lagi nanti',
                'alpha_numeric' => 'Produk yang dipilih tidak valid, silakan coba lagi nanti'
            ]
        ];

        if(!$this->validate($rules, $messages)) return $this->fail($this->validator->getErrors());

        $katalogModel   =   new KatalogModel();
        $idProduk       =   $this->request->getVar('idProduk');
        $idProduk       =   isset($idProduk) && $idProduk != "" ? hashidDecode($idProduk) : 0;
        $detailProduk   =	$katalogModel->getDetailProduk($idProduk);

        if($detailProduk){
            $detailProduk['ARRIMAGE'] =   json_decode($detailProduk['ARRIMAGE'], true);
            $detailProduk['DATASTOK'] =   [
                [
                    "IDREGIONAL"    =>  hashidEncode(101),
                    "NAMAREGIONAL"  =>  "Jakarta",
                    "TOTALSTOK"     =>  55
                ],
                [
                    "IDREGIONAL"    =>  hashidEncode(100),
                    "NAMAREGIONAL"  =>  "Surabaya",
                    "TOTALSTOK"     =>  213
                ],
                [
                    "IDREGIONAL"    =>  hashidEncode(102),
                    "NAMAREGIONAL"  =>  "Denpasar",
                    "TOTALSTOK"     =>  0
                ],
                [
                    "IDREGIONAL"    =>  hashidEncode(103),
                    "NAMAREGIONAL"  =>  "Semarang",
                    "TOTALSTOK"     =>  12
                ]
            ];
            return $this->setResponseFormat('json')->respond([
                "detailProduk"  =>  $detailProduk,
                "urlImageProduk"=>  BASE_URL_ASSETS_CUSTOMER_PRODUK
            ]);
        } else {
            return throwResponseNotFound('Detail produk tidak ditemukan');
        }
    }
}