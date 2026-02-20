<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\TransaksiModel;
use App\Models\MainOperation;

class Transaksi extends ResourceController
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

    public function getDataTransaksi()
    {
        $idCustomer     =   $this->userData->idCustomer;
        $isRegistered   =   $idCustomer != 0 ? true : false;
        
        if(!$isRegistered) return throwResponseForbidden('Anda tidak memiliki akses, harap masuk atau registrasi terlebih dahulu');

        $rules      =   [
            'searchKeyword'     =>  ['label' => 'Kata Kunci Pencarian', 'rules' => 'permit_empty|alpha_numeric_punct'],
            'idStatusTransaksi' =>  ['label' => 'Id Status Transaksi', 'rules' => 'permit_empty|alpha_numeric'],
            'idKategori'        =>  ['label' => 'Id Kategori', 'rules' => 'permit_empty|alpha_numeric'],
            'page'              =>  ['label' => 'Page', 'rules' => 'required|numeric'],
            'dataPerPage'       =>  ['label' => 'Data Per Page', 'rules' => 'required|numeric']
        ];

        $messages   =   [
            'idStatusTransaksi' => [
                'alpha_numeric' => 'Status transaksi yang dipilih tidak valid, silakan coba lagi nanti'
            ],
            'idKategori'=> [
                'alpha_numeric' => 'Kategori yang dipilih tidak valid, silakan coba lagi nanti'
            ],
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

        $mainOperation      =   new MainOperation();
        $transaksiModel     =   new TransaksiModel();
        $idStatusTransaksi  =   $this->request->getVar('idStatusTransaksi');
        $idStatusTransaksi  =   isset($idStatusTransaksi) && $idStatusTransaksi != "" ? hashidDecode($idStatusTransaksi) : 0;
        $idKategori         =   $this->request->getVar('idKategori');
        $idKategori         =   isset($idKategori) && $idKategori != "" ? hashidDecode($idKategori) : 0;
        $searchKeyword      =   $this->request->getVar('searchKeyword');
        $page               =   $this->request->getVar('page');
        $dataPerPage        =   $this->request->getVar('dataPerPage');
        $baseData           =   $transaksiModel->getDataTransaksi($idCustomer, $idStatusTransaksi, $idKategori, $searchKeyword);
        $totalNumberData    =   $baseData->countAllResults(false);
        $pageProperty       =   $mainOperation->generatePageProperty($page, $dataPerPage, $totalNumberData);
        
        if($totalNumberData > 0){
            $dataTransaksi  =   $baseData->orderBy('INPUTTANGGALWAKTU', 'DESC')->asObject()->findAll($dataPerPage, ($page - 1) * $dataPerPage);
            foreach ($dataTransaksi as &$transaksi) {
                $arrIdProduk=   explode(',', $transaksi->ARRIDPRODUK);
                if(is_array($arrIdProduk) && count($arrIdProduk) > 0){
                    $dataProduk     =   $transaksiModel->getDataImageProduk($arrIdProduk);
                    $arrImageProduk =   [];

                    foreach ($dataProduk as $produk) {
                        $arrImage   =   json_decode($produk->ARRIMAGE, true);
                        if(is_array($arrImage) && count($arrImage) > 0){
                            $arrImageProduk =   BASE_URL_ASSETS_CUSTOMER_PRODUK.$arrImage[0];
                        } else {
                            $arrImageProduk =   BASE_URL_ASSETS_CUSTOMER_PRODUK.'noimage.jpg';
                        }
                        unset($produk->ARRIMAGE);
                    }

                    $transaksi->ARRIMAGEPRODUK  =   $arrImageProduk;
                }
                unset($transaksi->ARRIDPRODUK);
            }

            $dataTransaksi  =   encodeDatabaseObjectResultKey($dataTransaksi, ['IDTRANSAKSIREKAP']);
            return $this->setResponseFormat('json')->respond([
                "dataTransaksi" =>  $dataTransaksi,
                "pageProperty"  =>  $pageProperty
            ]);
        } else {
            $dataReturn     =   [
                "dataTransaksi" =>  [],
                "pageProperty"  =>  $pageProperty
            ];
            return throwResponseNotFound('Tidak ada data yang ditemukan', $dataReturn);
        }
    }

    public function getDetailTransaksi()
    {
        $rules      =   [
            'idTransaksiRekap'  =>  ['label' => 'Id Transaksi', 'rules' => 'required|alpha_numeric'],
        ];

        $messages   =   [
            'idTransaksiRekap'  => [
                'required'      => 'Transaksi yang dipilih tidak valid, silakan coba lagi nanti',
                'alpha_numeric' => 'Transaksi yang dipilih tidak valid, silakan coba lagi nanti'
            ]
        ];

        if(!$this->validate($rules, $messages)) return $this->fail($this->validator->getErrors());

        $transaksiModel   =   new TransaksiModel();
        $idTransaksiRekap =   $this->request->getVar('idTransaksiRekap');
        $idTransaksiRekap =   isset($idTransaksiRekap) && $idTransaksiRekap != "" ? hashidDecode($idTransaksiRekap) : 0;
        $detailTransaksi  =   $transaksiModel->getDetailTransaksi($idTransaksiRekap);

        if($detailTransaksi){
            $dataProdukTransaksi=   $transaksiModel->getDataProdukTransaksi($idTransaksiRekap);
            foreach ($dataProdukTransaksi as &$produkTransaksi) {
                $arrImageProduk =   json_decode($produkTransaksi->ARRIMAGE, true);
                if(is_array($arrImageProduk) && count($arrImageProduk) > 0){
                    $arrImageProduk  =   $arrImageProduk[0];
                } else {
                    $arrImageProduk  =   'noimage.jpg';
                }
                $produkTransaksi->IMAGEPRODUK  =   $arrImageProduk;
                unset($produkTransaksi->ARRIMAGE);
            }

            return $this->setResponseFormat('json')->respond([
                "detailTransaksi"       =>  $detailTransaksi,
                "dataProdukTransaksi"   =>  $dataProdukTransaksi,
                "urlImageProduk"        =>  BASE_URL_ASSETS_CUSTOMER_PRODUK
            ]);
        } else {
            return throwResponseNotFound('Detail transaksi tidak ditemukan');
        }
    }

    public function checkProdukOngkosKirim()
    {
        $idCustomer     =   $this->userData->idCustomer;
        $isRegistered   =   $idCustomer != 0 ? true : false;
        
        if(!$isRegistered) return throwResponseForbidden('Anda tidak memiliki akses, harap masuk atau registrasi terlebih dahulu');

        $rules  =   [
            'idCustomerAlamat'      =>  ['label' => 'Id Customer Alamat', 'rules' => 'required|alpha_numeric'],
            'dataProduk.*.idProduk' =>  ['label' => 'Produk', 'rules' => 'required|alpha_numeric'],
        ];

        $messages   =   [
            'idCustomerAlamat' => [
                'required'      => 'Alamat pengiriman yang dipilih tidak valid, silakan coba lagi nanti',
                'alpha_numeric' => 'Alamat pengiriman yang dipilih tidak valid, silakan coba lagi nanti'
            ],
            'dataProduk.*.idProduk' =>  [
                'required'      =>  'Produk dipilih tidak valid, silakan periksa kembali',
                'alpha_numeric' =>  'Produk yang dipilih tidak valid, silakan periksa kembali'
            ]
        ];

        if(!$this->validate($rules, $messages)) return $this->validator->getErrors();

        $idCustomerAlamat   =   $this->request->getVar('idCustomerAlamat');
        $idCustomerAlamat   =   isset($idCustomerAlamat) && $idCustomerAlamat != "" ? hashidDecode($idCustomerAlamat) : 0;
        $dataProduk         =   $this->request->getVar('dataProduk');
        $dataProdukHarga    =   [];

        foreach ($dataProduk as $produk) {
            $idProduk  =   isset($produk->idProduk) && $produk->idProduk != "" ? hashidDecode($produk->idProduk) : 0;

            if($idProduk == 0) return throwResponseNotAcceptable('Produk yang dipilih tidak valid, silakan periksa kembali');
            $detailProduk   =   (new TransaksiModel())->getDetailProdukById($idProduk);
            if(!$detailProduk) return throwResponseNotAcceptable('Produk yang dipilih tidak valid, silakan periksa kembali');
            $dataProdukHarga[]   =   [
                "idProduk"  =>  hashidEncode($idProduk),
                "harga"     =>  $detailProduk['HARGAJUAL']
            ];
        }

        return $this->setResponseFormat('json')->respond([
            "totalOngkir"       =>  45000,
            "dataProdukHarga"   =>  $dataProdukHarga,
        ]);
    }

    public function saveDataTransaksi()
    {
        $idCustomer     =   $this->userData->idCustomer;
        $isRegistered   =   $idCustomer != 0 ? true : false;
        
        if(!$isRegistered) return throwResponseForbidden('Anda tidak memiliki akses, harap masuk atau registrasi terlebih dahulu');

        $rules  =   [
            'idRegional'                =>  ['label' => 'Id Regional', 'rules' => 'required|alpha_numeric'],
            'idCustomerAlamat'          =>  ['label' => 'Id Customer Alamat', 'rules' => 'required|alpha_numeric'],
            'idKanalPembayaran'         =>  ['label' => 'Id Kanal Pembayaran', 'rules' => 'required|alpha_numeric'],
            'idEkspedisi'               =>  ['label' => 'Id Ekspedisi', 'rules' => 'required|alpha_numeric'],
            'catatan'                   =>  ['label' => 'Catatan', 'rules' => 'required|regex_match[/^[a-zA-Z0-9\s\p{P}]+$/u]|min_length[5]|max_length[255]'],
            'dataProduk.*.idProduk'     =>  ['label' => 'Produk', 'rules' => 'required|alpha_numeric'],
            'dataProduk.*.jumlah'       =>  ['label' => 'Jumlah Produk', 'rules' => 'required|numeric|greater_than[0]|min_length[1]|max_length[10]'],
            'dataProduk.*.keterangan'   =>  ['label' => 'Keterangan Produk', 'rules' => 'permit_empty|regex_match[/^[a-zA-Z0-9\s\p{P}]+$/u]|max_length[255]'],
            'ongkosKirim'               =>  ['label' => 'Ongkos Kirim', 'rules' => 'required|numeric|greater_than_equal_to[0]|min_length[1]|max_length[10]'],
        ];

        $messages   =   [
            'idRegional'  => [
                'required'      => 'Regional yang dipilih tidak valid, silakan coba lagi nanti',
                'alpha_numeric' => 'Regional yang dipilih tidak valid, silakan coba lagi nanti'
            ],
            'idCustomerAlamat' => [
                'required'      => 'Alamat konsumen yang dipilih tidak valid, silakan coba lagi nanti',
                'alpha_numeric' => 'Alamat konsumen yang dipilih tidak valid, silakan coba lagi nanti'
            ],
            'idKanalPembayaran' => [
                'required'      => 'Kanal pembayaran yang dipilih tidak valid, silakan coba lagi nanti',
                'alpha_numeric' => 'Kanal pembayaran yang dipilih tidak valid, silakan coba lagi nanti'
            ],
            'idEkspedisi' => [
                'required'      => 'Ekspedisi yang dipilih tidak valid, silakan coba lagi nanti',
                'alpha_numeric' => 'Ekspedisi yang dipilih tidak valid, silakan coba lagi nanti'
            ],
            'dataProduk.*.idProduk' =>  [
                'required'      =>  'Produk dipilih tidak valid, silakan periksa kembali',
                'alpha_numeric' =>  'Produk yang dipilih tidak valid, silakan periksa kembali'
            ]
        ];

        if(!$this->validate($rules, $messages)) return $this->fail($this->validator->getErrors());

        $transaksiModel     =   new TransaksiModel();
        $mainOperation      =   new MainOperation();
        $idRegional         =   $this->request->getVar('idRegional');
        $idRegional         =   isset($idRegional) && $idRegional != "" ? hashidDecode($idRegional) : 0;
        $idCustomerAlamat   =   $this->request->getVar('idCustomerAlamat');
        $idCustomerAlamat   =   isset($idCustomerAlamat) && $idCustomerAlamat != "" ? hashidDecode($idCustomerAlamat) : 0;
        $idKanalPembayaran  =   $this->request->getVar('idKanalPembayaran');
        $idKanalPembayaran  =   isset($idKanalPembayaran) && $idKanalPembayaran != "" ? hashidDecode($idKanalPembayaran) : 0;
        $idEkspedisi        =   $this->request->getVar('idEkspedisi');
        $idEkspedisi        =   isset($idEkspedisi) && $idEkspedisi != "" ? hashidDecode($idEkspedisi) : 0;
        $catatan            =   $this->request->getVar('catatan');
        $dataProduk         =   $this->request->getVar('dataProduk');
        $ongkosKirim        =   $this->request->getVar('ongkosKirim');
        $totalBarang        =   0;
        $totalNominalBarang =   0;

        foreach ($dataProduk as $produk) {
            $idProduk  =   isset($produk->idProduk) && $produk->idProduk != "" ? hashidDecode($produk->idProduk) : 0;
            $jumlah    =   isset($produk->jumlah) && $produk->jumlah != "" ? $produk->jumlah : 0;

            if($idProduk == 0 || $jumlah == 0) return throwResponseNotAcceptable('Produk yang dipilih tidak valid, silakan periksa kembali');
            $detailProduk   =   $transaksiModel->getDetailProdukById($idProduk);
            if(!$detailProduk) return throwResponseNotAcceptable('Produk yang dipilih tidak valid, silakan periksa kembali');

            $hargaProduk        =   $detailProduk['HARGAJUAL'];
            $totalNominalBarang +=   $hargaProduk * $jumlah;
            $totalBarang++;
        }

        $detailCustomerAlamat   =   $transaksiModel->getDetailCustomerAlamatById($idCustomerAlamat);
        if(!$detailCustomerAlamat) return throwResponseNotAcceptable('Alamat pengiriman yang dipilih tidak valid, silakan periksa kembali');
        $alamatNama             =   $detailCustomerAlamat['NAMAALAMAT'];
        $alamatKirim            =   $detailCustomerAlamat['ALAMATKIRIM'];
        $penerimaNama           =   $detailCustomerAlamat['NAMAPENERIMA'];
        $penerimaNomorTelepon   =   $detailCustomerAlamat['NOMORHPPENERIMA'];

        $arrInsertDataRekap =   [
            'IDREGIONAL'            =>  $idRegional,
            'IDCUSTOMER'            =>  $idCustomer,
            'IDCUSTOMERALAMAT'      =>  $idCustomerAlamat,
            'IDKANALPEMBAYARAN'     =>  $idKanalPembayaran,
            'IDEKSPEDISI'           =>  $idEkspedisi,
            'IDSTATUSTRANSAKSI'     =>  1,
            'NOMORTRANSAKSI'        =>  $this->generateNomorTransaksi(),
            'ALAMATNAMA'            =>  $alamatNama,
            'ALAMATKIRIM'           =>  $alamatKirim,
            'PENERIMANAMA'          =>  $penerimaNama,
            'PENERIMANOMORTELEPON'  =>  $penerimaNomorTelepon,
            'CATATAN'               =>  $catatan,
            'TOTALBARANG'           =>  $totalBarang,
            'TOTALNOMINALBARANG'    =>  $totalNominalBarang,
            'TOTALNOMINALONGKIR'    =>  $ongkosKirim,
            'TOTALNOMINALDISKON'    =>  0,
            'TOTALNOMINALBAYAR'     =>  $totalNominalBarang + $ongkosKirim,
            'INPUTTANGGALWAKTU'     =>  date('Y-m-d H:i:s')
        ];

        $procInsertTransaksiRekap   =   $mainOperation->insertDataTable('t_transaksirekap', $arrInsertDataRekap);
        if(!$procInsertTransaksiRekap['status']) return switchMySQLErrorCode($procInsertTransaksiRekap['errCode']);
        $idPenjualanRekap       =   $procInsertTransaksiRekap['insertID'];

        foreach ($dataProduk as $produk) {
            $idProduk           =   isset($produk->idProduk) && $produk->idProduk != "" ? hashidDecode($produk->idProduk) : 0;
            $jumlah             =   isset($produk->jumlah) && $produk->jumlah != "" ? $produk->jumlah : 0;
            $keterangan         =   isset($produk->keterangan) && $produk->keterangan != "" ? $produk->keterangan : '';
            $detailProduk       =   $transaksiModel->getDetailProdukById($idProduk);
            $hargaProduk        =   $detailProduk['HARGAJUAL'];
            $totalNominalBarang =   $hargaProduk * $jumlah;

            $arrInsertDataBarang=   [
                'IDTRANSAKSIREKAP'  =>  $idPenjualanRekap,
                'IDPRODUK'          =>  $idProduk,
                'KETERANGAN'        =>  $keterangan,
                'JUMLAH'            =>  $jumlah,
                'NOMINALSATUAN'     =>  $hargaProduk,
                'NOMINALTOTAL'      =>  $totalNominalBarang
            ];

            $procInsertTransaksiBarang    =   $mainOperation->insertDataTable('t_transaksibarang', $arrInsertDataBarang);
            if(!$procInsertTransaksiBarang['status']) return switchMySQLErrorCode($procInsertTransaksiBarang['errCode']);
        }

        $arrInsertDataRiwayat   =   [
            'IDTRANSAKSIREKAP'  =>  $idPenjualanRekap,
            'IDSTATUSTRANSAKSI' =>  $idProduk,
            'INPUTUSER'         =>  $this->userData->nama." (Konsumen)",
            'TANGGALWAKTU'      =>  date('Y-m-d H:i:s')
        ];

        $mainOperation->insertDataTable('t_transaksiriwayat', $arrInsertDataRiwayat);

        return throwResponseOK('Transaksi berhasil disimpan, silakan lanjutkan ke proses pembayaran', [
            "idTransaksiRekap"  =>  hashidEncode($idPenjualanRekap)
        ]);
    }

    private function generateNomorTransaksi()
    {
        $kodeTransaksi    =   'MPC-';
        $kodeTanggal      =   date('ym');
        $kodeUnik         =   generateRandomCharacter(4, 3);
        $nomorUnik        =   generateRandomCharacter(3, 1);

        return $kodeTransaksi.$kodeUnik.$kodeTanggal.$nomorUnik;
    }
}