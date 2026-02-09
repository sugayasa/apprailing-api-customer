<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Libraries\FirebaseRTDB;

class MainOperation extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'ci_sessions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['ip_address', 'timestamp', 'data'];

    public function execQueryWithLimit($queryString, $page, $dataPerPage)
    {
		$startid    =	($page * 1 - 1) * $dataPerPage;
        $query      =   $this->query($queryString." LIMIT ".$startid.", ".$dataPerPage);

        return $query->getResult();
    }

    public function generateResultPagination($result, $basequery, $keyfield, $page, $dataperpage)
    {
        $startid	=	($page * 1 - 1) * $dataperpage;
		$datastart	=	$startid + 1;
		$dataend	=	$datastart + $dataperpage - 1;
		$query      =   $this->query("SELECT IFNULL(COUNT(".$keyfield."), 0) AS TOTAL FROM (".$basequery.") AS A");
		
		$row		=	$query->getRow();
		$datatotal	=	$row->TOTAL;
		$pagetotal	=	ceil($datatotal/$dataperpage);
		$datastart	=	$pagetotal == 0 ? 0 : $startid + 1;
		$startnumber=	$pagetotal == 0 ? 0 : ($page-1) * $dataperpage + 1;
		$dataend	=	$dataend > $datatotal ? $datatotal : $dataend;
		
		return array("data"=>$result ,"dataStart"=>$datastart, "dataEnd"=>$dataend, "dataTotal"=>$datatotal, "pageTotal"=>$pagetotal, "startNumber"=>$startnumber);
    }

    public function generatePageProperty($page, $dataPerPage, $dataNumberTotal) : array
    {
        if($dataNumberTotal == 0) return $this->generateEmptyPageProperty();
        
        $dataNumberStart=	($page * 1 - 1) * $dataPerPage + 1;
		$dataNumberEnd	=	$dataNumberStart + $dataPerPage - 1;

		$pageTotal      =	ceil($dataNumberTotal/$dataPerPage);
		$dataNumberStart=	$pageTotal == 0 ? 0 : $dataNumberStart;
		$dataNumberEnd	=	$dataNumberEnd > $dataNumberTotal ? $dataNumberTotal : $dataNumberEnd;
        return ["dataNumberStart"=>$dataNumberStart, "dataNumberEnd"=>$dataNumberEnd, "dataNumberTotal"=>$dataNumberTotal, "pageTotal"=>$pageTotal];
    }

    public function generateEmptyPageProperty() : array
    {
        return ["dataNumberStart"=>0, "dataNumberEnd"=>0, "dataNumberTotal"=>0, "pageTotal"=>0];
    }

	public function generateEmptyResult()
    {
		return array("data"=>[], "datastart"=>0, "dataend"=>0, "datatotal"=>0, "pagetotal"=>0);
	}

    public function insertDataTable($tableName, $arrInsert)
    {
        $db     =   \Config\Database::connect();
        try {
            $table  =   $db->table($tableName);
            foreach($arrInsert as $field => $value){
                $table->set($field, $value);
            }
            $table->insert();

            $insertID       =   $db->insertID();
            $affectedRows   =   $db->affectedRows();

            if($insertID > 0 || $affectedRows > 0) return ["status"=>true, "errCode"=>false, "insertID"=>$insertID];
            return ["status"=>false, "errCode"=>1329];
        } catch (\Throwable $th) {
            $error		    =	$db->error();
            $errorCode	    =	$error['code'] == 0 ? 1329 : $error['code'];
            return ["status"=>false, "errCode"=>$errorCode, "errorMessages"=>$th];
        }
    }

    public function insertIgnoreDataTable($tableName, $arrInsert)
    {
        $db     =   \Config\Database::connect();
        try {
            $table  =   $db->table($tableName);
            foreach($arrInsert as $field => $value){
                $table->set($field, $value);
            }
            $queryString    = $table->getCompiledInsert();
            $queryString    = str_replace('INSERT INTO', 'INSERT IGNORE INTO', $queryString);

            $db->query($queryString);
            $insertID       =   $db->insertID();
            $affectedRows   =   $db->affectedRows();

            if($insertID > 0 || $affectedRows > 0) return ["status"=>true, "errCode"=>false, "insertID"=>$insertID];
            return ["status"=>false, "errCode"=>1329];
        } catch (\Throwable $th) {
            $error		    =	$db->error();
            $errorCode	    =	$error['code'] == 0 ? 1329 : $error['code'];
            return ["status"=>false, "errCode"=>$errorCode, "errorMessages"=>$th];
        }
    }

    public function insertDataBatchTable($tableName, $arrInsert)
    {
        $db     =   \Config\Database::connect();
        try {
            $table          =   $db->table($tableName);
            $table->insertBatch($arrInsert);
            $affectedRows   =   $db->affectedRows();

            if($affectedRows > 0) return ["status"=>true, "errCode"=>false];
            return ["status"=>false, "errCode"=>1329];
        } catch (\Throwable $th) {
            $error		    =	$db->error();
            $errorCode	    =	$error['code'] == 0 ? 1329 : $error['code'];
            return ["status"=>false, "errCode"=>$errorCode, "errorMessages"=>$th->getMessage()];
        }
    }

    public function updateDataTable($tableName, $arrUpdate, $arrWhere)
    {
        $db     =   \Config\Database::connect();
        try {
            $table  =   $db->table($tableName);
            foreach($arrUpdate as $field => $value){
                $table->set($field, $value);
            }

            foreach($arrWhere as $field => $value){
                if(is_array($value)){
                    $table->whereIn($field, $value);
                } else {
                    $table->where($field, $value);
                }
            }
            $table->update();

            $affectedRows   =   $db->affectedRows();
            if($affectedRows > 0) return ["status"=>true, "errCode"=>false];
            return ["status"=>false, "errCode"=>1329, "queryString"=>$db->getLastQuery()];
        } catch (\Throwable $th) {
            $error		    =	$db->error();
            $errorCode	    =	$error['code'] == 0 ? 1329 : $error['code'];
            return ["status"=>false, "error"=>$error, "errCode"=>$errorCode, "errorMessages"=>$th, "queryString"=>$db->getLastQuery()];
        }
        return ["status"=>false, "errCode"=>false];
    }

    public function deleteDataTable($tableName, $arrWhere)
    {
        $db     =   \Config\Database::connect();
        try {
            $table  =   $db->table($tableName);

            foreach($arrWhere as $field => $value){
                if(is_array($value)){
                    $table->whereIn($field, $value);
                } else {
                    $table->where($field, $value);
                }
            }
            $table->delete();

            $affectedRows   =   $db->affectedRows();
            if($affectedRows > 0) return ["status"=>true, "affectedRows"=>$affectedRows];
            return ["status"=>false, "errCode"=>1329];
        } catch (\Throwable $th) {
            $error		    =	$db->error();
            $errorCode	    =	$error['code'] == 0 ? 1329 : $error['code'];
            return ["status"=>false, "errCode"=>$errorCode, "errorMessages"=>$th];
        }
    }

    public function isDataExist($tableName, $arrField)
    {
        $db   =   \Config\Database::connect();
        $table=   $db->table($tableName);
        foreach($arrField as $field => $value){
            if(is_array($value)){
                $table->whereIn($field, $value);
            } else {
                $table->where($field, $value);
            }
        }
        
        $query  =   $table->get();
        return $query->getNumRows() > 0 ? $query->getRowArray() : false;
    }

    public function getDataSystemSetting($idSystemSetting)
    {	
        $this->select("DATASETTING");
        $this->from('a_systemsettings', true);
        $this->where('IDSYSTEMSETTINGS', $idSystemSetting);
        $this->limit(1);

        $result =   $this->first();

        if(is_null($result)) return '[]';
        return $result['DATASETTING'];
    }

    public function getDataDetailRegional()
    {	
        $this->select("IDKOTA, NAMAKOTA, INISIALKOTA, NAMADATABASE, CLASSWARNA");
        $this->from(APP_MAIN_DATABASE_NAME.'.a_kota', true);
        $this->where('KOTAUTAMA', 1);
        $this->where('STATUS', 1);
        $this->orderBy('IDKOTA', 'ASC');

        $result =   $this->get()->getResultObject();
        if(is_null($result)) return [];
        return $result;
    }

    public function getDataMerk()
    {	
        $this->select("IDMERK, KODEMERK, NAMAMERK, FILELOGO, HEXWARNA");
        $this->from(APP_MAIN_DATABASE_NAME.'.m_merk', true);
        $this->orderBy('IDMERK', 'ASC');

        $result =   $this->get()->getResultObject();
        if(is_null($result)) return [];
        return $result;
    }

    public function getDataMarketplaceAktif()
    {	
        $this->select("IDMEDIAMARKETING, MEDIAMARKETING AS NAMAMARKETPLACE, FILELOGO");
        $this->from(APP_MAIN_DATABASE_NAME.'.m_marketingmedia', true);
        $this->where('STATUS', 1);
        $this->orderBy('MEDIAMARKETING', 'ASC');

        $result =   $this->get()->getResultObject();
        if(is_null($result)) return [];
        return $result;
    }
}