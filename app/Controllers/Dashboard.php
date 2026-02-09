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
        $mainOperation              =   new MainOperation();
        $dashboardModel             =   new DashboardModel();
        $dataDetailRegional         =   $mainOperation->getDataDetailRegional();
        $dataMerk                   =   $mainOperation->getDataMerk();
        $dataMarketplaceAktif       =   $mainOperation->getDataMarketplaceAktif();
        $arrDatabaseRegional        =   array_column($dataDetailRegional, 'NAMADATABASE');
        $tanggalPeriodeAwal         =   date("Y-m-01");
        $tanggalPeriodeAkhir        =   date("Y-m-t");
        $dataGrafikPenjualan        =   [];
        $dataStatistikMerk          =   [];
        $dataStatistikMarketplace   =   [];
        $dataStatistikRegional      =	[];
        $arrTanggalPeriode          =   [];
        $tanggalPeriodeAwalTS       =   strtotime($tanggalPeriodeAwal);
        $tanggalPeriodeAkhirTS      =   strtotime($tanggalPeriodeAkhir);
            
        while ($tanggalPeriodeAwalTS <= $tanggalPeriodeAkhirTS) {
            $arrTanggalPeriode[]    =   date('d', $tanggalPeriodeAwalTS);
            $tanggalPeriodeAwalTS   =   strtotime('+1 day', $tanggalPeriodeAwalTS);
        }

        if($dataMerk && count($dataMerk) > 0){
            foreach($dataMerk as $keyMerk){
                $dataGrafikPenjualan[]    =   [
                    'color'                     =>  $keyMerk->HEXWARNA,
                    'borderColor'               =>  $keyMerk->HEXWARNA,
                    'borderWidth'               =>  1.5,
                    'pointBackgroundColor'      =>  'app.color.componentBg',
                    'pointBorderWidth'          =>  1.5,
                    'pointRadius'               =>  4,
                    'pointHoverBackgroundColor' =>  $keyMerk->HEXWARNA,
                    'pointHoverBorderColor'     =>  $keyMerk->HEXWARNA,
                    'pointHoverRadius'          =>  7,
                    'label'                     =>  $keyMerk->NAMAMERK,
                    'data'                      =>  array_fill(0, count($arrTanggalPeriode), 0),
                    'tension'                   =>  0.4
                ];
            }

            $dataPenjualanPerMerkPerTanggal  =   $dashboardModel->getDataPenjualanPerMerkPerTanggal($arrDatabaseRegional, $tanggalPeriodeAwal, $tanggalPeriodeAkhir);

            if($dataPenjualanPerMerkPerTanggal && count($dataPenjualanPerMerkPerTanggal) > 0){
                foreach($dataPenjualanPerMerkPerTanggal as $keyPenjualanPerMerkPerTanggal){
                    $namaMerk           =   $keyPenjualanPerMerkPerTanggal->NAMAMERK;
                    $tanggalPenjualan   =   ltrim($keyPenjualanPerMerkPerTanggal->TANGGAL, '0');
                    $hargaTotalPenjualan=   intval($keyPenjualanPerMerkPerTanggal->HARGATOTAL);

                    foreach($dataGrafikPenjualan as &$grafikPenjualan){
                        if ($grafikPenjualan['label'] === $namaMerk) {
                            $indexTanggal  =   array_search($tanggalPenjualan, $arrTanggalPeriode);
                            if($indexTanggal !== false){
                                $grafikPenjualan['data'][$indexTanggal]   +=  number_format($hargaTotalPenjualan, 0, '.', '');
                            }
                        }
                    }
                }
            }
        }

        if($dataMerk && count($dataMerk) > 0){
            foreach($dataMerk as $keyMerk){
                $dataStatistikMerk[]    =   [
                    "IDMERK"            =>  $keyMerk->IDMERK,
                    "NAMAMERK"          =>  $keyMerk->NAMAMERK,
                    "FILELOGO"          =>  $keyMerk->FILELOGO,
                    "TOTALSALESORDER"   =>  0,
                    "TOTALNOMINAL"      =>  0,
                ];
            }

            $dataStatistikMerkRegional  =   $dashboardModel->getDataStatistikMerkRegional($arrDatabaseRegional, $tanggalPeriodeAwal, $tanggalPeriodeAkhir);
            if($dataStatistikMerkRegional && count($dataStatistikMerkRegional) > 0){
                    foreach($dataStatistikMerkRegional as $statistikMerkRegional){
                        $idMerkRegional =   $statistikMerkRegional->IDMERK;
                        $totalSalesOrder=   $statistikMerkRegional->TOTALSALESORDER;
                        $totalNominal   =   $statistikMerkRegional->TOTALNOMINAL;
                        
                        foreach($dataStatistikMerk as &$statistikMerk){
                            if ($statistikMerk['IDMERK'] === $idMerkRegional) {
                                $statistikMerk['TOTALSALESORDER']   +=  $totalSalesOrder;
                                $statistikMerk['TOTALNOMINAL']      +=  $totalNominal;
                            }
                        }
                    }
                }
        }
     
        if($dataMarketplaceAktif && count($dataMarketplaceAktif) > 0){
            $arrIdMediaMarketing        =   array_column($dataMarketplaceAktif, 'IDMEDIAMARKETING');

            foreach($dataMarketplaceAktif as $detailMarketplace){
                $dataStatistikMarketplace[] =   [
                    "IDMEDIAMARKETING"   =>  $detailMarketplace->IDMEDIAMARKETING,
                    "NAMAMARKETPLACE"    =>  $detailMarketplace->NAMAMARKETPLACE,
                    "FILELOGO"           =>  $detailMarketplace->FILELOGO,
                    "TOTALSALESORDER"    =>  0,
                    "TOTALNOMINAL"       =>  0,
                ];
            }

            foreach($dataDetailRegional as $detailRegional){
                $namaDatabaseRegional               =   $detailRegional->NAMADATABASE;
                $dataStatistikMarketplaceRegional   =   $dashboardModel->getDataStatistikMarketplaceRegional($namaDatabaseRegional, $arrIdMediaMarketing, $tanggalPeriodeAwal, $tanggalPeriodeAkhir);
                
                if($dataStatistikMarketplaceRegional && count($dataStatistikMarketplaceRegional) > 0){
                    foreach($dataStatistikMarketplaceRegional as $statistikMarketplaceRegional){
                        $idMarketplaceRegional   =   $statistikMarketplaceRegional->IDMARKETPLACE;
                        $totalSalesOrder        =   $statistikMarketplaceRegional->TOTALSALESORDER;
                        $totalNominal           =   $statistikMarketplaceRegional->TOTALNOMINAL;
                        
                        foreach($dataStatistikMarketplace as &$statistikMarketplace){
                            if ($statistikMarketplace['IDMEDIAMARKETING'] === $idMarketplaceRegional) {
                                $statistikMarketplace['TOTALSALESORDER']  +=  $totalSalesOrder;
                                $statistikMarketplace['TOTALNOMINAL']     +=  $totalNominal;
                            }
                        }
                    }
                }
            }
        }

        if($dataDetailRegional && count($dataDetailRegional) > 0){
            $jumlahRegional                 =   count($dataDetailRegional);
            $statistikRegionalMarginBottom  =   2;

            switch($jumlahRegional){
                case 4: $statistikRegionalMarginBottom  =   5; break;
                case 5: $statistikRegionalMarginBottom  =   4; break;
                case 6: $statistikRegionalMarginBottom  =   3; break;
                case 7: $statistikRegionalMarginBottom  =   2; break;
                case 8: $statistikRegionalMarginBottom  =   1; break;
            }

            foreach($dataDetailRegional as $detailRegional){
                $namaDatabaseRegional       =   $detailRegional->NAMADATABASE;
                $dataStatistikPerRegional   =   $dashboardModel->getDataStatistikPerRegional($namaDatabaseRegional, $tanggalPeriodeAwal, $tanggalPeriodeAkhir);
                $totalNominal               =   intval($dataStatistikPerRegional['TOTALNOMINAL']);

                $dataStatistikRegional[]    =   [
                    "NAMAREGIONAL"      =>  $detailRegional->NAMAKOTA,
                    "CLASSWARNA"        =>  $detailRegional->CLASSWARNA,
                    "TOTALSALESORDER"   =>  $dataStatistikPerRegional['TOTALSALESORDER'],
                    "TOTALNOMINAL"      =>  $totalNominal,
                    "PERSENTASE"        =>  1
                ];
            }

            $arrSalesOrderNominal   =   array_column($dataStatistikRegional, 'TOTALNOMINAL');
            $totalSalesOrderNominal =   array_sum($arrSalesOrderNominal);

            foreach($dataStatistikRegional as &$statistikRegional){
                $totalNominal                   =   intval($statistikRegional['TOTALNOMINAL']);
                $persentase                     =   $totalSalesOrderNominal > 0  ? ($totalNominal / $totalSalesOrderNominal) * 100 : 1;
                $statistikRegional['PERSENTASE']=   number_format($persentase, 2, '.', '');
            }
        }

        $dataBarangBestSeller   =   $dashboardModel->getDataBarangBestSeller($arrDatabaseRegional, $tanggalPeriodeAwal, $tanggalPeriodeAkhir);
        $dataHistoriSalesOrder  =   $dashboardModel->getDataHistoriSalesOrder($arrDatabaseRegional);

        return $this->setResponseFormat('json')
                    ->respond([
                        "arrTanggalPeriode"             =>  $arrTanggalPeriode,
                        "dataGrafikPenjualan"           =>  $dataGrafikPenjualan,
                        "dataStatistikMerk"             =>  $dataStatistikMerk,
                        "dataStatistikMarketplace"      =>  $dataStatistikMarketplace,
                        "statistikRegionalMarginBottom" =>  $statistikRegionalMarginBottom,
                        "dataStatistikRegional"         =>  $dataStatistikRegional,
                        "dataBarangBestSeller"          =>  $dataBarangBestSeller,
                        "dataHistoriSalesOrder"         =>  $dataHistoriSalesOrder,
                        "urlAssetLogoMerk"              =>  BASE_URL_ASSETS_LOGO_MERK,
                        "urlAssetLogoMarketplace"       =>  BASE_URL_ASSETS_LOGO_MARKETPLACE,
                        "urlAssetFotoBarang"            =>  BASE_URL_ASSETS_PHOTO_BARANG
                    ]);
    }
}