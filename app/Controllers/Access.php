<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\AccessModel;
use App\Models\MainOperation;
use CodeIgniter\I18n\Time;

class Access extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    use ResponseTrait;
    protected $userData, $hardwareIDHeader, $timeZoneOffset, $platform, $currentDateTime;
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
        parent::initController($request, $response, $logger);

        try {
            $this->userData         =   $request->userData;
            $this->hardwareIDHeader =   $request->hardwareIDHeader;
            $this->timeZoneOffset   =   $request->timeZoneOffset;
            $this->platform         =   $request->platform;
            $this->currentDateTime  =   $request->currentDateTime;
        } catch (\Throwable $th) {
            return throwResponseInternalServerError(
                        'Internal server error',
                        [
                            'errorCode' =>  '[E-AUTH-000.0.1]',
                            'message'   =>  ENVIRONMENT === 'development' ? $th->getMessage() : ''
                        ]
                    );
        }
    }

    public function index()
    {
        return $this->failForbidden('[E-AUTH-000] Forbidden Access');
    }

    public function check()
    {
        helper(['firebaseJWT', 'hashid']);
        $headerValidation   =   validateHeadersRequiredParams();

        if (!$headerValidation['valid']) {
            return $this->response->setJSON([
                'status'    =>  'error',
                'errors'    =>  $headerValidation['errors']
            ])->setStatusCode(400);
        }

        $hardwareID         =   strtoupper($headerValidation['data']['HardwareID']);
        $userTimeZoneOffset =   $headerValidation['data']['TimeZoneOffset'];
        $platform           =   $headerValidation['data']['Platform'];
        $header             =   $this->request->getServer('HTTP_AUTHORIZATION');
        $explodeHeader      =   $header != "" ? explode(' ', $header) : [];
        $token              =   is_array($explodeHeader) && isset($explodeHeader[1]) && $explodeHeader[1] != "" ? $explodeHeader[1] : "";
        $timeCreate         =   Time::now(APP_TIMEZONE)->toDateTimeString();
        $statusCode         =   401;
        $responseMsg        =   'Harap masuk/daftar menggunakan nomor telepon atau email untuk melanjutkan';
        $captchaCode        =   generateRandomCharacter(4, 3);

        $userData   =   array(
            "avatar"    =>  BASE_URL_ASSETS_CUSTOMER_AVATAR."default.jpg",
            "nama"      =>  "Guest",
            "email"     =>  "-",
            "nomorHP"   =>  "-",
            "kota"      =>  APP_DEFAULT_ALAMAT_KOTA,
            "propinsi"  =>  APP_DEFAULT_ALAMAT_PROPINSI
        );

        $tokenPayload   =   array(
            "idCustomer"        =>  0,
            "idSession"         =>  0,
            "avatar"            =>  BASE_URL_ASSETS_CUSTOMER_AVATAR."default.jpg",
            "nama"              =>  "Guest",
            "email"             =>  "-",
            "nomorHP"           =>  "-",
            "kota"              =>  APP_DEFAULT_ALAMAT_KOTA,
            "propinsi"          =>  APP_DEFAULT_ALAMAT_PROPINSI,
            "hardwareID"        =>  $hardwareID,
            "userTimeZoneOffset"=>  $userTimeZoneOffset,
            "platform"          =>  $platform,
            "captchaCode"       =>  $captchaCode,
            "otpCode"           =>  "",
            "timeCreate"        =>  $timeCreate
        );

        $defaultToken   =   encodeJWTToken($tokenPayload);
        if(isset($token) && $token != ""){
            try {
                $dataDecode     =   decodeJWTToken($token);
                $idSession      =   intval($dataDecode->idSession);
                $hardwareIDToken=   $dataDecode->hardwareID;
                $timeCreateToken=   $dataDecode->timeCreate;

                if($idSession != 0){
                    $accessModel    =   new AccessModel(); 
                    $sessionDataDB  =   $accessModel->where("IDSESSION", $idSession)->first();

                    if(!$sessionDataDB || is_null($sessionDataDB)){
                        return throwResponseUnauthorized(
                            'User Anda tidak terdaftar. Harap login atau mendaftar untuk melanjutkan',
                            [
                                'token'     =>  $defaultToken,
                                'errorCode' =>  '[E-AUTH-001.1.1]'
                            ]
                        );
                    }

                    $idCustomerDB   =   $sessionDataDB['IDCUSTOMER'];
                    $hardwareIDDB   =   $sessionDataDB['HARDWAREID'];
                    $platformDB     =   $sessionDataDB['PLATFORM'];

                    if($hardwareID == $hardwareIDDB && $hardwareID == $hardwareIDToken && $platform == $platformDB){
                        $timeCreateToken    =   Time::parse($timeCreateToken, APP_TIMEZONE);
                        $minutesDifference  =   $timeCreateToken->difference(Time::now(APP_TIMEZONE))->getMinutes();

                        if($minutesDifference > MAX_INACTIVE_SESSION_MINUTES){
                            return throwResponseForbidden(
                                'Sesi anda telah berakhir, harap login terlebih dahulu',
                                ['errorCode' =>  '[E-AUTH-001.1.2]']
                            );
                        }
            
                        $accessModel->update($idSession, ['DATETIMELOGIN' => $timeCreate]);
                        
                        $detailCustomerDB   =   $accessModel->getDetailCustomer($idCustomerDB);
                        $avatarDB           =   BASE_URL_ASSETS_CUSTOMER_AVATAR.(isset($detailCustomerDB['AVATAR']) ? $detailCustomerDB['AVATAR'] : 'default.jpg');
                        $namaDB             =   isset($detailCustomerDB['NAMA']) ? $detailCustomerDB['NAMA'] : '';
                        $emailDB            =   isset($detailCustomerDB['EMAIL']) ? $detailCustomerDB['EMAIL'] : '';
                        $nomorHPDB          =   isset($detailCustomerDB['NOMORHP']) ? $detailCustomerDB['NOMORHP'] : '';
                        $kotaDB             =   isset($detailCustomerDB['KOTA']) ? $detailCustomerDB['KOTA'] : APP_DEFAULT_ALAMAT_KOTA;
                        $propinsiDB         =   isset($detailCustomerDB['PROPINSI']) ? $detailCustomerDB['PROPINSI'] : APP_DEFAULT_ALAMAT_PROPINSI;
                        $userData           =   [
                            "avatar"    =>  $avatarDB,
                            "nama"      =>  $namaDB,
                            "email"     =>  $emailDB,
                            "nomorHP"   =>  $nomorHPDB,
                            "kota"      =>  $kotaDB,
                            "propinsi"  =>  $propinsiDB
                        ];

                        $tokenPayload['idCustomer'] =   $idCustomerDB;
                        $tokenPayload['idSession']  =   $idSession;
                        $tokenPayload['avatar']     =   $avatarDB;
                        $tokenPayload['nama']       =   $namaDB;
                        $tokenPayload['email']      =   $emailDB;
                        $tokenPayload['nomorHP']    =   $nomorHPDB;
                        $tokenPayload['kota']       =   $kotaDB;
                        $tokenPayload['propinsi']   =   $propinsiDB;
                        $statusCode                 =   200;
                        $responseMsg                =   'Sesi aktif, lanjutkan';
                    } else {
                        return throwResponseUnauthorized(
                                    'Hardware ID perangkat berubah, harap login untuk melanjutkan',
                                    [
                                        'token'     =>  $defaultToken,
                                        'errorCode' =>  '[E-AUTH-001.1.3]'
                                    ]
                                );
                    }
                }
            } catch (\Throwable $th) {
                return throwResponseUnauthorized(
                            'Token tidak valid',
                            [
                                'token'     =>  $defaultToken,
                                'errorCode' =>  '[E-AUTH-001.2.1]',
                                'message'   =>  ENVIRONMENT === 'development' ? $th->getMessage() : ''
                            ]
                        );
            }
        }

        $newToken       =   encodeJWTToken($tokenPayload);
        $optionHelper   =   isset($token) && $token != "" ? $this->getDataOption() : [];
        return $this->setResponseFormat('json')
                    ->respond([
                        'token'         =>  $newToken,
                        'userData'      =>  $userData,
                        'optionHelper'  =>  $optionHelper,
                        'messages'      =>  [
                            "accessMessage" =>  $responseMsg
                        ]
                    ])
                    ->setStatusCode($statusCode);

    }

    public function registerSubmitData()
    {
        $rules  =   [
            'nama'          =>  ['label' => 'Nama', 'rules' => 'required|alpha_numeric_space|max_length[100]'],
            'email'         =>  ['label' => 'Email', 'rules' => 'permit_empty|valid_email'],
            'phoneNumber'   =>  ['label' => 'Nomor Telepon', 'rules' => 'permit_empty|min_length[10]|max_length[15]|numeric'],
            'captcha'       =>  ['label' => 'Captcha', 'rules' => 'required|alpha_numeric|exact_length[4]']
        ];

        $messages   =   [
            'email' =>  [
                'valid_email'   =>  'Format email tidak valid'
            ],
            'phoneNumber'   =>  [
                'min_length'    =>  'Panjang nomor telepon minimal 10 digit',
                'max_length'    =>  'Panjang nomor telepon maksimal 15 digit',
                'numeric'       =>  'Nomor telepon hanya boleh berisi angka'
            ],
            'captcha'   =>  [
                'required'      =>  'Kode captcha wajib diisi',
                'alpha_numeric' =>  'Kode captcha hanya boleh berisi huruf dan angka',
                'exact_length'  =>  'Panjang kode captcha harus 4 karakter'
            ]
        ];

        if(!$this->validate($rules, $messages)) return $this->fail($this->validator->getErrors());

        $nama           =   $this->request->getVar('nama');
        $email          =   $this->request->getVar('email');
        $phoneNumber    =   $this->request->getVar('phoneNumber');
        $captcha        =   $this->request->getVar('captcha');
        $captchaToken   =   $this->userData->captchaCode;
        $otpCode        =   is_null(issetNotNullAndNotEmptyString($this->userData->otpCode)) ? null : $this->userData->otpCode;
        
        if($captcha != $captchaToken) return $this->fail('Kode captcha yang Anda masukkan tidak cocok');
        if(is_null(issetNotNullAndNotEmptyString($email)) && is_null(issetNotNullAndNotEmptyString($phoneNumber))) return $this->fail('Masukkan email atau nomor telepon untuk melanjutkan');

        $mainOperation          =   new MainOperation();
        $emailPhoneNumberType   =   is_null(issetNotNullAndNotEmptyString($email)) ? 'PN' : 'EM';
        $emailPhoneNumberTypeStr=   $emailPhoneNumberType == 'PN' ? 'whatsapp' : 'email';
        $emailPhoneNumberStr    =   $emailPhoneNumberType == 'PN' ? $phoneNumber : $email;
        $emailPhoneNumberField  =   $emailPhoneNumberType == 'PN' ? 'NOMORHP' : 'EMAIL';
        $isDataUserExist        =   $mainOperation->isDataExist('m_customer', [$emailPhoneNumberField => $emailPhoneNumberStr]);

        if($isDataUserExist){
            return $this->fail($emailPhoneNumberStr.' sudah terdaftar, silakan masukkan '.$emailPhoneNumberStr.' untuk masuk ke aplikasi');
        }

        $messageSuccess =   'Halo '.$nama.', silakan lanjutkan dengan memasukkan kode OTP yang telah dikirimkan melalui pesan '.$emailPhoneNumberTypeStr.'.'; 
        $otpCode        =   is_null($otpCode) && $otpCode != '' ? $otpCode : generateRandomCharacter(6, 1);

        switch($emailPhoneNumberType){
            case 'EM':
                $this->sendEmailOTPCustomer($email, $nama, $otpCode, 'mendaftar');
                break;
            case 'PN':
                $this->sendWhatsAppOTPCustomer($phoneNumber, $otpCode);
                break;
        }

        $tokenUpdate    =   array(
            "nama"          =>  $nama,
            "email"         =>  $email,
            "nomorHP"       =>  $phoneNumber,
            "otpCode"       =>  $otpCode,
            "otpCodeExpired"=>  strtotime($this->currentDateTime) + (APP_OTP_EXPIRED_MINUTES*60)
        );

        return $this->setResponseFormat('json')
                    ->respond([
                        'tokenUpdate'       =>  $tokenUpdate,
                        'emailPhoneNumber'  =>  $emailPhoneNumberStr,
                        'message'           =>  $messageSuccess
                    ]);
    }
    
    public function registerSubmitOTP()
    {
        $rules  =   [
            'otpCode'   =>  ['label' => 'Kode OTP', 'rules' => 'required|numeric|exact_length[6]']
        ];

        $messages   =   [
            'otpCode'   =>  [
                'numeric'       =>  'Kode OTP hanya boleh berisi angka',
                'exact_length'  =>  'Panjang kode OTP harus 6 digit'
            ]
        ];

        if(!$this->validate($rules, $messages)) return $this->fail($this->validator->getErrors());

        $otpCodeParam   =   $this->request->getVar('otpCode');
        $otpCodeToken   =   $this->userData->otpCode;
        $otpCodeExpired =   $this->userData->otpCodeExpired;

        if($otpCodeExpired < strtotime($this->currentDateTime)) {
            $otpCode        =   generateRandomCharacter(6, 1);
            $tokenUpdate    =   array(
                "otpCode"       =>  $otpCode,
                "otpCodeExpired"=>  strtotime($this->currentDateTime) + (APP_OTP_EXPIRED_MINUTES*60)
            );
            return throwResponseNotAcceptable(
                        'Kode OTP telah kedaluwarsa, silakan mengulang untuk mendapatkan kode OTP baru',
                        [
                            'tokenUpdate'   =>  $tokenUpdate,
                            'resendOTP'     =>  true
                        ]
                    );
        }
        
        if($otpCodeParam != $otpCodeToken) return $this->fail('Kode captcha yang Anda masukkan tidak cocok');
        
        $mainOperation      =   new MainOperation();
        $nama               =   $this->userData->nama;
        $email              =   $this->userData->email;
        $phoneNumber        =   $this->userData->nomorHP;
        $arrInsertCustomer  =   [
            "NAMA"      =>  $nama,
            "EMAIL"     =>  $email,
            "NOMORHP"   =>  $phoneNumber,
            "STATUS"    =>  1
        ];

        $procInsertCustomer =   $mainOperation->insertDataTable('m_customer', $arrInsertCustomer);
        if(!$procInsertCustomer['status']){
            return throwResponseInternalServerError(
                        'Gagal melakukan pendaftaran, silakan coba beberapa saat lagi',
                        [
                            'errorCode' =>  '[E-AUTH-003.1.1]',
                            'message'   =>  ENVIRONMENT === 'development' ? $procInsertCustomer['message'] : ''
                        ]
                    );
        } else {
            $idCustomer =   $procInsertCustomer['insertID'];
            $idSession  =   $this->registerSessionCustomer($idCustomer);
            $tokenUpdate=   array(
                "idSession" =>  $idSession,
                "otpCode"   =>  ""
            );

            return $this->setResponseFormat('json')
                        ->respond([
                            'message'       =>  'Halo '.$nama.', pendaftaran Anda berhasil',
                            'tokenUpdate'   =>  $tokenUpdate,
                            'nama'          =>  $nama
                        ]);
        }
    }

    public function loginSubmitEmailPhoneNumber()
    {
        $rules  =   [
            'email'         =>  ['label' => 'Email', 'rules' => 'permit_empty|valid_email'],
            'phoneNumber'   =>  ['label' => 'Nomor Telepon', 'rules' => 'permit_empty|min_length[10]|max_length[15]|numeric'],
            'captcha'       =>  ['label' => 'Kode Captcha', 'rules' => 'required|alpha_numeric|exact_length[4]']
        ];

        $messages   =   [
            'email' =>  [
                'valid_email'   =>  'Format email tidak valid'
            ],
            'phoneNumber'   =>  [
                'min_length'    =>  'Panjang nomor telepon minimal 10 digit',
                'max_length'    =>  'Panjang nomor telepon maksimal 15 digit',
                'numeric'       =>  'Nomor telepon hanya boleh berisi angka'
            ],
            'captcha'   =>  [
                'required'      =>  'Kode captcha wajib diisi',
                'alpha_numeric' =>  'Kode captcha hanya boleh berisi huruf dan angka',
                'exact_length'  =>  'Panjang kode captcha harus 4 karakter'
            ]
        ];

        if(!$this->validate($rules, $messages)) return $this->fail($this->validator->getErrors());

        $accessModel    =   new AccessModel();
        $email          =   $this->request->getVar('email');
        $phoneNumber    =   $this->request->getVar('phoneNumber');
        $captcha        =   $this->request->getVar('captcha');
        $captchaToken   =   $this->userData->captchaCode;

        if($captcha != $captchaToken) return $this->fail('Kode captcha yang Anda masukkan tidak cocok');
        if(is_null(issetNotNullAndNotEmptyString($email)) && is_null(issetNotNullAndNotEmptyString($phoneNumber))) return $this->fail('Masukkan email atau nomor telepon untuk melanjutkan');

        $dataCustomer   =   $accessModel->getDataCustomer($email, $phoneNumber);
        if(!$dataCustomer) return $this->failNotFound('Tidak ada data yang cocok, masukkan email atau nomor telepon lain');
 
        $idCustomer             =   $dataCustomer['IDCUSTOMER'];
        $nama                   =   $dataCustomer['NAMA'];
        $email                  =   $dataCustomer['EMAIL'];
        $phoneNumber            =   $dataCustomer['NOMORHP'];
        $emailPhoneNumberType   =   is_null(issetNotNullAndNotEmptyString($email)) ? 'PN' : 'EM';
        $emailPhoneNumberTypeStr=   $emailPhoneNumberType == 'PN' ? 'whatsapp' : 'email';
        $emailPhoneNumberStr    =   $emailPhoneNumberType == 'PN' ? $phoneNumber : $email;
        $messageSuccess         =   'Halo '.$nama.', silakan lanjutkan dengan memasukkan kode OTP yang telah dikirimkan melalui pesan '.$emailPhoneNumberTypeStr.'.'; 
        $otpCode                =   generateRandomCharacter(6, 1);

        switch($emailPhoneNumberType){
            case 'EM':
                $this->sendEmailOTPCustomer($email, $nama, $otpCode, 'mengakses');
                break;
            case 'PN':
                $this->sendWhatsAppOTPCustomer($phoneNumber, $otpCode);
                break;
        }

        $tokenUpdate    =   array(
            "idCustomer"    =>  $idCustomer,
            "idSession"     =>  0,
            "nama"          =>  $nama,
            "email"         =>  $email,
            "nomorHP"       =>  $phoneNumber,
            "otpCode"       =>  $otpCode,
            "otpCodeExpired"=>  strtotime($this->currentDateTime) + (APP_OTP_EXPIRED_MINUTES*60)
        );

        return $this->setResponseFormat('json')
                    ->respond([
                        'tokenUpdate'       =>  $tokenUpdate,
                        'emailPhoneNumber'  =>  $emailPhoneNumberStr,
                        'message'           =>  $messageSuccess
                    ]);
    }

    public function loginSubmitOTP()
    {
        $rules  =   [
            'otpCode'   =>  ['label' => 'Kode OTP', 'rules' => 'required|numeric|exact_length[6]']
        ];

        $messages   =   [
            'otpCode'   =>  [
                'numeric'       =>  'Kode OTP hanya boleh berisi angka',
                'exact_length'  =>  'Panjang kode OTP harus 6 digit'
            ]
        ];

        if(!$this->validate($rules, $messages)) return $this->fail($this->validator->getErrors());

        $otpCodeParam   =   $this->request->getVar('otpCode');
        $idCustomer     =   $this->userData->idCustomer;
        $otpCodeToken   =   $this->userData->otpCode;
        $otpCodeExpired =   $this->userData->otpCodeExpired;

        if($otpCodeExpired < strtotime($this->currentDateTime)) {
            $otpCode        =   generateRandomCharacter(6, 1);
            $tokenUpdate    =   array(
                "otpCode"       =>  $otpCode,
                "otpCodeExpired"=>  strtotime($this->currentDateTime) + (APP_OTP_EXPIRED_MINUTES*60)
            );
            return throwResponseNotAcceptable(
                        'Kode OTP telah kedaluwarsa, silakan mengulang untuk mendapatkan kode OTP baru',
                        [
                            'tokenUpdate'   =>  $tokenUpdate,
                            'resendOTP'     =>  true
                        ]
                    );
        }

        if($otpCodeParam != $otpCodeToken) return $this->fail('Kode captcha yang Anda masukkan tidak cocok');
        $idSession  =   $this->registerSessionCustomer($idCustomer);
        $tokenUpdate=   array(
            "idSession" =>  $idSession,
            "otpCode"   =>  ""
        );

        return $this->setResponseFormat('json')
                    ->respond([
                        'tokenUpdate'   =>  $tokenUpdate,
                        'message'       =>  "Login berhasil"
                    ]);		
    }

    private function registerSessionCustomer($idCustomer)
    {
        $accessModel        =   new AccessModel();
        $dataSession        =   $accessModel->where('IDCUSTOMER', $idCustomer)->where('PLATFORM', $this->platform)->get()->getRowArray();
        $hardwareID         =   strtoupper($this->hardwareIDHeader);
        $platform           =   $this->platform;
        $idSession          =   0;
        $arrInsUpdSession   =   [
            "IDCUSTOMER"        =>  $idCustomer,
            "PLATFORM"          =>  $platform,
            "HARDWAREID"        =>  $hardwareID,
            "DATETIMELOGIN"     =>  date('Y-m-d H:i:s'),
            "DATETIMEACTIVITY"  =>  date('Y-m-d H:i:s'),
            "DATETIMEEXPIRED"   =>  date('Y-m-d H:i:s', strtotime('+'.MAX_INACTIVE_SESSION_MINUTES.' minutes'))
        ];


        if($dataSession){
            $idSession  =   $dataSession['IDSESSION'];
            $accessModel->update($idSession, $arrInsUpdSession);
        } else {
            $procInsertSession  =   $accessModel->insert($arrInsUpdSession);
            if($procInsertSession) $idSession  =   $accessModel->insertID;
        }    

        return $idSession;
    }

    private function sendEmailOTPCustomer($email, $nama, $otpCode, $loginRegisterStr = 'mengakses')
    {   
        $expiryTime     =   Time::now(APP_TIMEZONE)->addMinutes(APP_OTP_EXPIRED_MINUTES);
        $expiryDateTime =   $expiryTime->toLocalizedString('dd MMM yyyy HH:mm');
        $data           =   [
            'nama'              =>  $nama,
            'loginRegisterStr'  =>  $loginRegisterStr,
            'otpCode'           =>  $otpCode,
            'expiryDateTime'    =>  $expiryDateTime
        ];
        
        $htmlContent    =   view('email/loginRegisterOTP', $data);
        $mailer         =   new \App\Libraries\Mailer();
        $mailer->send(
            $email,
            $nama,
            'Kode OTP Akun Aplikasi '.APP_COMPANY_NAME,
            $htmlContent
        );
        return true;
    }

    private function sendWhatsAppOTPCustomer($phoneNumber, $otpCode)
    {
        $oneMsgIO       =   new \App\Libraries\OneMsgIO();
        $paramTemplate	=   [
            [
                "type"  => "body", 
                "parameters"=>  [
                    ["type" =>	"text", "text"	=>	$otpCode]
                ]
            ],
            [
                "type"      =>	"button", 
                "index"     =>	0, 
                "sub_type"  =>	"url", 
                "parameters"=>  [
                    ["type"	=>	"text", "text"	=>	$otpCode]
                ] 
            ]
        ];

        $oneMsgIO->sendMessageTemplate('otp_apps_login', ONEMSGIO_DEFAULT_CHATTEMPLATE_LANGUAGECODE, $phoneNumber, $paramTemplate);
        return true;
    }

    public function logout($token = false)
    {
        if(!$token || $token == "") return $this->failUnauthorized('[E-AUTH-001.1] Token Required');
        helper(['firebaseJWT']);

        try {
            $dataDecode     =   decodeJWTToken($token);
            $idSession      =   $dataDecode->idSession;
            $hardwareID     =   strtoupper($dataDecode->hardwareID);
            $accessModel    =   new AccessModel();
            $userAdminDataDB=   $accessModel
                                ->where("IDSESSION", $idSession)
                                ->first();

            if($userAdminDataDB && !is_null($userAdminDataDB)) {
                $hardwareIDDB   =   $userAdminDataDB['HARDWAREID'];

                if($hardwareID == $hardwareIDDB){
                    $accessModel->where('HARDWAREID', $hardwareID)->set('HARDWAREID', 'null', false)->update();
                }
            }

            $tokenUpdate    =   array(
                "idSession" =>  0,
                "nama"      =>  "",
                "email"     =>  "",
                "nomorHP"   =>  ""
            );
            return $this->setResponseFormat('json')
                    ->respond([
                        'message'       =>  'Sesi berhasil diakhiri',
                        'tokenUpdate'   =>  $tokenUpdate
                    ]);
        } catch (\Throwable $th) {
            return throwResponseInternalServerError(
                        'Internal server error',
                        [
                            'errorCode' =>  '[E-AUTH-001.2.1]',
                            'message'   =>  ENVIRONMENT === 'development' ? $th->getMessage() : ''
                        ]
                    );
        }
    }

    public function captcha($token = '')
    {
        if(!$token || $token == "") $this->returnBlankCaptcha();
        helper(['firebaseJWT']);
        try {
            $dataDecode     =   decodeJWTToken($token);
            $captchaCode    =   $dataDecode->captchaCode;
            $codeLength     =   strlen($captchaCode);

            generateCaptchaImage($captchaCode, $codeLength);
        } catch (\Throwable $th) {
            $this->returnBlankCaptcha();
        }
    }

    private function returnBlankCaptcha()
    {
        $img    =   imagecreatetruecolor(120, 20);
        $bg     =   imagecolorallocate ( $img, 255, 255, 255 );
        imagefilledrectangle($img, 0, 0, 120, 20, $bg);
        
        ob_start();
        imagejpeg($img, "blank.jpg", 100);
        $contents = ob_get_contents();
        ob_end_clean();

        $dataUri = "data:image/jpeg;base64," . base64_encode($contents);
        echo $dataUri;
    }

    private function getDataOption()
    {
        $accessModel            =   new AccessModel();
        $dataRegional           =   encodeDatabaseObjectResultKey($accessModel->getDataRegional(), 'ID');
        $dataMerk               =   encodeDatabaseObjectResultKey($accessModel->getDataMerk(), 'ID');
        $dataBarangKategori     =   encodeDatabaseObjectResultKey($accessModel->getDataBarangKategori(), 'ID');
        $dataEkspedisi          =   encodeDatabaseObjectResultKey($accessModel->getDataEkspedisi(), 'ID');
        $dataKanalPembayaran    =   encodeDatabaseObjectResultKey($accessModel->getDataKanalPembayaran(), 'ID');
        $dataStatusTransaksi    =   encodeDatabaseObjectResultKey($accessModel->getDataStatusTransaksi(), 'ID');

        return [
            "dataRegional"          =>  $dataRegional,
            "dataMerk"              =>  $dataMerk,
            "dataBarangKategori"    =>  $dataBarangKategori,
            "dataEkspedisi"         =>  $dataEkspedisi,
            "dataKanalPembayaran"   =>  $dataKanalPembayaran,
            "dataStatusTransaksi"   =>  $dataStatusTransaksi,
            "optionHours"	        =>  OPTION_HOURS,
            "optionMinutes"         =>  OPTION_MINUTES,
            "optionMinuteInterval"	=>  OPTION_MINUTEINTERVAL,
            "optionMonth"	        =>  OPTION_MONTH,
            "optionYear"	        =>  OPTION_YEAR
        ];
    }

    public function getDataOptionByKey($keyName, $optionName = false, $keyword = false)
    {
        $accessModel    =   new AccessModel();
        $optionName     =   $optionName != false ? $optionName : 'randomOption';
        $dataOption     =   [];
        $arrEncodeKey   =   ['ID'];

        switch($keyName){
            default :
                break;
        }

        $dataOption     =   encodeDatabaseObjectResultKey($dataOption, $arrEncodeKey);
        return $this->setResponseFormat('json')
                ->respond([
                    "dataOption"    =>  $dataOption,
                    "optionName"    =>  $optionName
                ]);
    }

    public function detailProfileSetting()
    {
        $accessModel    =   new AccessModel();
        $idUserAdmin    =   $this->userData->idUserAdmin;
        $detailUserAdmin=   $accessModel->getUserAdminDetail($idUserAdmin);

        if(is_null($detailUserAdmin)) return throwResponseNotFound("Detail profil tidak ditemukan");
        unset($detailUserAdmin['IDUSERADMINLEVEL']);
        return $this->setResponseFormat('json')
                    ->respond([
                        "detailUserAdmin"   =>  $detailUserAdmin
                     ]);
    }

    public function saveDetailProfileSetting()
    {
        helper(['form']);
        $idUserAdmin  =   $this->userData->idUserAdmin;
        $rules          =   [
            'username'  => ['label' => 'Username', 'rules' => 'required|alpha_numeric|min_length[4]'],
            'name'      => ['label' => 'Nama', 'rules' => 'required|alpha_numeric_space|min_length[4]'],
        ];

        if(!$this->validate($rules)) return $this->fail($this->validator->getErrors());

        $accessModel        =   new AccessModel();
        $username           =   $this->request->getVar('username');
        $name               =   $this->request->getVar('name');
        $currentPassword    =   $this->request->getVar('currentPassword');
        $newPassword        =   $this->request->getVar('newPassword');
        $repeatPassword     =   $this->request->getVar('repeatPassword');
        $relogin            =   false;

        $arrUpdateUserAdmin =   [
            'NAME'      =>  $name,
            'USERNAME'  =>  $username
        ];

        if($currentPassword != "" || $newPassword != "" || $repeatPassword != ""){
			if($currentPassword == "") return throwResponseNotAcceptable("Harap masukkan kata sandi lama Anda (kata sandi saat ini)");
			if($newPassword == "") return throwResponseNotAcceptable("Harap masukkan kata sandi baru");
            if($repeatPassword == "") return throwResponseNotAcceptable("Harap masukkan pengulangan kata sandi baru");
			if($newPassword != $repeatPassword) return throwResponseNotAcceptable("Pengulangan kata sandi yang Anda masukkan tidak cocok");
			
            $dataCustomer  =   $accessModel->where("IDUSERADMIN", $idUserAdmin)->first();
            if(!$dataCustomer) return $this->failNotFound('Data profil Anda tidak ditemukan, silakan coba lagi nanti');
            $passwordVerify =   password_verify($currentPassword, $dataCustomer['PASSWORD']);
            if(!$passwordVerify) return $this->fail('Kata sandi lama yang Anda masukkan salah');
			
			$arrUpdateUserAdmin['PASSWORD'] =	password_hash($newPassword, PASSWORD_DEFAULT);
            $relogin                        =   true;
		}

        $accessModel->update($idUserAdmin, $arrUpdateUserAdmin);
        $tokenUpdate    =   [
            "username"  =>  $username,
            "name"      =>  $name
        ];

        return $this->setResponseFormat('json')
                    ->respond([
                        "message"       =>  "Data profil Anda telah diperbarui",
                        "relogin"       =>  $relogin,
                        "tokenUpdate"   =>  $tokenUpdate
                     ]);
    }
}