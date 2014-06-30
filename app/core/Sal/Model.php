<?php

namespace app\core\Sal;

use Silex\Application;

class Model
{

    /**
     * @var string
     */
    protected $_tableName;
    /**
     * @var \Silex\Application
     */
    protected $_app;

    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected $_db;

    public function __construct(Application $app)
    {
        $this->_app = $app;

        $this->_db = $this->_app['db'];
    }

}
