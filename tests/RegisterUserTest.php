<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterUserTest extends WebTestCase
{
    public function testSomething(): void
    {
        //1
        $client = static::createClient();
        $crawler = $client->request('GET', '/inscription');

        //2
        $client->submitForm('S\'inscrire', [
            "register_user[email]" => "julie@test.com",
            "register_user[plainPassword][first]" => "12345",
            "register_user[plainPassword][second]" => "12345",
            "register_user[firstname]" => "julie",
            "register_user[lastname]" => "test",
        ]);

        //FOLLOW
        $this->assertResponseRedirects("/connexion");
        $client->followRedirects();

        //3
        $this->assertSelectorExists('div:contains("Votre compte est correctement créer ! Connectez vous")');
    }
}