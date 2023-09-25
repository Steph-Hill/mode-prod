<?php

namespace App\Tests\Unit;

use App\Entity\Contact;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ContactTest extends KernelTestCase
{
    public function testSomething(): void
    {
        $kernel = self::bootKernel();

        $container=$this->getContainer();

        $contact = new Contact();
        $contact->setName('Million Stéphen')
                    ->setEmail('Email@live.fr')
                    ->setPostalAddress('PostalAddress #1')
                    ->setCodePostal('98633')
                    ->setMessage('Lorens ipsom')
                    ->setObjet('Bonjour')
                    ->setCreatedAt(new \DateTimeImmutable());
        
                    $errors = $container->get('validator')->validate($contact);

                    $this->assertCount(0,$errors);
    }
}
