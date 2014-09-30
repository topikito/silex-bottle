<?php

namespace app\Config;

/**
 * Class AutoLoader
 *
 * @package app\config
 */
class Autoloader
{

    private $_root;

    public function __construct($rootFolder)
    {
        $this->_root = $rootFolder;
    }

    /**
     * @param $className
     *
     * @return bool|mixed
     */
    public function load($className)
    {
        if (strpos($className, 'app') !== 0) {
            $className = 'src\\' . $className;
        }

        $className = ltrim($className, '\\');
        $dirName  = '';
        if ($lastPos = strrpos($className, '\\')) {
            $spaceName = substr($className, 0, $lastPos);
            $className = substr($className, $lastPos + 1);
            $dirName  = str_replace('\\', DIRECTORY_SEPARATOR, $spaceName) . DIRECTORY_SEPARATOR;
        }
        $dirName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
        $dirName = $this->_root . DIRECTORY_SEPARATOR . $dirName;

        if (file_exists($dirName)) {
            $result = require_once $dirName;
        } else {
            $result = false;
        }

        return $result;
    }

}
