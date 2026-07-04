<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\ScanQRModel;

class ScanQR extends ResourceController
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

    public function getDetailQRCustomer()
    {
        $idCustomer     =   $this->userData->idCustomer;
        if (!$idCustomer) return throwResponseForbidden('Anda tidak memiliki akses, harap masuk atau registrasi terlebih dahulu');

        $scanQRModel    =   new ScanQRModel();
        $detailCustomer =	$scanQRModel->getDetailCustomer($idCustomer);
        $paramQRImage   =   [
            "idCustomer"    =>  $idCustomer,
            "tanggalWaktu"  =>  date('Y-m-d H:i:s')
        ];
        $tokenQRImage   =   encodeJWTToken($paramQRImage);
        
        return $this
                ->setResponseFormat('json')
                ->respond([
                    "kodeUnikCustomer"  =>  $detailCustomer['KODEUNIK'],
                    "namaCustomer"      =>  $detailCustomer['NAMA'],
                    "imageQRCustomer"   =>  BASE_URL_ASSETS_CUSTOMER_IMAGE_QR.$tokenQRImage
                ]);
    }
}