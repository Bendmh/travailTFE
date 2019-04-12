<?php
/**
 * Created by PhpStorm.
 * User: B
 * Date: 16-02-19
 * Time: 16:44
 */

namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class RouteControllerTest extends WebTestCase{

    /**
     * @dataProvider provideUrls
     */
    public function testPageIsSuccessful($url){

        $client = static::createClient();
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful());

    }

    public function provideUrls()
    {
        return [
            ['/'],
            ['/inscription'],
            ['/activity'],
            ['/activity/new'],
            ['/connexion']
        ];
    }

}