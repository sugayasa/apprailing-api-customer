<?php
namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Controllers\BaseController;
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
        return $this->failForbidden('[E-AUTH-000] Akses ditolak');
    }

    public function loginPage()
    {
        return $this->failForbidden('[E-AUTH-000] Akses ditolak');
    }
}
