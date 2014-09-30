<?php

namespace Topikito\Acme\Helper;

use Topikito\Acme\Exception\UnexpectedTypeException;
use Silex\Application;

class Abort
{

    /**
     * @var array
     */
    private $_message = [
        400 => 'Bad request',
        401 => 'Unauthorized',
        404 => 'Page not found',
        500 => 'Server error',
    ];

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->_app = $app;
    }

    /**
     * @param null  $message
     * @param array $headers
     */
    public function badRequest($message = null, $headers = [])
    {
        $errorCode = 400;
        $message = is_null($message) ? $this->_message[$errorCode] : $message;
        $this->_app['errorData'] = ['message' => $message];

        // DIE
        $this->_app->abort($errorCode, $message, $headers);
    }

    /**
     * @param null  $message
     * @param array $headers
     */
    public function unauthorized($message = null, $headers = [])
    {
        $errorCode = 401;
        $message = is_null($message) ? $this->_message[$errorCode] : $message;
        $this->_app['errorData'] = ['message' => $message];

        // DIE
        $this->_app->abort($errorCode, $message, $headers);
    }

    /**
     * @param null  $message
     * @param array $headers
     */
    public function notFound($message = null, $headers = [])
    {
        $errorCode = 404;
        $message = is_null($message) ? $this->_message[$errorCode] : $message;
        $this->_app['errorData'] = ['message' => $message];

        // DIE
        $this->_app->abort($errorCode, $message, $headers);
    }

    /**
     * @param null  $message
     * @param array $headers
     */
    public function serverError($message = null, $headers = [])
    {
        $errorCode = 500;
        $message = is_null($message) ? $this->_message[$errorCode] : $message;
        $this->_app['errorData'] = ['message' => $message];

        // DIE
        $this->_app->abort($errorCode, $message, $headers);
    }

    /**
     * @param null  $message
     * @param array $headers
     */
    public function blockedUser($message = null, $headers = [])
    {
        if ($message == null) {
            $message = 'Your account has been blocked.';
        }

        $errorCode = 401;
        $message = is_null($message) ? $this->_message[$errorCode] : $message;
        $this->_app['errorData'] = ['message' => $message];

        // DIE
        $this->_app->abort($errorCode, $message, $headers);
    }

    /**
     * @param null  $message
     * @param array $headers
     */
    public function deletedUser($message = null, $headers = [])
    {
        if ($message == null) {
            $message = 'Your account has been deleted.';
        }

        $errorCode = 401;
        $message = is_null($message) ? $this->_message[$errorCode] : $message;
        $this->_app['errorData'] = ['message' => $message];

        // DIE
        $this->_app->abort($errorCode, $message, $headers);
    }

    public function unexpectedValueType($message = null, $headers = [])
    {
        $errorCode = 500;
        $message = is_null($message) ? 'Unexpected value type' : $message;

        throw new UnexpectedTypeException($errorCode, $message, null, $headers);
    }

}
