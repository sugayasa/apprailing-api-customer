<?php

namespace App\Models;
use CodeIgniter\Model;

class DashboardModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 't_salesorderrekap';
    protected $primaryKey       = 'IDSALESORDERREKAP';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [];

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
    
    public function getDataPenjualanPerMerkPerTanggal($arrDatabaseRegional, $tanggalPeriodeAwal, $tanggalPeriodeAkhir)
    {	
        $unionQuery =   "";
        foreach($arrDatabaseRegional as $namaDatabaseRegional) {
            $subQuery   =   $this->db->table($namaDatabaseRegional.'.t_salesorderdetail AS A');
            $subQuery->select("C.IDMERK, D.NAMAMERK, DATE_FORMAT(B.TANGGALWAKTU, '%d') AS TANGGAL, SUM(A.HARGATOTAL) AS HARGATOTAL");
            $subQuery->join($namaDatabaseRegional.'.t_salesorderrekap AS B', 'B.IDSALESORDERREKAP = A.IDREKAPSALESORDER', 'LEFT');
            $subQuery->join(APP_MAIN_DATABASE_NAME.'.m_barang AS C', 'C.IDBARANG = A.IDBARANG', 'LEFT');
            $subQuery->join(APP_MAIN_DATABASE_NAME.'.m_merk AS D', 'D.IDMERK = C.IDMERK', 'LEFT');
            $subQuery->where('DATE(B.TANGGALWAKTU) >= ', $tanggalPeriodeAwal);
            $subQuery->where('DATE(B.TANGGALWAKTU) <= ', $tanggalPeriodeAkhir);
            $subQuery->where('B.STATUSPENAWARAN != ', -1);
            $subQuery->where('B.IDMARKETPLACE != ', 0);
            $subQuery->groupBy('C.IDMERK, DATE_FORMAT(B.TANGGALWAKTU, "%d")');
            $subQuery   =   $subQuery->getCompiledSelect();
            $unionQuery .=   "({$subQuery}) UNION ALL ";
        }

        $unionQuery =   rtrim($unionQuery, " UNION ALL ");
        $finalQuery =   $this->db->query(
                            "SELECT NAMAMERK, TANGGAL, SUM(HARGATOTAL) AS HARGATOTAL
                            FROM ({$unionQuery}) AS A
                            GROUP BY IDMERK, TANGGAL
                            ORDER BY TANGGAL"
                        );
        $result     =   $finalQuery->getResultObject();

        if(is_null($result)) return [];
        return $result;
	}
    
    public function getDataStatistikMerkRegional($arrDatabaseRegional, $tanggalPeriodeAwal, $tanggalPeriodeAkhir)
    {	
        $unionQuery =   "";
        foreach($arrDatabaseRegional as $namaDatabaseRegional) {
            $subQuery   =   $this->db->table($namaDatabaseRegional.'.t_salesorderdetail AS A');
            $subQuery->select('C.IDMERK, COUNT(DISTINCT(B.IDSALESORDERREKAP)) AS TOTALSALESORDER, IFNULL(SUM(A.HARGATOTAL), 0) AS TOTALNOMINAL');
            $subQuery->join($namaDatabaseRegional.'.t_salesorderrekap AS B', 'B.IDSALESORDERREKAP = A.IDREKAPSALESORDER', 'LEFT');
            $subQuery->join(APP_MAIN_DATABASE_NAME.'.m_barang AS C', 'C.IDBARANG = A.IDBARANG', 'LEFT');
            $subQuery->where('DATE(B.TANGGALWAKTU) >= ', $tanggalPeriodeAwal);
            $subQuery->where('DATE(B.TANGGALWAKTU) <= ', $tanggalPeriodeAkhir);
            $subQuery->where('B.STATUSPENAWARAN != ', -1);
            $subQuery->where('IDMARKETPLACE != ', 0);
            $subQuery->groupBy('C.IDMERK');
            $subQuery   =   $subQuery->getCompiledSelect();
            $unionQuery .=   "({$subQuery}) UNION ALL ";
        }

        $unionQuery =   rtrim($unionQuery, " UNION ALL ");
        $finalQuery =   $this->db->query(
                            "SELECT IDMERK, SUM(TOTALSALESORDER) AS TOTALSALESORDER, SUM(TOTALNOMINAL) AS TOTALNOMINAL
                            FROM ({$unionQuery}) AS A
                            WHERE IDMERK IS NOT NULL GROUP BY IDMERK"
                        );
        $result     =   $finalQuery->getResultObject();

        if(is_null($result)) return [];
        return $result;
	}
    
    public function getDataStatistikMarketplaceRegional($namaDatabaseRegional, $arrIdMediaMarketing, $tanggalPeriodeAwal, $tanggalPeriodeAkhir)
    {	
        $this->select("IDMARKETPLACE, COUNT(IDSALESORDERREKAP) AS TOTALSALESORDER, IFNULL(SUM(GRANDTOTALHARGA), 0) AS TOTALNOMINAL");
        $this->from($namaDatabaseRegional.'.t_salesorderrekap', true);
        $this->whereIn('IDMARKETPLACE', $arrIdMediaMarketing);
        $this->where('DATE(TANGGALWAKTU) >= ', $tanggalPeriodeAwal);
        $this->where('DATE(TANGGALWAKTU) <= ', $tanggalPeriodeAkhir);
        $this->where('STATUSPENAWARAN != ', -1);
        $this->groupBy('IDMARKETPLACE');

        $result =   $this->get()->getResultObject();
        if(is_null($result)) return [];
        return $result;
	}
    
    public function getDataStatistikPerRegional($namaDatabaseRegional, $tanggalPeriodeAwal, $tanggalPeriodeAkhir)
    {	
        $this->select("COUNT(IDSALESORDERREKAP) AS TOTALSALESORDER, IFNULL(SUM(GRANDTOTALHARGA), 0) AS TOTALNOMINAL");
        $this->from($namaDatabaseRegional.'.t_salesorderrekap', true);
        $this->where('IDMARKETPLACE != ', 0);
        $this->where('DATE(TANGGALWAKTU) >= ', $tanggalPeriodeAwal);
        $this->where('DATE(TANGGALWAKTU) <= ', $tanggalPeriodeAkhir);
        $this->where('STATUSPENAWARAN != ', -1);
        $this->limit(1);

        $result =   $this->first();

        if(is_null($result)) return [
            "TOTALSALESORDER"   =>  0,
            "TOTALNOMINAL"      =>  0,
        ];
        return $result;
	}
    
    public function getDataBarangBestSeller($arrDatabaseRegional, $tanggalPeriodeAwal, $tanggalPeriodeAkhir)
    {	
        $unionQuery =   "";
        foreach($arrDatabaseRegional as $namaDatabaseRegional) {
            $subQuery   =   $this->db->table($namaDatabaseRegional.'.t_salesorderdetail AS A');
            $subQuery->select(
                "C.IMAGE1, D.NAMAMERK, E.KATEGORIBARANG, CONCAT(C.NAMABARANG, ' ', C.KODEBARANG) AS NAMAKODEBARANG, SUM(A.HARGATOTAL) AS HARGATOTAL,
                SUM(A.JUMLAHPCS) AS JUMLAHPCS, A.IDBARANG"
            );
            $subQuery->join($namaDatabaseRegional.'.t_salesorderrekap AS B', 'B.IDSALESORDERREKAP = A.IDREKAPSALESORDER', 'LEFT');
            $subQuery->join(APP_MAIN_DATABASE_NAME.'.m_barang AS C', 'C.IDBARANG = A.IDBARANG', 'LEFT');
            $subQuery->join(APP_MAIN_DATABASE_NAME.'.m_merk AS D', 'D.IDMERK = C.IDMERK', 'LEFT');
            $subQuery->join(APP_MAIN_DATABASE_NAME.'.m_barangkategori AS E', 'E.IDKATEGORIBARANG = C.IDKATEGORI', 'LEFT');
            $subQuery->where('DATE(B.TANGGALWAKTU) >= ', $tanggalPeriodeAwal);
            $subQuery->where('DATE(B.TANGGALWAKTU) <= ', $tanggalPeriodeAkhir);
            $subQuery->where('B.STATUSPENAWARAN != ', -1);
            $subQuery->where('B.IDMARKETPLACE != ', 0);
            $subQuery->groupBy('A.IDBARANG');
            $subQuery   =   $subQuery->getCompiledSelect();
            $unionQuery .=   "({$subQuery}) UNION ALL ";
        }

        $unionQuery =   rtrim($unionQuery, " UNION ALL ");
        $finalQuery =   $this->db->query(
                            "SELECT IMAGE1, NAMAMERK, KATEGORIBARANG, NAMAKODEBARANG, SUM(HARGATOTAL) AS HARGATOTAL, SUM(JUMLAHPCS) AS JUMLAHPCS
                            FROM ({$unionQuery}) AS A
                            GROUP BY IDBARANG
                            ORDER BY SUM(JUMLAHPCS) DESC
                            LIMIT 6"
                        );
        $result     =   $finalQuery->getResultObject();

        if(is_null($result)) return [];
        return $result;
	}
    
    public function getDataHistoriSalesOrder($arrDatabaseRegional)
    {	
        $unionQuery =   "";
        foreach($arrDatabaseRegional as $namaDatabaseRegional) {
            $subQuery   =   $this->db->table($namaDatabaseRegional.'.t_salesorderrekap AS A');
            $subQuery->select(
                "'".$namaDatabaseRegional."' AS NAMADATABASE, B.FILELOGOSQUARE, A.NOMORTRANSAKSIMP, A.NAMACUSTOMERMP, A.NAMAEKSPEDISIMP,
                A.NOMORRESIMP, A.STATUSPENAWARAN, A.GRANDTOTALHARGA, A.TANGGALWAKTU, A.IDMARKETPLACE"
            );
            $subQuery->join(APP_MAIN_DATABASE_NAME.'.m_marketingmedia AS B', 'B.IDMEDIAMARKETING = A.IDMARKETPLACE', 'LEFT');
            $subQuery->where('A.STATUSPENAWARAN != ', -1);
            $subQuery->where('A.IDMARKETPLACE != ', 0);
            $subQuery->orderBy('A.TANGGALWAKTU', 'DESC');
            $subQuery->limit(10);
            $subQuery   =   $subQuery->getCompiledSelect();
            $unionQuery .=   "({$subQuery}) UNION ALL ";
        }

        $unionQuery =   rtrim($unionQuery, " UNION ALL ");
        $finalQuery =   $this->db->query(
                            "SELECT A.FILELOGOSQUARE, B.NAMAKOTA, A.NOMORTRANSAKSIMP, A.NAMACUSTOMERMP, A.NAMAEKSPEDISIMP, A.NOMORRESIMP,
                                    A.STATUSPENAWARAN, A.GRANDTOTALHARGA, DATE_FORMAT(A.TANGGALWAKTU, '%d %M %Y %H:%i') AS TANGGALWAKTU, A.IDMARKETPLACE
                            FROM ({$unionQuery}) AS A
                            LEFT JOIN ".APP_MAIN_DATABASE_NAME.".a_kota AS B ON A.NAMADATABASE = B.NAMADATABASE AND B.KOTAUTAMA = 1
                            ORDER BY A.TANGGALWAKTU DESC
                            LIMIT 5"
                        );
        $result     =   $finalQuery->getResultObject();

        if(is_null($result)) return [];
        return $result;
	}
}