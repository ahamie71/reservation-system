<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class EventControllerTest extends WebTestCase
{
    public function testIndex(): void
{
    $client = static::createClient();
    $client->request('GET', '/events');

    echo $client->getResponse()->getContent(); // afficher le contenu

    self::assertResponseIsSuccessful();

}

public function testIndexPageIsSuccessful(): void
{
    $client = static::createClient();
    $client->request('GET', '/events');

    $this->assertResponseIsSuccessful(); // Vérifie que la réponse HTTP est 200
    $this->assertSelectorExists('h1');   // Vérifie que la page contient un <h1>
    $this->assertSelectorTextContains('h1', 'Événements');
    // Ex de vérif de contenu
}


}