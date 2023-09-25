<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class LoginTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();

        /** @var UrlgeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get("router");

        $crawler = $client->request('GET', $urlGenerator->generate('app_login'));

       /*  $submitButton = $crawler->selectButton('Se Connecter');

        $form = $submitButton->form();
 */
        $form = $crawler->filter("form[name=login]")->form([
            "email"=>"steph97tow@gmail.com",
            "password"=>"Hillions12?"
        ]);

        $client->submit($form); 

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $client->followRedirect();

        $this->assertRouteSame('app_login');
    }
}
