<?php

namespace app\Config\Bridge;

use app\config\Interfaces\Loadable;
use Silex\Application;

/**
 * Class Service
 *
 * @package app\config\Bridges
 */
abstract class BaseServiceLoader implements Loadable
{

    /**
     * @var Application
     */
    protected $_app;

    protected $_sourceFolder;
    protected $_tempFolder;

    public function __construct(Application $app)
    {
        $this->_app = $app;

        $this->_sourceFolder = dirname(dirname(dirname(__DIR__))) . '/src/';
        $this->_tempFolder = dirname(dirname(dirname(__DIR__))) . '/tmp/';

        if (!is_dir($this->_tempFolder)) {
            mkdir($this->_tempFolder);
        }
    }

}
