<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class homeTest extends WebTestCase
{
    public function testSomething(): void
    {
        /* Creation d'un client */
        $client = static::createClient();
        /* action du client vers un page spécifique */
        $crawler = $client->request('GET', '/');
        /* verifie le success */
        $this->assertResponseIsSuccessful();
       /* verifie le success par le resultat 'h1', 'Accueil' */ 
        $this->assertSelectorTextContains('h1', 'Accueil');
    }
}
