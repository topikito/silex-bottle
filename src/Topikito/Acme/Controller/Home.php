<?php

namespace Topikito\Acme\Controller;

use app\core;

class Home extends core\Controller
{

    public function index()
    {
        $html = 'HELLO WORLD';

        return new core\Response($html);
    }

}
