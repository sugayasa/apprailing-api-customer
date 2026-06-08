<?php
/**
 * CodeIgniter 4 IDE Helper for Intelephense
 * 
 * This file helps IDE understand CodeIgniter 4 framework features.
 * DO NOT include this file in your application.
 */

namespace {
    /**
     * Helper function to load helpers
     */
    function helper($filenames) {}

    /**
     * Service function
     */
    function service(string $name, ...$params) {}

    /**
     * Model function
     */
    function model(string $name, bool $getShared = true, &$conn = null) {}

    /**
     * View function
     */
    function view(string $name, array $data = [], array $options = []) {}

    /**
     * Throw response with internal server error
     */
    function throwResponseInternalServerError($message, $data = []) {}

    /**
     * Check if string is set, not null and not empty
     */
    function issetNotNullAndNotEmptyString($value) {}
}

namespace CodeIgniter\HTTP {
    /**
     * Request Interface
     */
    interface RequestInterface {
        public function getIPAddress();
        public function isValidIP($ip, $which = '');
        public function getMethod($upper = false);
        public function getServer($index = null, $filter = null);
        public function getVar($index = null, $filter = null);
        public function getGet($index = null, $filter = null);
        public function getPost($index = null, $filter = null);
        public function getCookie($index = null, $filter = null);
    }

    /**
     * Response Interface
     */
    interface ResponseInterface {
        public function setStatusCode(int $code, string $reason = '');
        public function getStatusCode();
        public function setBody($data);
        public function getBody();
        public function setJSON($data);
        public function getJSON();
        public function setHeader($name, $value);
        public function getHeader($name);
    }
}

namespace Psr\Log {
    /**
     * Logger Interface
     */
    interface LoggerInterface {
        public function emergency($message, array $context = []);
        public function alert($message, array $context = []);
        public function critical($message, array $context = []);
        public function error($message, array $context = []);
        public function warning($message, array $context = []);
        public function notice($message, array $context = []);
        public function info($message, array $context = []);
        public function debug($message, array $context = []);
        public function log($level, $message, array $context = []);
    }
}

namespace CodeIgniter\I18n {
    use DateTime;
    use DateTimeZone;

    /**
     * Time class for date/time manipulation
     */
    class Time extends DateTime {
        /**
         * @return static
         */
        public static function now($timezone = null) {
            return new static();
        }
        
        /**
         * @return static
         */
        public static function parse(string $time, $timezone = null) {
            return new static();
        }
        
        public function toDateTimeString() {
            return '';
        }
        
        /**
         * @return static
         */
        public function addMinutes(int $minutes) {
            return $this;
        }
        
        /**
         * @return TimeDifference
         */
        public function difference($time) {
            return new TimeDifference();
        }
    }

    /**
     * Time Difference
     */
    class TimeDifference {
        public function getMinutes() {
            return 0;
        }
        public function getHours() {
            return 0;
        }
        public function getDays() {
            return 0;
        }
        public function getSeconds() {
            return 0;
        }
    }
}

namespace CodeIgniter\Controller {
    use CodeIgniter\HTTP\RequestInterface;
    use CodeIgniter\HTTP\ResponseInterface;
    use Psr\Log\LoggerInterface;
    use CodeIgniter\Validation\Validation;

    /**
     * @property RequestInterface $request
     * @property ResponseInterface $response
     * @property LoggerInterface $logger
     * @property Validation $validator
     */
    class BaseController {
        protected $request;
        protected $response;
        protected $logger;
        protected $validator;
        protected $helpers = [];
        protected $forceHTTPS = 0;
        
        public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {}
        
        /**
         * Validate data
         */
        public function validate($rules, array $messages = []): bool {}
    }
}

namespace CodeIgniter\RESTful {
    use CodeIgniter\Controller\BaseController;
    use CodeIgniter\HTTP\ResponseInterface;

    /**
     * @property \CodeIgniter\HTTP\RequestInterface $request
     * @property \CodeIgniter\HTTP\ResponseInterface $response
     * @property \CodeIgniter\Validation\Validation $validator
     */
    class ResourceController extends BaseController {
        protected $modelName;
        protected $format = 'json';

        /**
         * Set response format
         * @return $this
         */
        public function setResponseFormat(string $format) {
            return $this;
        }

        /**
         * Response with fail status
         * @return ResponseInterface
         */
        public function fail($messages, int $status = 400, string $code = null, string $message = '') {}

        /**
         * Response with fail not found
         * @return ResponseInterface
         */
        public function failNotFound(string $description = 'Not Found') {}

        /**
         * Response with fail forbidden
         * @return ResponseInterface
         */
        public function failForbidden(string $description = 'Forbidden') {}

        /**
         * Response with fail unauthorized
         * @return ResponseInterface
         */
        public function failUnauthorized(string $description = 'Unauthorized') {}

        /**
         * Response with fail validation error
         * @return ResponseInterface
         */
        public function failValidationError(string $description = 'Validation Error') {}

        /**
         * Response with fail resource exists
         * @return ResponseInterface
         */
        public function failResourceExists(string $description = 'Resource Exists') {}

        /**
         * Response with fail resource gone
         * @return ResponseInterface
         */
        public function failResourceGone(string $description = 'Resource Gone') {}

        /**
         * Response with fail too many requests
         * @return ResponseInterface
         */
        public function failTooManyRequests(string $description = 'Too Many Requests') {}

        /**
         * Response with fail server error
         * @return ResponseInterface
         */
        public function failServerError(string $description = 'Internal Server Error', string $code = null, string $message = '') {}

        /**
         * Response with created status
         * @return ResponseInterface
         */
        public function respondCreated($data = null, string $message = '') {}

        /**
         * Response with deleted status
         * @return ResponseInterface
         */
        public function respondDeleted($data, string $message = '') {}

        /**
         * Response with no content
         * @return ResponseInterface
         */
        public function respondNoContent(string $message = 'No Content') {}

        /**
         * Response with updated status
         * @return ResponseInterface
         */
        public function respondUpdated($data, string $message = '') {}
    }

    class ResourcePresenter extends ResourceController {}
}

namespace CodeIgniter\API {
    use CodeIgniter\HTTP\ResponseInterface;

    /**
     * Response Trait for API responses
     */
    trait ResponseTrait {
        /**
         * @return ResponseInterface
         */
        public function respond($data, int $status = 200, string $message = '') {}
        
        /**
         * @return ResponseInterface
         */
        public function fail($messages, int $status = 400, string $code = null, string $message = '') {}
        
        /**
         * @return ResponseInterface
         */
        public function failNotFound(string $description = 'Not Found') {}
        
        /**
         * @return ResponseInterface
         */
        public function failForbidden(string $description = 'Forbidden') {}
        
        /**
         * @return ResponseInterface
         */
        public function failUnauthorized(string $description = 'Unauthorized') {}
        
        /**
         * @return ResponseInterface
         */
        public function failValidationError(string $description = 'Validation Error') {}
        
        /**
         * @return ResponseInterface
         */
        public function failResourceExists(string $description = 'Resource Exists') {}
        
        /**
         * @return ResponseInterface
         */
        public function failResourceGone(string $description = 'Resource Gone') {}
        
        /**
         * @return ResponseInterface
         */
        public function failTooManyRequests(string $description = 'Too Many Requests') {}
        
        /**
         * @return ResponseInterface
         */
        public function failServerError(string $description = 'Internal Server Error', string $code = null, string $message = '') {}
        
        /**
         * @return ResponseInterface
         */
        public function respondCreated($data = null, string $message = '') {}
        
        /**
         * @return ResponseInterface
         */
        public function respondDeleted($data, string $message = '') {}
        
        /**
         * @return ResponseInterface
         */
        public function respondNoContent(string $message = 'No Content') {}
        
        /**
         * @return ResponseInterface
         */
        public function respondUpdated($data, string $message = '') {}
        
        /**
         * Set response format
         * @return $this
         */
        public function setResponseFormat(string $format) {
            return $this;
        }
    }
}

namespace CodeIgniter\Model {
    use CodeIgniter\Database\BaseBuilder;

    /**
     * @mixin BaseBuilder
     * @method $this where($key, $value = null, bool $escape = null)
     * @method $this orWhere($key, $value = null, bool $escape = null)
     * @method $this whereIn(string $key = null, $values = null, bool $escape = null)
     * @method $this whereNotIn(string $key = null, $values = null, bool $escape = null)
     * @method $this like($field, string $match = '', string $side = 'both', bool $escape = null, bool $insensitiveSearch = false)
     * @method $this orderBy(string $orderBy, string $direction = '', bool $escape = null)
     * @method $this limit(?int $value = null, ?int $offset = 0)
     * @method $this select($select = '*', ?bool $escape = null)
     * @method $this join(string $table, string $cond, string $type = '', bool $escape = null)
     * @method $this groupBy($by, ?bool $escape = null)
     * @method $this set($key, $value = '', ?bool $escape = null)
     * @method object get()
     */
    class Model {
        protected $table;
        protected $primaryKey = 'id';
        protected $returnType = 'array';
        protected $allowedFields = [];
        public $insertID;
        
        public function find($id = null) {}
        public function findAll(int $limit = 0, int $offset = 0) {}
        
        /**
         * @return array|object|null
         */
        public function first() {}
        
        /**
         * @return bool|int
         */
        public function insert($data = null, bool $returnID = true) {}
        
        /**
         * @return bool
         */
        public function update($id = null, $data = null): bool {}
        
        public function delete($id = null, bool $purge = false) {}
        
        /**
         * @return $this
         */
        public function where($key, $value = null, bool $escape = null) {
            return $this;
        }
        
        /**
         * @return $this
         */
        public function orWhere($key, $value = null, bool $escape = null) {
            return $this;
        }
        
        /**
         * @return $this
         */
        public function whereIn(string $key = null, $values = null, bool $escape = null) {
            return $this;
        }
        
        /**
         * @return $this
         */
        public function whereNotIn(string $key = null, $values = null, bool $escape = null) {
            return $this;
        }
        
        /**
         * @return $this
         */
        public function like($field, string $match = '', string $side = 'both', bool $escape = null, bool $insensitiveSearch = false) {
            return $this;
        }
        
        /**
         * @return $this
         */
        public function orderBy(string $orderBy, string $direction = '', bool $escape = null) {
            return $this;
        }
        
        /**
         * @return $this
         */
        public function limit(?int $value = null, ?int $offset = 0) {
            return $this;
        }
        
        /**
         * @return $this
         */
        public function select($select = '*', ?bool $escape = null) {
            return $this;
        }
        
        /**
         * @return $this
         */
        public function join(string $table, string $cond, string $type = '', bool $escape = null) {
            return $this;
        }
        
        /**
         * @return $this
         */
        public function groupBy($by, ?bool $escape = null) {
            return $this;
        }
        
        public function save($data): bool {}
        public function getInsertID() {}
        public function countAllResults(bool $reset = true, bool $test = false) {}
        
        /**
         * @return $this
         */
        public function set($key, $value = '', ?bool $escape = null) {
            return $this;
        }
        
        /**
         * @return object
         */
        public function get() {
            return (object)[];
        }
        
        /**
         * Magic method for query builder methods
         */
        public function __call($method, $params) {}
    }
}

namespace CodeIgniter\Database {
    /**
     * Base Builder for query building
     */
    class BaseBuilder {
        public function where($key, $value = null, bool $escape = null) {}
        public function orWhere($key, $value = null, bool $escape = null) {}
        public function whereIn(string $key = null, $values = null, bool $escape = null) {}
        public function whereNotIn(string $key = null, $values = null, bool $escape = null) {}
        public function like($field, string $match = '', string $side = 'both', bool $escape = null, bool $insensitiveSearch = false) {}
        public function orderBy(string $orderBy, string $direction = '', bool $escape = null) {}
        public function limit(?int $value = null, ?int $offset = 0) {}
        public function select($select = '*', ?bool $escape = null) {}
        public function join(string $table, string $cond, string $type = '', bool $escape = null) {}
        public function groupBy($by, ?bool $escape = null) {}
        public function set($key, $value = '', ?bool $escape = null) {}
        public function get() {}
        public function getRowArray() {}
        public function getResultArray() {}
        public function update($set = null, $where = null, ?int $limit = null) {}
        public function insert($set = null, bool $escape = null) {}
        public function delete($where = '', ?int $limit = null, bool $resetData = true) {}
    }
}

// Constants
define('ENVIRONMENT', 'development');
define('APP_TIMEZONE', 'Asia/Jakarta');
define('WRITEPATH', __DIR__ . '/writable/');
define('SYSTEMPATH', __DIR__ . '/vendor/codeigniter4/framework/system/');
define('ROOTPATH', __DIR__ . '/');
define('APPPATH', __DIR__ . '/app/');
define('FCPATH', __DIR__ . '/public/');
define('APP_OTP_EXPIRED_MINUTES', 5);

namespace App\Models {
    use CodeIgniter\Model;
    
    /**
     * AccessModel with custom methods
     * 
     * @method $this where($key, $value = null, bool $escape = null)
     * @method $this orWhere($key, $value = null, bool $escape = null)
     * @method $this set($key, $value = '', ?bool $escape = null)
     * @method bool update($id = null, $data = null)
     * @method bool|int insert($data = null, bool $returnID = true)
     * @method array|object|null first()
     * @method object get()
     */
    class AccessModel extends Model {
        public $insertID;
        
        public function getDataCustomer($email, $phoneNumber) {}
        public function getDetailCustomer($idCustomer) {}
        public function checkHardwareIDUserAdmin($idUserAdmin, $hardwareID) {}
        public function getDataRegional() {}
        public function getDataMerk() {}
        public function getDataBarangKategori() {}
        public function getUserAdminDetail($idUserAdmin) {}
    }
}

