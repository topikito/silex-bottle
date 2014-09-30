<?php

namespace Topikito\Acme\Config\Middleware;

use app\config\Bridge\BaseMiddleware;
use app;

class ErrorHandler extends BaseMiddleware
{

    private $_errorSendEmailIfLevel = [
        500
    ];

    /**
     * @var array
     */
    private $_errorDefaultMessages = [
        400 => 'Bad request',
        401 => 'Unauthorized',
        404 => 'Page not found',
        500 => 'Server error',
    ];

    private function _loadError()
    {
        $this->_app->error(function (\Exception $e, $code) {
                if (!$code) {
                    $code = 500;
                }

                // Don't log 4XX errors
                if ($code >= 500 or $code < 400) {
                    error_log($e);
                }

                $eMessage = $e->getMessage();
                $eTrace = $e->getTraceAsString();

                $message = $this->_errorDefaultMessages[$code];
                if (isset($this->_app['errorData']) && isset($this->_app['errorData']['message'])) {
                    $message = $this->_app['errorData']['message'];
                }

                $params = ['message' => $message, 'code' => $code];

                if ($this->_app['debug']) {
                    $params['eMessage'] = $eMessage;
                    $params['eTrace'] = $eTrace;
                }

                $view  = new app\core\BaseView($this->_app);
                $html = $view->render('Error\index.html.twig', $params);

                return new app\core\BaseResponse($html, $code);
            });
    }

    public function load()
    {
        $this->_loadError();
    }

}
