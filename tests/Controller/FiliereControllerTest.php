<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class FiliereControllerTest extends WebTestCase{
    public function testIndex(): void
    {
        $client = static::createClient();
       

        self::assertResponseIsSuccessful();
    }
}
