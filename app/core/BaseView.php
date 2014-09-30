<?php

namespace app\Core;

use Silex\Application;

/**
 * Class View
 *
 * @package app\core
 */
class BaseView
{

    /**
     * @var \Silex\Application
     */
    protected $_app;

    /**
     * @var array
     */
    protected $_params = [];

    /**
     * @var bool
     */
    protected $_minifyOutput = false;

    /**
     * @param Application $app
     * @param array       $params
     */
    public function __construct(Application $app, $params = [])
    {
        $this->_app = $app;

        if (!empty($params)) {
            $this->setParams($params);
        }

    }

    /**
     * @param $params
     *
     * @return $this
     */
    public function setParams($params)
    {
        $this->_params = array_merge($this->_params, (array) $params);
        return $this;
    }

    /**
     * @param $name
     *
     * @return $this
     */
    public function unsetParam($name)
    {
        unset($this->_params[$name]);
        return $this;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->_params;
    }

    /**
     * @param $name
     *
     * @return array
     */
    public function getParam($name)
    {
        if (isset($this->_params[$name])) {
            return $this->_params[$name];
        }
        return null;
    }

    /**
     * @return bool
     */
    public function resetParams()
    {
        $this->_params = [];
        return true;
    }

    /**
     * @param        $scripts
     * @param string $to
     *
     * @return $this
     */
    public function addScripts($scripts, $to = 'header')
    {
        $arr = ['scripts' => [$to => (array) $scripts]];
        return $this->setParams($arr);
    }

    /**
     * @param type $key
     * @param type $value
     *
     * @return array
     */
    public function addJsVariable($key, $value)
    {
        $this->_params['jsVariables'][$key] = $value;
        return $this->_params['jsVariables'];
    }

    /**
     * @param $key
     *
     * @return mixed
     */
    public function removeJsVariable($key)
    {
        if (isset($this->_params['jsVariables'][$key])) {
            unset($this->_params['jsVariables'][$key]);
        }
        return true;
    }

    /**
     * @param $minify
     *
     * @return app\core\View
     */
    public function setMinifyOutput($minifyOutput = true)
    {
        $this->_minifyOutput = $minifyOutput;
        return $this;
    }

    /**
     * @return array
     */
    public function initJsVariables()
    {
        if (!isset($this->_params['jsVariables'])) {
            $this->_params['jsVariables'] = [];
        }
    }

    /**
     * @param        $message
     * @param string $type
     * @param null   $duration
     *
     * @return bool
     */
    public function addFlashMessage($message, $type = 'success', $duration = null)
    {
        $params = [
            'type' => $type,
            'message' => $message,
            'duration' => $duration
        ];

        $flash = $this->renderSubTemplate('common/flash_message', $params);
        $this->setParams(['flash' => $flash]);
        return true;
    }

    /**
     * @param       $name
     * @param array $params
     *
     * @return mixed
     */
    public function renderSubTemplate($name, $params = [])
    {
        return $this->_app['twig']->render($name, $params);
    }

    /**
     * @param null  $templateName
     * @param array $args
     *
     * @return mixed
     */
    public function render($templateName = null, $args = [])
    {
        if (!empty($args)) {
            $this->setParams($args);
        }

        if (!empty($this->_params['jsVariables'])) {
            $this->_params['jsVariables'] = base64_encode(json_encode($this->_params['jsVariables']));
        }

        $output = $this->_app['twig']->render($templateName, $this->_params);

        if ($this->_minifyOutput) {
            $output = \JShrink\Minifier::minify($output);
        }

        return $output;
    }

    /**
     * @param int $errorCode
     *
     * @return mixed
     */
    public function renderError($errorCode = 500)
    {
        $this->_app->abort($errorCode);
        return false;
    }

}
