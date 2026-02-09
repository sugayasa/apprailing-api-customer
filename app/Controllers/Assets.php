<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Assets extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    use ResponseTrait;
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
        parent::initController($request, $response, $logger);
    }

    public function index()
    {
        return $this->failForbidden('[E-AUTH-000] Forbidden Access');
    }

    public function logoMerk($namaFile)
    {
        $fullFilePath   =   PATH_STORAGE_FILE_LOGO_MERK.$namaFile;
        if (!is_file($fullFilePath) || !file_exists($fullFilePath)) $fullFilePath   =   PATH_STORAGE_FILE_LOGO_MERK  .'default.jpg';

        $mimeType       =   mime_content_type($fullFilePath);
        $fileContent    =   file_get_contents($fullFilePath);

        return $this->response
            ->setHeader('Content-Type', $mimeType)
            ->setHeader('Content-Disposition', 'inline; filename="' . $namaFile . '"')
            ->setBody($fileContent);
    }

    public function logoMarketplace($namaFile)
    {
        $fullFilePath   =   PATH_STORAGE_FILE_LOGO_MARKETPLACE.$namaFile;
        if (!is_file($fullFilePath) || !file_exists($fullFilePath)) $fullFilePath   =   PATH_STORAGE_FILE_LOGO_MARKETPLACE  .'default.jpg';

        $mimeType       =   mime_content_type($fullFilePath);
        $fileContent    =   file_get_contents($fullFilePath);

        return $this->response
            ->setHeader('Content-Type', $mimeType)
            ->setHeader('Content-Disposition', 'inline; filename="' . $namaFile . '"')
            ->setBody($fileContent);
    }

    public function photoBarang($namaFile)
    {
        $fullFilePath   =   PATH_STORAGE_PHOTO_BARANG.$namaFile;
        $isFotoDefault  =   strpos($namaFile, 'default') !== false;
        if (!is_file($fullFilePath) || !file_exists($fullFilePath) || $isFotoDefault !== false) $fullFilePath   =   PATH_STORAGE_PHOTO_BARANG  .'noimage.jpg';

        $mimeType       =   mime_content_type($fullFilePath);
        $fileContent    =   file_get_contents($fullFilePath);

        return $this->response
            ->setHeader('Content-Type', $mimeType)
            ->setHeader('Content-Disposition', 'inline; filename="' . $namaFile . '"')
            ->setBody($fileContent);
    }
}