<?php

namespace App\Models;
use CodeIgniter\Model;

class DashboardModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 't_slidebanner';
    protected $primaryKey       = 'IDSLIDEBANNER';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['IDSLIDEBANNER', 'JUDUL', 'DESKRIPSI', 'IMAGE', 'INPUTUSER', 'INPUTTANGGALWAKTU', 'STATUS'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getLoyaltiDetail($idCustomer)
    {
        $this->select(
            "A.IDCUSTOMERLOYALTI, DATE_FORMAT(MIN(A.TANGGALDAFTAR), '%d %M %Y') AS TANGGALDAFTAR, IFNULL(SUM(C.POIN), 0) AS TOTALPOIN,
            B.LOYALTITIER, 0 AS TOTALPOINSELANJUTNYA, '-' AS LOYALTITIERSELANJUTNYA,
            CONCAT('".BASE_URL_ASSETS_ICON_LEVEL_LOYALTI."', B.ICONFILE) AS ICONLOYALTI,
            CONCAT('".BASE_URL_ASSETS_CARD_LEVEL_LOYALTI."', B.CARDFILE) AS CARDLOYALTI"
        );
        $this->from('m_customer AS A', true);
        $this->join('m_customerloyalti AS B', 'A.IDCUSTOMERLOYALTI = B.IDCUSTOMERLOYALTI', 'LEFT');
        $this->join('t_transaksipoin AS C', 'A.IDCUSTOMER = C.IDCUSTOMER', 'LEFT');
        $this->where('A.IDCUSTOMER', $idCustomer);
        $this->groupBy('A.IDCUSTOMER');
        $this->limit(1);

        return $this->get()->getRowArray();
    }

    public function getDetailNextTierLoyalti($idCustomerLoyalti, $totalPoinLoyalti)
    {
        $this->select("LOYALTITIER, MINIMALPOIN");
        $this->from('m_customerloyalti', true);
        $this->where('IDCUSTOMERLOYALTI !=', $idCustomerLoyalti);
        $this->where('MINIMALPOIN >', $totalPoinLoyalti);
        $this->orderBy('MINIMALPOIN', 'ASC');
        $this->limit(1);

        $result =   $this->get()->getRowArray();
        if(is_null($result)) return [
            'LOYALTITIER'   =>  '-',
            'MINIMALPOIN'   =>  0
        ];
        return $result;
    }

    public function getReviewMarketing($idCustomer, $isDeveloper = 0)
    {
        $this->select(
            'NAMAMARKETING, RATING, KOMENTAR, CONCAT("'.BASE_URL_ASSETS_IMAGE_MARKETING.'", IMAGEMARKETING) AS IMAGEMARKETING,
            DATE_FORMAT(TANGGAL, "%d %M %Y") AS TANGGAL'
        );
        $this->from('t_reviewmarketing', true);
        $this->where('IDCUSTOMER', $idCustomer);
        if($isDeveloper == 1) $this->orWhere('IDCUSTOMER', 0);
        $this->orderBy('TANGGALWAKTU', 'DESC');
        $this->limit(1);

        $result =   $this->get()->getRowArray();
        if(is_null($result)) return [];
        return $result;
    }
    
    public function getDataSlideBanner()
    {	
        $this->select('IDSLIDEBANNER, JUDUL,IMAGE');
        $this->from('t_slidebanner', true);
        $this->where('STATUS', 1);
        $this->orderBy('INPUTTANGGALWAKTU', 'DESC');
        $this->limit(8);

        $result =   $this->get()->getResultObject();
        if(is_null($result)) return [];
        return $result;
	}
    
    public function getDataMerk()
    {	
        $this->select('IDMERK, NAMAMERK, LOGO');
        $this->from('m_merk', true);
        $this->where('STATUS', 1);

        $result =   $this->get()->getResultObject();
        if(is_null($result)) return [];
        return $result;
	}
    
    public function getDataOrderTerakhir($idCustomer)
    {	
        $this->select('A.IDTRANSAKSIREKAP, B.STATUSTRANSAKSI, DATE_FORMAT(A.INPUTTANGGALWAKTU, "%d %M %Y %H:%i") AS TANGGALORDER,
                        A.TOTALBARANG, A.NOMORTRANSAKSI, A.TOTALNOMINALBAYAR');
        $this->from('t_transaksirekap AS A', true);
        $this->join('m_statustransaksi AS B', 'A.IDSTATUSTRANSAKSI = B.IDSTATUSTRANSAKSI', 'LEFT');
        $this->where('STATUS', 1);
        $this->where('IDCUSTOMER', $idCustomer);
        $this->orderBy('INPUTTANGGALWAKTU', 'DESC');
        $this->limit(5);

        $result =   $this->get()->getResultObject();
        if(is_null($result)) return false;
        return $result;
	}
    
    public function getDataBarangOrder($idTransaksiRekap)
    {	
        $this->select('B.ARRIMAGE');
        $this->from('t_transaksibarang AS A', true);
        $this->join('t_produk AS B', 'A.IDPRODUK = B.IDPRODUK', 'LEFT');
        $this->where('A.IDTRANSAKSIREKAP', $idTransaksiRekap);

        $result =   $this->get()->getResultObject();
        if(is_null($result)) return false;
        return $result;
	}

    public function getDetailSlideBanner($idSlideBanner)
    {
        $this->select('KONTEN');
        $this->from('t_slidebanner', true);
        $this->where('IDSLIDEBANNER', $idSlideBanner);
        $this->limit(1);

        return $this->get()->getRowArray();
    }

    public function getDataVideoCompanyProfile()
    {
        $this->select(
            "IDVIDEOCOMPANYPROFILE, CONCAT('".BASE_URL_ASSETS_CUSTOMER_VIDEO_COMPANY_PROFILE."', IMAGETHUMBNAIL) AS IMAGETHUMBNAIL"
        );
        $this->from('t_videocompanyprofile', true);
        $this->where('STATUS', 1);
        $this->orderBy('URUTAN', 'ASC');
        $this->limit(4);

        return $this->get()->getResultObject();
    }

    public function getDetailVideoCompanyProfile($idVideoCompanyProfile)
    {
        $this->select('JUDUL, KONTEN, URLVIDEO');
        $this->from('t_videocompanyprofile', true);
        $this->where('IDVIDEOCOMPANYPROFILE', $idVideoCompanyProfile);
        $this->limit(1);

        return $this->get()->getRowArray();
    }

    public function getDataVideoCaraPemasangan()
    {
        $this->select(
            "IDVIDEOCARAPEMASANGAN, JUDUL, CONCAT('".BASE_URL_ASSETS_CUSTOMER_VIDEO_CARA_PASANG."', IMAGETHUMBNAIL) AS IMAGETHUMBNAIL"
        );
        $this->from('t_videocarapemasangan', true);
        $this->where('STATUS', 1);
        $this->orderBy('URUTAN', 'ASC');
        $this->limit(8);

        return $this->get()->getResultObject();
    }

    public function getDetailVideoCaraPemasangan($idVideoCaraPemasangan)
    {
        $this->select('JUDUL, KONTEN, URLVIDEO');
        $this->from('t_videocarapemasangan', true);
        $this->where('IDVIDEOCARAPEMASANGAN', $idVideoCaraPemasangan);
        $this->limit(1);

        return $this->get()->getRowArray();
    }

    public function getTipeSosmedMarketplace()
    {
        $this->select('IDTIPESOSMEDMARKETPLACE, NAMATIPE, CONCAT("'.BASE_URL_ASSETS_CUSTOMER_SOSMED_MARKETPLACE.'", FILEICON) AS FILEICON');
        $this->from('m_tipesosmedmarketplace', true);
        $this->where('STATUS', 1);
        $this->orderBy('URUTAN', 'ASC');

        return $this->get()->getResultObject();
    }

    public function getDataSosmedMarketplace($idTipeSosmedMarketplace)
    {
        $this->select('NAMAAKUN, URL');
        $this->from('t_sosmedmarketplace', true);
        $this->where('IDTIPESOSMEDMARKETPLACE', $idTipeSosmedMarketplace);
        $this->orderBy('URUTAN', 'ASC');

        return $this->get()->getResultObject();
    }
}