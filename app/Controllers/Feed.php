<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\FeedModel;
use App\Models\MainOperation;

class Feed extends ResourceController
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

    public function getDataFeed()
    {
        $rules      =   [
            'isBookmark'    =>  ['label' => 'Bookmark', 'rules' => 'permit_empty|in_list[0,1]'],
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

        $feedModel      =   new FeedModel();
        $mainOperation  =   new MainOperation();
        $idCustomer     =   $this->userData->idCustomer;
        $page           =   $this->request->getVar('page');
        $dataPerPage    =   $this->request->getVar('dataPerPage');
        $isBookmark     =   $this->request->getVar('isBookmark');
        $isBookmark     =   isset($isBookmark) && $isBookmark == 1 ? true : false;
        $baseData       =	$feedModel->getDataFeed($idCustomer, $isBookmark);
        $totalNumberData=   $baseData->countAllResults(false);
        $pageProperty   =   $mainOperation->generatePageProperty($page, $dataPerPage, $totalNumberData);

        if($totalNumberData > 0){
            $dataFeed   =   $baseData->orderBy('A.INPUTTANGGALWAKTU DESC')->asObject()->findAll($dataPerPage, ($page - 1) * $dataPerPage);
            $dataFeed   =   encodeDatabaseObjectResultKey($dataFeed, ['IDFEED']);
            return $this->setResponseFormat('json')->respond([
                "dataFeed"      =>  $dataFeed,
                "pageProperty"  =>  $pageProperty
            ]);
        } else {
            $dataReturn =   [
                "dataFeed"      =>  [],
                "pageProperty"  =>  $pageProperty
            ];
            return throwResponseNotFound('Tidak ada data yang ditemukan', $dataReturn);
        }
    }

    public function setSukaFeed()
    {
        $idCustomer =   $this->userData->idCustomer;
        if(!isset($idCustomer) || empty($idCustomer) || $idCustomer == 0) return throwResponseForbidden('Tidak dapat menyukai feed, harap masuk atau registrasi terlebih dahulu');

        $rules      =   [
            'idFeed'    =>  ['label' => 'Feed', 'rules' => 'required|alpha_numeric']
        ];

        $messages   =   [
            'idFeed'  => [
                'required'      => 'Data kiriman tidak valid',
                'alpha_numeric' => 'Data kiriman tidak valid'
            ]
        ];

        if(!$this->validate($rules, $messages)) return $this->fail($this->validator->getErrors());

        $mainOperation  =   new MainOperation();
        $idFeed         =   $this->request->getVar('idFeed');
        $idFeed         =   isset($idFeed) && $idFeed != "" ? hashidDecode($idFeed) : 0;
        $isDataSukaExist=   $mainOperation->isDataExist('t_feedsuka', ['IDFEED' => $idFeed, 'IDCUSTOMER' => $idCustomer]);

        if(!$isDataSukaExist) {
            $dataInsertSukaFeed =   [
                'IDFEED'            =>  $idFeed,
                'IDCUSTOMER'        =>  $idCustomer,
                'INPUTTANGGALWAKTU' =>  $this->currentDateTime
            ];
            $mainOperation->insertDataTable('t_feedsuka', $dataInsertSukaFeed);
            $this->updateTotalSukaFeed($idFeed);
            return throwResponseOK('Anda telah menyukai feed ini');
        } else {
            $mainOperation->deleteDataTable('t_feedsuka', ['IDFEED' => $idFeed, 'IDCUSTOMER' => $idCustomer]);
            $this->updateTotalSukaFeed($idFeed);
            return throwResponseOK('Anda tidak lagi menyukai feed ini');
        }
    }

    private function updateTotalSukaFeed($idFeed)
    {
        $feedModel          =   new FeedModel();
        $mainOperation      =   new MainOperation();
        $totalSukaFeed      =   $feedModel->where('IDFEED', $idFeed)->countAllResults('t_feedsuka', false);
        $mainOperation->updateDataTable('t_feed', ['TOTALSUKA' => $totalSukaFeed], ['IDFEED' => $idFeed]);
    }

    public function setBookmarkFeed()
    {
        $idCustomer =   $this->userData->idCustomer;
        if(!isset($idCustomer) || empty($idCustomer) || $idCustomer == 0) return throwResponseForbidden('Tidak dapat menandai feed, harap masuk atau registrasi terlebih dahulu');

        $rules      =   [
            'idFeed'    =>  ['label' => 'Feed', 'rules' => 'required|alpha_numeric']
        ];

        $messages   =   [
            'idFeed'  => [
                'required'      => 'Data kiriman tidak valid',
                'alpha_numeric' => 'Data kiriman tidak valid'
            ]
        ];

        if(!$this->validate($rules, $messages)) return $this->fail($this->validator->getErrors());

        $mainOperation      =   new MainOperation();
        $idFeed             =   $this->request->getVar('idFeed');
        $idFeed             =   isset($idFeed) && $idFeed != "" ? hashidDecode($idFeed) : 0;
        $isDataBookmarkExist=   $mainOperation->isDataExist('t_feedbookmark', ['IDFEED' => $idFeed, 'IDCUSTOMER' => $idCustomer]);

        if(!$isDataBookmarkExist) {
            $dataInsertBookmarkFeed =   [
                'IDFEED'            =>  $idFeed,
                'IDCUSTOMER'        =>  $idCustomer,
                'INPUTTANGGALWAKTU' =>  $this->currentDateTime
            ];
            $mainOperation->insertDataTable('t_feedbookmark', $dataInsertBookmarkFeed);
            $this->updateTotalBookmarkFeed($idFeed);
            return throwResponseOK('Feed telah disimpan ke bookmark anda');
        } else {
            $mainOperation->deleteDataTable('t_feedbookmark', ['IDFEED' => $idFeed, 'IDCUSTOMER' => $idCustomer]);
            $this->updateTotalBookmarkFeed($idFeed);
            return throwResponseOK('Feed telah dihapus dari bookmark anda');
        }
    }

    private function updateTotalBookmarkFeed($idFeed)
    {
        $feedModel          =   new FeedModel();
        $mainOperation      =   new MainOperation();
        $totalBookmarkFeed  =   $feedModel->where('IDFEED', $idFeed)->countAllResults('t_feedbookmark', false);
        $mainOperation->updateDataTable('t_feed', ['TOTALSIMPAN' => $totalBookmarkFeed], ['IDFEED' => $idFeed]);
    }
}