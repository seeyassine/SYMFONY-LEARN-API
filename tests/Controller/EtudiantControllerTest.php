<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class EtudiantControllerTest extends WebTestCase{
    public function testIndex(): void
    {
        $client = static::createClient();
        // $client->request('GET', '/etudiant');

        self::assertResponseIsSuccessful();
    }
}
