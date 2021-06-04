<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RoutesTest extends WebTestCase
{
    public function urlProvider()
    {
        yield ['/hello'];
        yield ['/boutique'];
        yield ['/contact'];
        yield ['/commande'];
        yield ['/connexion'];
        yield ['/inscription'];
        yield ['/compte'];
        yield ['/rayon/1'];
        yield ['/produit/1'];

    }

    /**
     * @dataProvider urlProvider
     */
    public function testpageSuccess($url): void
    {
        $client = static::createClient();
        $client->followRedirects();
        $client->request('GET', $url);


        /*
        if($client->getResponse()->isSuccessful()){
            $this->assertTrue($client->getResponse()->isSuccessful());
        }else{
            $this->assertResponseRedirects();
        }*/


        $this->assertResponseIsSuccessful();

    }

}
