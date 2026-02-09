<?php
namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Controllers\BaseController;
use App\Models\MainOperation;
use App\Models\AccessModel;

class Index extends BaseController
{
    use ResponseTrait;
    public function index()
    {
        return $this->failForbidden('[E-AUTH-000] Akses ditolak');
    }

    public function response404()
    {
        return $this->failNotFound('[E-AUTH-404] Tidak ditemukan');
    }

    public function main()
    {
        return view('main');
    }

    public function loginPage()
    {
        return view('login');
    }

    public function mainPage()
    {
        helper(['form']);

        $hardwareID     =   strtoupper($this->request->getVar('hardwareID'));
        $lastPageAlias  =   strtoupper($this->request->getVar('lastPageAlias'));
        $header         =   $this->request->getServer('HTTP_AUTHORIZATION');
        $explodeHeader  =   $header != "" ? explode(' ', $header) : [];
        $token          =   is_array($explodeHeader) && isset($explodeHeader[1]) && $explodeHeader[1] != "" ? $explodeHeader[1] : "";

        if(isset($token) && $token != ""){
            try {
                $dataDecode         =   decodeJWTToken($token);
                $idUserAdmin        =   intval($dataDecode->idUserAdmin);
                $idUserAdminLevel   =   intval($dataDecode->idUserAdminLevel);
                $hardwareIDToken    =   $dataDecode->hardwareID;

                if($idUserAdmin != 0){
                    if(isset($idUserAdminLevel) && $idUserAdminLevel != "" && $idUserAdminLevel != 0){
                        $accessModel    =   new AccessModel();
                        $userAdminDataDB=   $accessModel->getUserAdminDetail($idUserAdmin);

                        if(!$userAdminDataDB || is_null($userAdminDataDB)) return $this->failUnauthorized('[E-AUTH-001.1.0] Token tidak valid - Tidak terdaftar');

                        $hardwareIDDB       =   $userAdminDataDB['HARDWAREID'];
                        $idUserAdminLevel   =   $userAdminDataDB['IDUSERADMINLEVEL'];

                        if($hardwareID == $hardwareIDDB && $hardwareID == $hardwareIDToken){
                            $userAdminData  =   array(
                                "name"      =>   $userAdminDataDB['NAME'],
                                "email"     =>   $userAdminDataDB['EMAIL'],
                                "levelName" =>   $userAdminDataDB['LEVELNAME']
                            );

                            try {
                                $listMenuDB =   $accessModel->getUserAdminMenu($idUserAdminLevel);
                                $menuElement=	$this->menuBuilder($listMenuDB, $lastPageAlias);
                                $htmlRes    =   view(
                                    'mainPage',
                                    array(
                                        "userAdminData"         => $userAdminData,
                                        "menuElement"           => $menuElement,
                                        "allowNotifList"        => []
                                    ),
                                    ['debug' => false]
                                );
                                return $this->setResponseFormat('json')
                                ->respond([
                                    'htmlRes'   =>  $htmlRes
                                ]);
                            } catch (\Throwable $th) {
                                log_message('error', '[E-AUTH-001.1.1] Kesalahan internal. Gagal merespons: ' . $th->getMessage());
                                return $this->failUnauthorized('[E-AUTH-001.1.1] Kesalahan internal. Gagal merespons');
                            }
                        } else {
                            return $this->failUnauthorized('[E-AUTH-001.1.2] Token tidak valid - Hardware ID');
                        }
                    } else {
                        return $this->failUnauthorized('[E-AUTH-001.1.3] Token tidak valid - Level');
                    }
                } else {
                    return $this->failUnauthorized('[E-AUTH-001.1.4] Token tidak valid - User ID');
                }
            } catch (\Throwable $th) {
                return $this->failUnauthorized('[E-AUTH-001.2.0] Token tidak valid');
            }
        } else {
            return $this->failUnauthorized('[E-AUTH-001.2.0] Token tidak valid');
        }
    }

    public function menuBuilder($listMenuDB, $lastPageAlias)
    {
        if($listMenuDB == "" || !is_array($listMenuDB) || empty($listMenuDB)){
			return "";
		} else {			
			$activeGroupMenu        =	"";
            $activeGroupMenuChild   =	0;
			$menuElement	        =	"";				
			foreach($listMenuDB as $indexMenu => $keyMenu){
                $groupName      =   $keyMenu->GROUPNAME;
                $menuName       =   $keyMenu->MENUNAME;
                $menuAlias      =   $keyMenu->MENUALIAS;
                $menuURL        =   $keyMenu->URL;
                $menuIcon       =   $keyMenu->ICON;
                $menuName       =   $keyMenu->MENUNAME;
                $active			=	$lastPageAlias != '' && $lastPageAlias == $menuAlias ? "active" : "";
                $active			=	$active	== '' && $indexMenu == 0 ? 'active' : '';

                if($activeGroupMenu != $groupName){
                    if($activeGroupMenu != "" && $activeGroupMenuChild > 1){
                        $menuElement    .=  '</div></div>';
                    }

                    if($groupName == $menuName){
                        $menuElement    .=  '<div class="menu-item">
                                                <a href="#" class="menu-app-item menu-link" title="'.$menuName.'" data-alias="'.$menuAlias.'" data-url="'.$menuURL.'">
                                                    <span class="menu-icon"><i class="fa '.$menuIcon.'"></i></span><span class="menu-text">'.$menuName.'</span>
                                                </a>
                                            </div>';
                    } else {
                        $menuElement.=  '<div class="menu-item has-sub">
                                            <a href="#" class="menu-link">
                                                <span class="menu-icon"><i class="fa '.$menuIcon.'"></i></span>
                                                <span class="menu-text">'.$groupName.'</span>
                                                <span class="menu-caret"><b class="caret"></b></span>
                                            </a>
                                            <div class="menu-submenu">
                                                <div class="menu-item">
                                                    <a href="#" class="menu-app-item menu-link" title="'.$menuName.'" data-alias="'.$menuAlias.'" data-url="'.$menuURL.'">
                                                        <span class="menu-text">'.$menuName.'</span>
                                                    </a>
                                                </div>';
                    }

                    $activeGroupMenuChild   =   1;
                    $activeGroupMenu        =	$groupName;
                } else {
                    $menuElement.=  '<div class="menu-item">
                                        <a href="#" class="menu-app-item menu-link" title="'.$menuName.'" data-alias="'.$menuAlias.'" data-url="'.$menuURL.'">
                                            <span class="menu-text">'.$menuName.'</span>
                                        </a>
                                    </div>';
                    $activeGroupMenuChild++;
                }
			}
			
            $menuElement    .=  '</div></div>';
			return $menuElement;
		}
    }
}
