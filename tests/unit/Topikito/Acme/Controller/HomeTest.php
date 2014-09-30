<?php

namespace Tests\Unit\Topikito\Acme\Controller;

use Tests\Unit\FrameworkTestCase;

class HomeTest extends FrameworkTestCase {

    public function testHomePageReturns200WithOneInputForEmail() {
        $client = $this->createClient();
        $client->request('GET', '/');

        $this->assertTrue($client->getResponse()->isOk(),       'Response is not OK');
        $this->assertTrue(!$client->getResponse()->isEmpty(),   'Content is empty');
    }

}
