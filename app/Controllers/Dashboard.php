<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
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
        $dashboardModel =   new DashboardModel();
        $isDeveloper    =   isset($this->userData->isDeveloper) ? (int)$this->userData->isDeveloper : 0;
        $profileData    =   [
            "avatar"        =>  $this->userData->avatar,
            "nama"          =>  $this->userData->nama,
            "tanggalLahir"  =>  $this->userData->tanggalLahir,
            "kota"          =>  $this->userData->kota,
            "propinsi"      =>  $this->userData->propinsi
        ];

        //ROYALTI DETAIL
        $idCustomerLoyalti  =   0;
        $totalPoinLoyalti   =   0;
        $loyaltiDetail      =   [
            'TANGGALDAFTAR'         =>  '-',
            'TOTALPOIN'             =>  0,
            'LOYALTITIER'           =>  '-',
            'TOTALPOINSELANJUTNYA'  =>  0,
            'LOYALTITIERSELANJUTNYA'=>  '-',
            'ICONLOYALTI'           =>  BASE_URL_ASSETS_ICON_LEVEL_LOYALTI.'default.png',
            'CARDLOYALTI'           =>  BASE_URL_ASSETS_CARD_LEVEL_LOYALTI.'default-card.png'
        ];

        if($this->userData->idCustomer && $this->userData->idCustomer != '') {
            $loyaltiDetail  =   $dashboardModel->getLoyaltiDetail($this->userData->idCustomer);
            if(!is_null($loyaltiDetail)){
                $idCustomerLoyalti      =   $loyaltiDetail['IDCUSTOMERLOYALTI'];
                $totalPoinLoyalti       =   $loyaltiDetail['TOTALPOIN'];
                unset($loyaltiDetail['IDCUSTOMERLOYALTI']);
            }
        }

        $detailNextTierLoyalti  =   $dashboardModel->getDetailNextTierLoyalti($idCustomerLoyalti, $totalPoinLoyalti);
        $totalPoinSelanjutnya   =   $detailNextTierLoyalti['MINIMALPOIN'] - $totalPoinLoyalti;
        
        $loyaltiDetail['TOTALPOIN']             =   number_format($totalPoinLoyalti, 0, ',', '.');
        $loyaltiDetail['TOTALPOINSELANJUTNYA']  =   $totalPoinSelanjutnya > 0 ? number_format($totalPoinSelanjutnya, 0, ',', '.') : 0;
        $loyaltiDetail['LOYALTITIERSELANJUTNYA']=   $detailNextTierLoyalti['LOYALTITIER'];
        
        //REVIEW MARKETING
        $reviewMarketing=   $this->userData->idCustomer && $this->userData->idCustomer != '' ? $dashboardModel->getReviewMarketing($this->userData->idCustomer, $isDeveloper) : [];

        //SLIDE BANNER
        $dataSlideBanner=   $dashboardModel->getDataSlideBanner();
        $slideBanner    =   [];
        foreach ($dataSlideBanner as $keySlide) {
            $slideBanner[] = [
                "idSlideBanner" =>  hashidEncode($keySlide->IDSLIDEBANNER),
                "urlImage"      =>  BASE_URL_ASSETS_CUSTOMER_SLIDE_BANNER.$keySlide->IMAGE,
                "urlDetail"     =>  BASE_URL_DETAIL_SLIDE_ARTICLE.hashidEncode($keySlide->IDSLIDEBANNER, true)
            ];
        }

        //DATA MERK
        $dataMerkDB =   $dashboardModel->getDataMerk();
        $dataMerk   =   [];
        foreach ($dataMerkDB as $keyMerk) {
            $dataMerk[] = [
                "idMerk"    =>  hashidEncode($keyMerk->IDMERK),
                "namaMerk"  =>  $keyMerk->NAMAMERK,
                "logoMerk"  =>  BASE_URL_ASSETS_CUSTOMER_MERK.$keyMerk->LOGO
            ];
        }

        //DATA ORDER TERAKHIR
        $dataOrderDB=   $dashboardModel->getDataOrderTerakhir($this->userData->idCustomer);
        $dataOrder  =   [];
        foreach ($dataOrderDB as $keyOrder) {
            $idTransaksiRekap   =   $keyOrder->IDTRANSAKSIREKAP;
            $dataBarangOrder    =   $dashboardModel->getDataBarangOrder($idTransaksiRekap);
            $arrFotoBarang      =   [];
            foreach ($dataBarangOrder as $keyBarangOrder) {
                $arrImage       =   $keyBarangOrder->ARRIMAGE;
                $arrImageDecoded=   json_decode($arrImage, true);
                if ($arrImageDecoded && isset($arrImageDecoded[0])) {
                    $arrFotoBarang[]=   BASE_URL_ASSETS_PHOTO_BARANG.$arrImageDecoded[0];
                }
            }

            $dataOrder[]        =   [
                "idOrder"       =>  hashidEncode($idTransaksiRekap),
                "statusOrder"   =>  $keyOrder->STATUSTRANSAKSI,
                "tanggalWaktu"  =>  $keyOrder->TANGGALORDER,
                "fotoBarang"    =>  $arrFotoBarang,
                "jumlahBarang"  =>  $keyOrder->TOTALBARANG,
                "kodeOrder"     =>  $keyOrder->NOMORTRANSAKSI,
                "totalNominal"  =>  $keyOrder->TOTALNOMINALBAYAR
            ];
        }

        return $this
                ->setResponseFormat('json')
                ->respond([
                    "profileData"       =>  $profileData,
                    "loyaltiDetail"     =>  $loyaltiDetail,
                    "reviewMarketing"   =>  $reviewMarketing,
                    "slideBanner"       =>  $slideBanner,
                    "dataMerk"          =>  $dataMerk,
                    "dataOrder"         =>  $dataOrder
                ]);
    }

    public function getDetailSlideBanner($idSlideBanner)
    {
        $dashboardModel     =   new DashboardModel();
        $idSlideBanner      =   hashidDecode($idSlideBanner, true);
        $detailSlideBanner  =   $dashboardModel->getDetailSlideBanner($idSlideBanner);
        
        if(is_null($detailSlideBanner)) return view('errors/cli/artikel_tidak_ditemukan');
        return view('detail_artikel', [
            'konten' => $detailSlideBanner['KONTEN']
        ]);
    }
}