<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

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

    public function logoMerk($nameFile)
    {
        $fullFilePath   =   PATH_STORAGE_FILE_LOGO_MERK.$nameFile;
        $isDefault      =   strpos($nameFile, 'default') !== false;
        $defaultFilePath=   PATH_STORAGE_FILE_LOGO_MERK  .'default.jpg';

        return $this->setReturnAssets($nameFile, $fullFilePath, $isDefault, $defaultFilePath);
    }

    public function logoMarketplace($nameFile)
    {
        $fullFilePath   =   PATH_STORAGE_FILE_LOGO_MARKETPLACE.$nameFile;
        $isDefault      =   strpos($nameFile, 'default') !== false;
        $defaultFilePath=   PATH_STORAGE_FILE_LOGO_MARKETPLACE  .'default.jpg';

        return $this->setReturnAssets($nameFile, $fullFilePath, $isDefault, $defaultFilePath);
    }

    public function cardLevelLoyalti($namaFile)
    {
        $fullFilePath   =   PATH_STORAGE_FILE_CARD_LEVEL_LOYALTI.$namaFile;
        $isDefault      =   strpos($namaFile, 'default') !== false;
        $defaultFilePath=   PATH_STORAGE_FILE_CARD_LEVEL_LOYALTI  .'default.jpg';

        return $this->setReturnAssets($namaFile, $fullFilePath, $isDefault, $defaultFilePath);
    }

    public function iconLevelLoyalti($namaFile)
    {
        $fullFilePath   =   PATH_STORAGE_FILE_ICON_LEVEL_LOYALTI.$namaFile;
        $isDefault      =   strpos($namaFile, 'default') !== false;
        $defaultFilePath=   PATH_STORAGE_FILE_ICON_LEVEL_LOYALTI  .'default.png';

        return $this->setReturnAssets($namaFile, $fullFilePath, $isDefault, $defaultFilePath);
    }

    public function pdfKatalogThumbnail($namaFile)
    {
        $fullFilePath   =   PATH_STORAGE_FILE_PDF_KATALOG_THUMBNAIL.$namaFile;
        $isDefault      =   strpos($namaFile, 'default') !== false;
        $defaultFilePath=   PATH_STORAGE_FILE_PDF_KATALOG_THUMBNAIL  .'default.jpg';

        return $this->setReturnAssets($namaFile, $fullFilePath, $isDefault, $defaultFilePath);
    }

    public function pdfKatalogFile($namaFile)
    {
        $fullFilePath   =   PATH_STORAGE_FILE_PDF_KATALOG_FILE.$namaFile;
        $isDefault      =   strpos($namaFile, 'default') !== false;
        $defaultFilePath=   PATH_STORAGE_FILE_PDF_KATALOG_FILE  .'default.pdf';

        return $this->setReturnAssets($namaFile, $fullFilePath, $isDefault, $defaultFilePath);
    }

    public function photoBarang($nameFile)
    {
        $fullFilePath   =   PATH_STORAGE_PHOTO_BARANG.$nameFile;
        $isDefault      =   strpos($nameFile, 'default') !== false;
        $defaultFilePath=   PATH_STORAGE_PHOTO_BARANG  .'noimage.jpg';

        return $this->setReturnAssets($nameFile, $fullFilePath, $isDefault, $defaultFilePath);
    }

    public function imageMarketing($nameFile)
    {
        $fullFilePath   =   PATH_STORAGE_IMAGE_MARKETING.$nameFile;
        $isDefault      =   strpos($nameFile, 'default') !== false;
        $defaultFilePath=   PATH_STORAGE_IMAGE_MARKETING  .'default.jpg';

        return $this->setReturnAssets($nameFile, $fullFilePath, $isDefault, $defaultFilePath);
    }

    public function customerAvatar($nameFile)
    {
        $fullFilePath   =   PATH_STORAGE_CUSTOMER_AVATAR.$nameFile;
        $isDefault      =   strpos($nameFile, 'default') !== false;
        $defaultFilePath=   PATH_STORAGE_CUSTOMER_AVATAR  .'default.jpg';

        return $this->setReturnAssets($nameFile, $fullFilePath, $isDefault, $defaultFilePath);
    }

    public function customerQRImage($tokenQRImage)
    {
        $writer =   new PngWriter();
        $qrCode =   new QrCode(
            data: $tokenQRImage,
            size: 300,
            margin: 1
        );

        $result =   $writer->write($qrCode);

        return $this->response
            ->setHeader('Content-Type', 'image/png')
            ->setHeader('Content-Disposition', 'inline; filename="' . $tokenQRImage . '.png"')
            ->setBody($result->getString());
    }

    public function customerSlideBoarding($nameFile)
    {
        $fullFilePath   =   PATH_STORAGE_CUSTOMER_SLIDE_BOARDING.$nameFile;
        $isDefault      =   strpos($nameFile, 'defaultBoarding') !== false;
        $defaultFilePath=   PATH_STORAGE_CUSTOMER_SLIDE_BOARDING  .'defaultBoarding.png';

        return $this->setReturnAssets($nameFile, $fullFilePath, $isDefault, $defaultFilePath);
    }

    public function customerSlideBanner($nameFile)
    {
        $fullFilePath   =   PATH_STORAGE_CUSTOMER_SLIDE_BANNER.$nameFile;
        $isDefault      =   strpos($nameFile, 'default') !== false;
        $defaultFilePath=   PATH_STORAGE_CUSTOMER_SLIDE_BANNER  .'default.jpg';

        return $this->setReturnAssets($nameFile, $fullFilePath, $isDefault, $defaultFilePath);
    }

    public function customerVideoCaraPemasangan($nameFile)
    {
        $fullFilePath   =   PATH_STORAGE_CUSTOMER_VIDEO_CARA_PASANG.$nameFile;
        $isDefault      =   strpos($nameFile, 'default') !== false;
        $defaultFilePath=   PATH_STORAGE_CUSTOMER_VIDEO_CARA_PASANG  .'thumbnailDefault.png';

        return $this->setReturnAssets($nameFile, $fullFilePath, $isDefault, $defaultFilePath);
    }

    public function customerMerk($nameFile)
    {
        $fullFilePath   =   PATH_STORAGE_CUSTOMER_MERK.$nameFile;
        $isDefault      =   strpos($nameFile, 'default') !== false;
        $defaultFilePath=   PATH_STORAGE_CUSTOMER_MERK  .'default.jpg';

        return $this->setReturnAssets($nameFile, $fullFilePath, $isDefault, $defaultFilePath);
    }

    public function customerProduk($nameFile)
    {
        $fullFilePath   =   PATH_STORAGE_CUSTOMER_PRODUK.$nameFile;
        $isDefault      =   strpos($nameFile, 'default') !== false;
        $defaultFilePath=   PATH_STORAGE_CUSTOMER_PRODUK  .'default.jpg';

        return $this->setReturnAssets($nameFile, $fullFilePath, $isDefault, $defaultFilePath);
    }

    private function setReturnAssets($nameFile, $fullFilePath, $isDefault, $defaultFilePath)
    {
        if (!is_file($fullFilePath) || !file_exists($fullFilePath) || $isDefault !== false) $fullFilePath   =   $defaultFilePath;

        $mimeType       =   mime_content_type($fullFilePath);
        $fileContent    =   file_get_contents($fullFilePath);

        return $this->response
            ->setHeader('Content-Type', $mimeType)
            ->setHeader('Content-Disposition', 'inline; filename="' . $nameFile . '"')
            ->setBody($fileContent);
    }
}