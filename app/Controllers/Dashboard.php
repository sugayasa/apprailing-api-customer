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

        return $this
                ->setResponseFormat('json')
                ->respond([
                    "profileData"   =>  $profileData,
                    "slideBanner"   =>  $slideBanner
                ]);
    }
}