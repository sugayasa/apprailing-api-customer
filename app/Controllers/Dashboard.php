<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\MainOperation;
use App\Models\DashboardModel;

class Dashboard extends ResourceController
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

    public function getDataDashboard()
    {
        $profileData    =   [
            "avatar"    =>  $this->userData->avatar,
            "nama"      =>  $this->userData->nama,
            "kota"      =>  $this->userData->kota,
            "propinsi"  =>  $this->userData->propinsi
        ];

        $slideBanner    =   [
            [
                "idSlideBanner" =>  hashidEncode(1),
                "urlImage"      =>  BASE_URL_ASSETS_CUSTOMER_SLIDE_BANNER.'slide-banner-1.jpg'
            ],
            [
                "idSlideBanner" =>  hashidEncode(2),
                "urlImage"      =>  BASE_URL_ASSETS_CUSTOMER_SLIDE_BANNER.'slide-banner-2.jpg'
            ],
            [
                "idSlideBanner" =>  hashidEncode(3),
                "urlImage"      =>  BASE_URL_ASSETS_CUSTOMER_SLIDE_BANNER.'slide-banner-3.jpg'
            ]
        ];

        $dataMerk       =   [
            [
                "idMerk"    =>  hashidEncode(1),
                "namaMerk"  =>  "Rich Railing",
                "logoMerk"  =>  BASE_URL_ASSETS_CUSTOMER_MERK.'richrailing.png'
            ],
            [
                "idMerk"    =>  hashidEncode(2),
                "namaMerk"  =>  "Railingku",
                "logoMerk"  =>  BASE_URL_ASSETS_CUSTOMER_MERK.'railingku.png'
            ],
            [
                "idMerk"    =>  hashidEncode(3),
                "namaMerk"  =>  "Weezy",
                "logoMerk"  =>  BASE_URL_ASSETS_CUSTOMER_MERK.'weezy.png'
            ]
        ];

        $dataOrder      =   [
            [
                "idOrder"       =>  hashidEncode(1),
                "statusOrder"   =>  "Order Selesai",
                "tanggalWaktu"  =>  "23 Desember 2025 10:00",
                "fotoBarang"    =>  [
                    BASE_URL_ASSETS_PHOTO_BARANG.'railing-balkon-minimalis.jpg',
                    BASE_URL_ASSETS_PHOTO_BARANG.'railing-balkon-minimalis.jpg'
                ],
                "jumlahBarang"  =>  4,
                "kodeOrder"     =>  "#ORD-0010007",
                "totalNominal"  =>  "4.300.000"
            ]
        ];

        return $this
                ->setResponseFormat('json')
                ->respond([
                    "profileData"   =>  $profileData,
                    "slideBanner"   =>  $slideBanner,
                    "dataMerk"      =>  $dataMerk,
                    "dataOrder"     =>  $dataOrder
                ]);
    }
}