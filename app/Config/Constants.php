<?php

require_once ROOTPATH . '../vendor/autoload.php';
use Dotenv\Dotenv;
$dotenv	=	Dotenv::createImmutable(ROOTPATH);
$dotenv->load();

/*
 | --------------------------------------------------------------------
 | App Namespace
 | --------------------------------------------------------------------
 |
 | This defines the default Namespace that is used throughout
 | CodeIgniter to refer to the Application directory. Change
 | this constant to change the namespace that all application
 | classes should use.
 |
 | NOTE: changing this will require manually modifying the
 | existing namespaces of App\* namespaced-classes.
 */
defined('APP_NAMESPACE') || define('APP_NAMESPACE', 'App');

/*
 | --------------------------------------------------------------------------
 | Composer Path
 | --------------------------------------------------------------------------
 |
 | The path that Composer's autoload file is expected to live. By default,
 | the vendor folder is in the Root directory, but you can customize that here.
 */
defined('COMPOSER_PATH') || define('COMPOSER_PATH', ROOTPATH . '../vendor/autoload.php');

/*
 |--------------------------------------------------------------------------
 | Timing Constants
 |--------------------------------------------------------------------------
 |
 | Provide simple ways to work with the myriad of PHP functions that
 | require information to be in seconds.
 */
defined('SECOND') || define('SECOND', 1);
defined('MINUTE') || define('MINUTE', 60);
defined('HOUR')   || define('HOUR', 3600);
defined('DAY')    || define('DAY', 86400);
defined('WEEK')   || define('WEEK', 604800);
defined('MONTH')  || define('MONTH', 2_592_000);
defined('YEAR')   || define('YEAR', 31_536_000);
defined('DECADE') || define('DECADE', 315_360_000);

/*
 | --------------------------------------------------------------------------
 | Exit Status Codes
 | --------------------------------------------------------------------------
 |
 | Used to indicate the conditions under which the script is exit()ing.
 | While there is no universal standard for error codes, there are some
 | broad conventions.  Three such conventions are mentioned below, for
 | those who wish to make use of them.  The CodeIgniter defaults were
 | chosen for the least overlap with these conventions, while still
 | leaving room for others to be defined in future versions and user
 | applications.
 |
 | The three main conventions used for determining exit status codes
 | are as follows:
 |
 |    Standard C/C++ Library (stdlibc):
 |       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
 |       (This link also contains other GNU-specific conventions)
 |    BSD sysexits.h:
 |       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
 |    Bash scripting:
 |       http://tldp.org/LDP/abs/html/exitcodes.html
 |
 */
defined('EXIT_SUCCESS')        || define('EXIT_SUCCESS', 0);        // no errors
defined('EXIT_ERROR')          || define('EXIT_ERROR', 1);          // generic error
defined('EXIT_CONFIG')         || define('EXIT_CONFIG', 3);         // configuration error
defined('EXIT_UNKNOWN_FILE')   || define('EXIT_UNKNOWN_FILE', 4);   // file not found
defined('EXIT_UNKNOWN_CLASS')  || define('EXIT_UNKNOWN_CLASS', 5);  // unknown class
defined('EXIT_UNKNOWN_METHOD') || define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     || define('EXIT_USER_INPUT', 7);     // invalid user input
defined('EXIT_DATABASE')       || define('EXIT_DATABASE', 8);       // database error
defined('EXIT__AUTO_MIN')      || define('EXIT__AUTO_MIN', 9);      // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      || define('EXIT__AUTO_MAX', 125);    // highest automatically-assigned error code

/**
 * @deprecated Use \CodeIgniter\Events\Events::PRIORITY_LOW instead.
 */
define('EVENT_PRIORITY_LOW', 200);

/**
 * @deprecated Use \CodeIgniter\Events\Events::PRIORITY_NORMAL instead.
 */
define('EVENT_PRIORITY_NORMAL', 100);

/**
 * @deprecated Use \CodeIgniter\Events\Events::PRIORITY_HIGH instead.
 */
define('EVENT_PRIORITY_HIGH', 10);
$url			=	!empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : "-";
$domain			=	explode(".", $url);
$subdomain		=	$domain[0];
$productionURL	=	$subdomain == "store" ? true : false;

$arrHours   =   [];
for($i=0; $i<24; $i++){
    $arrHours[] =   array("ID"=>str_pad($i, 2, '0', STR_PAD_LEFT), "VALUE"=>str_pad($i, 2, '0', STR_PAD_LEFT));
}
$strArrHour =   implode(',', array_column($arrHours, 'VALUE'));

$arrMinutes =   [];
for($i=0; $i<60; $i++){
    $arrMinutes[]   =   array("ID"=>str_pad($i, 2, '0', STR_PAD_LEFT), "VALUE"=>str_pad($i, 2, '0', STR_PAD_LEFT));
}

$arrMinuteInterval   =	array(
    array("ID"=>"00", "VALUE"=>"00"),
    array("ID"=>"15", "VALUE"=>"15"),
    array("ID"=>"30", "VALUE"=>"30"),
    array("ID"=>"45", "VALUE"=>"45"),
);
$strArrMinuteInterval=  implode(',', array_column($arrMinuteInterval, 'VALUE'));

$arrMonth   =	array(
    array("ID"=>"01", "VALUE"=>"January"),
    array("ID"=>"02", "VALUE"=>"February"),
    array("ID"=>"03", "VALUE"=>"March"),
    array("ID"=>"04", "VALUE"=>"April"),
    array("ID"=>"05", "VALUE"=>"May"),
    array("ID"=>"06", "VALUE"=>"June"),
    array("ID"=>"07", "VALUE"=>"July"),
    array("ID"=>"08", "VALUE"=>"August"),
    array("ID"=>"09", "VALUE"=>"September"),
    array("ID"=>"10", "VALUE"=>"October"),
    array("ID"=>"11", "VALUE"=>"November"),
    array("ID"=>"12", "VALUE"=>"December")
);

$thisYear   =	date('Y');
$lastYear   =	date("Y", strtotime("-1 year"));
$nextYear   =	date("Y", strtotime("+1 year"));
$arrYear    =	array(
    array("ID"=>$nextYear, "VALUE"=>$nextYear),
    array("ID"=>$thisYear, "VALUE"=>$thisYear),
    array("ID"=>$lastYear, "VALUE"=>$lastYear)
);

defined('APP_COMPANY_NAME')                             || define('APP_COMPANY_NAME', $_ENV['APP_COMPANY_NAME'] ?: 'API Customer');
defined('APP_COMPANY_ADDRESS')                          || define('APP_COMPANY_ADDRESS', $_ENV['APP_COMPANY_ADDRESS'] ?: 'Indonesia');
defined('APP_NAME')                                     || define('APP_NAME', $_ENV['APP_NAME'] ?: 'API Customer');
defined('APP_NAME_FORMAL')                              || define('APP_NAME_FORMAL', $_ENV['APP_NAME_FORMAL'] ?: 'API Customer');
defined('APP_TIMEZONE')                                 || define('APP_TIMEZONE', $_ENV['APP_TIMEZONE'] ?: 'Asia/Jakarta');
defined('APP_OTP_EXPIRED_MINUTES')                      || define('APP_OTP_EXPIRED_MINUTES', $_ENV['APP_OTP_EXPIRED_MINUTES'] ?: '120');
defined('APP_MAIN_DATABASE_NAME')                       || define('APP_MAIN_DATABASE_NAME', $_ENV['APP_MAIN_DATABASE_NAME'] ?: 'db_main');
defined('APP_MAIN_DATABASE_DEFAULT')                    || define('APP_MAIN_DATABASE_DEFAULT', $_ENV['APP_MAIN_DATABASE_DEFAULT'] ?: 'db_default');
defined('APP_DEFAULT_ALAMAT_KOTA')                      || define('APP_DEFAULT_ALAMAT_KOTA', $_ENV['APP_DEFAULT_ALAMAT_KOTA'] ?: 'Jakarta Pusat');
defined('APP_DEFAULT_ALAMAT_PROPINSI')                  || define('APP_DEFAULT_ALAMAT_PROPINSI', $_ENV['APP_DEFAULT_ALAMAT_PROPINSI'] ?: 'DKI Jakarta');
defined('APP_DATABASE_TOOL_SECRET_KEY')                 || define('APP_DATABASE_TOOL_SECRET_KEY', $_ENV['APP_DATABASE_TOOL_SECRET_KEY'] ?: 'secretkey');

defined('MAX_INACTIVE_SESSION_MINUTES')                 || define('MAX_INACTIVE_SESSION_MINUTES', $_ENV['MAX_INACTIVE_SESSION_MINUTES'] ?: 60);
defined('LOG_USER_REQUEST')                             || define('LOG_USER_REQUEST', $_ENV['LOG_USER_REQUEST'] ?: false);

defined('PRODUCTION_URL')						        || define('PRODUCTION_URL', $productionURL);
defined('BASE_URL')                                     || define('BASE_URL', $_ENV['BASE_URL'] ?: 'https://example.com/');
defined('BASE_URL_ADMIN_APPS')                          || define('BASE_URL_ADMIN_APPS', $_ENV['BASE_URL_ADMIN_APPS'] ?: 'https://example.com/');

defined('BASE_URL_ASSETS_CUSTOM')                       || define('BASE_URL_ASSETS_CUSTOM', BASE_URL.$_ENV['BASE_URL_ASSETS_CUSTOM'] ?: 'assets/');
defined('BASE_URL_ASSETS_LOGO_MERK')                    || define('BASE_URL_ASSETS_LOGO_MERK', BASE_URL_ASSETS_CUSTOM.$_ENV['BASE_URL_ASSETS_LOGO_MERK_PATH'] ?: 'logoMerk/');
defined('BASE_URL_ASSETS_LOGO_MARKETPLACE')             || define('BASE_URL_ASSETS_LOGO_MARKETPLACE', BASE_URL_ASSETS_CUSTOM.$_ENV['BASE_URL_ASSETS_LOGO_MARKETPLACE_PATH'] ?: 'logoMarketplace/');
defined('BASE_URL_ASSETS_PHOTO_BARANG')                 || define('BASE_URL_ASSETS_PHOTO_BARANG', BASE_URL_ASSETS_CUSTOM.$_ENV['BASE_URL_ASSETS_PHOTO_BARANG_PATH'] ?: 'photoBarang/');
defined('BASE_URL_ASSETS_CUSTOMER_AVATAR')              || define('BASE_URL_ASSETS_CUSTOMER_AVATAR', BASE_URL_ASSETS_CUSTOM.$_ENV['BASE_URL_ASSETS_CUSTOMER_AVATAR_PATH'] ?: 'customerAvatar/');
defined('BASE_URL_ASSETS_CUSTOMER_SLIDE_BANNER')        || define('BASE_URL_ASSETS_CUSTOMER_SLIDE_BANNER', BASE_URL_ASSETS_CUSTOM.$_ENV['BASE_URL_ASSETS_CUSTOMER_SLIDE_BANNER_PATH'] ?: 'customerSlideBanner/');
defined('BASE_URL_ASSETS_CUSTOMER_MERK')                || define('BASE_URL_ASSETS_CUSTOMER_MERK', BASE_URL_ASSETS_CUSTOM.$_ENV['BASE_URL_ASSETS_CUSTOMER_MERK_PATH'] ?: 'customerMerk/');

defined('OPTION_HOURS')						            || define('OPTION_HOURS', $arrHours);
defined('OPTION_HOUR_STRARR')                           || define('OPTION_HOUR_STRARR', $strArrHour);
defined('OPTION_MINUTES')                               || define('OPTION_MINUTES', $arrMinutes);
defined('OPTION_MINUTEINTERVAL')                        || define('OPTION_MINUTEINTERVAL', $arrMinuteInterval);
defined('OPTION_MINUTEINTERVAL_STRARR')                 || define('OPTION_MINUTEINTERVAL_STRARR', $strArrMinuteInterval);
defined('OPTION_MONTH')						            || define('OPTION_MONTH', $arrMonth);
defined('OPTION_YEAR')						            || define('OPTION_YEAR', $arrYear);

defined('PATH_STORAGE')						            || define('PATH_STORAGE', $_ENV['PATH_STORAGE'] ?: 'storage/');
defined('PATH_STORAGE_FILE')                            || define('PATH_STORAGE_FILE', PATH_STORAGE.$_ENV['PATH_STORAGE_FILE'] ?: PATH_STORAGE.'file_storage/');
defined('PATH_STORAGE_FILE_LOGO_MERK')                  || define('PATH_STORAGE_FILE_LOGO_MERK', PATH_STORAGE_FILE.$_ENV['PATH_STORAGE_FILE_LOGO_MERK'] ?: PATH_STORAGE_FILE.'logoMerk/');
defined('PATH_STORAGE_FILE_LOGO_MARKETPLACE')           || define('PATH_STORAGE_FILE_LOGO_MARKETPLACE', PATH_STORAGE_FILE.$_ENV['PATH_STORAGE_FILE_LOGO_MARKETPLACE'] ?: PATH_STORAGE_FILE.'logoMarketplace/');
defined('PATH_STORAGE_PHOTO')                           || define('PATH_STORAGE_PHOTO', PATH_STORAGE.$_ENV['PATH_STORAGE_PHOTO'] ?: PATH_STORAGE.'photo_storage/');
defined('PATH_STORAGE_PHOTO_BARANG')                    || define('PATH_STORAGE_PHOTO_BARANG', PATH_STORAGE_PHOTO.$_ENV['PATH_STORAGE_PHOTO_BARANG'] ?: PATH_STORAGE_PHOTO.'barang/');
defined('PATH_STORAGE_CUSTOMER_AVATAR')                 || define('PATH_STORAGE_CUSTOMER_AVATAR', PATH_STORAGE_PHOTO.$_ENV['PATH_STORAGE_CUSTOMER_AVATAR'] ?: PATH_STORAGE_PHOTO.'customer_avatar/');
defined('PATH_STORAGE_CUSTOMER_SLIDE_BANNER')           || define('PATH_STORAGE_CUSTOMER_SLIDE_BANNER', PATH_STORAGE_PHOTO.$_ENV['PATH_STORAGE_CUSTOMER_SLIDE_BANNER'] ?: PATH_STORAGE_PHOTO.'slide_banner/');
defined('PATH_STORAGE_CUSTOMER_MERK')                   || define('PATH_STORAGE_CUSTOMER_MERK', PATH_STORAGE_PHOTO.$_ENV['PATH_STORAGE_CUSTOMER_MERK'] ?: PATH_STORAGE_PHOTO.'customer_merk/');

defined('EMAIL_SMTP_HOST')								|| define('EMAIL_SMTP_HOST', $_ENV['EMAIL_SMTP_HOST'] ?: 'default_email_host.com');
defined('EMAIL_SMTP_PORT')								|| define('EMAIL_SMTP_PORT', $_ENV['EMAIL_SMTP_PORT'] ?: 587);
defined('EMAIL_SMTP_USER')								|| define('EMAIL_SMTP_USER', $_ENV['EMAIL_SMTP_USER'] ?: 'user@mail.com');
defined('EMAIL_SMTP_PASSWORD')                          || define('EMAIL_SMTP_PASSWORD', $_ENV['EMAIL_SMTP_PASSWORD'] ?: 'password123');
defined('EMAIL_SMTP_CRYPTO')                            || define('EMAIL_SMTP_CRYPTO', $_ENV['EMAIL_SMTP_CRYPTO'] ?: 'tls');
defined('EMAIL_FROM_ADDRESS')                           || define('EMAIL_FROM_ADDRESS', $_ENV['EMAIL_FROM_ADDRESS'] ?: 'noreply@mail.com');
defined('EMAIL_FROM_NAME')                              || define('EMAIL_FROM_NAME', $_ENV['EMAIL_FROM_NAME'] ?: 'No Reply');
defined('EMAIL_DEBUG')                                  || define('EMAIL_DEBUG', $_ENV['EMAIL_DEBUG'] ?: 0);

defined('ONEMSGIO_TOKEN')								|| define('ONEMSGIO_TOKEN', $_ENV['ONEMSGIO_TOKEN'] ?: 'default_token_1234567890');
defined('ONEMSGIO_JWT_TOKEN')							|| define('ONEMSGIO_JWT_TOKEN', $_ENV['ONEMSGIO_JWT_TOKEN'] ?: 'default_token_1234567890');
defined('ONEMSGIO_NAMESPACE')							|| define('ONEMSGIO_NAMESPACE', $_ENV['ONEMSGIO_NAMESPACE'] ?: 'default_7777_8888_9999_gu4345842000');
defined('ONEMSGIO_CHANNEL_URL')							|| define('ONEMSGIO_CHANNEL_URL', $_ENV['ONEMSGIO_CHANNEL_URL'] ?: 'https://1msg.io/1234567890/');
defined('ONEMSGIO_DEFAULT_CHATTEMPLATE_LANGUAGECODE')	|| define('ONEMSGIO_DEFAULT_CHATTEMPLATE_LANGUAGECODE',	$_ENV['ONEMSGIO_DEFAULT_CHATTEMPLATE_LANGUAGECODE'] ?: 'id');

defined('FIREBASE_PRIVATE_KEY_PATH')		            || define('FIREBASE_PRIVATE_KEY_PATH', APPPATH . $_ENV['FIREBASE_PRIVATE_KEY_PATH'] ?: 'default.json');

defined('FIREBASE_PUBLIC_API_KEY')                      || define('FIREBASE_PUBLIC_API_KEY', $_ENV['FIREBASE_PUBLIC_API_KEY'] ?: 'AIopPPLLERjk1-7XXXXXXXXXXXXXXXXXX');
defined('FIREBASE_PUBLIC_AUTH_DOMAIN')		            || define('FIREBASE_PUBLIC_AUTH_DOMAIN', $_ENV['FIREBASE_PUBLIC_AUTH_DOMAIN'] ?: 'example-project.firebaseapp.com');
defined('FIREBASE_PUBLIC_PROJECT_ID')		            || define('FIREBASE_PUBLIC_PROJECT_ID', $_ENV['FIREBASE_PUBLIC_PROJECT_ID'] ?: 'example-project');
defined('FIREBASE_PUBLIC_STORAGE_BUCKET')               || define('FIREBASE_PUBLIC_STORAGE_BUCKET', $_ENV['FIREBASE_PUBLIC_STORAGE_BUCKET'] ?: 'example-project.appspot.com');
defined('FIREBASE_PUBLIC_MESSAGING_SENDER_ID')          || define('FIREBASE_PUBLIC_MESSAGING_SENDER_ID', $_ENV['FIREBASE_PUBLIC_MESSAGING_SENDER_ID'] ?: '111111111111');
defined('FIREBASE_PUBLIC_APP_ID')                       || define('FIREBASE_PUBLIC_APP_ID', $_ENV['FIREBASE_PUBLIC_APP_ID'] ?: '1:111111111111:web:0ffffffffffffffffffff');
defined('FIREBASE_PUBLIC_MEASUREMENT_ID')               || define('FIREBASE_PUBLIC_MEASUREMENT_ID', $_ENV['FIREBASE_PUBLIC_MEASUREMENT_ID'] ?: 'G-1111111111');

defined('FIREBASE_RTDB_URI')                            || define('FIREBASE_RTDB_URI', $_ENV['FIREBASE_RTDB_URI'] ?: 'https://example.com');
defined('FIREBASE_RTDB_PROJECT_ID')                     || define('FIREBASE_RTDB_PROJECT_ID', $_ENV['FIREBASE_RTDB_PROJECT_ID'] ?: 'default');
defined('FIREBASE_RTDB_MAINREF_NAME')                   || define('FIREBASE_RTDB_MAINREF_NAME', $_ENV['FIREBASE_RTDB_MAINREF_NAME'] ?: 'default/');