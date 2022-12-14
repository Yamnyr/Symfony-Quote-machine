<?php

namespace App\Tests\Controller;

use App\Factory\UserFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\Factories;

class CitationTest extends WebTestCase
{
    use Factories;

    public function testListCitation(): void
    {
        $client = static::createClient();
        $client->request('GET', '/quote');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Liste des citations');
    }

    public function testNew(): void
    {
        $client = static::createClient();
        $client->loginUser(UserFactory::createOne()->object());

        $client->request('GET', '/quote/new');
        $client->submitForm('Ajouter', [
            'quote[content]' => 'Exemple de citation',
            'quote[meta]' => 'Anonyme',
        ]);
        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('quote_index');
        $this->assertSelectorTextNotContains('body', 'Aucun résultat trouvé');
        $this->assertSelectorTextContains('body', 'Exemple de citation');
    }

    public function testEdit(): void
    {
        $client = static::createClient();
        $client->loginUser(UserFactory::createOne()->object());

        $client->request('GET', '/quote/new');
        $client->submitForm('Ajouter', [
            'quote[content]' => 'Exemple de citation',
            'quote[meta]' => 'Anonyme',
        ]);
        $client->followRedirect();

        $client->clickLink('modifier');
        $client->submitForm('modifier', [
            'quote[content]' => 'Exemple de citation modifiée',
            'quote[meta]' => 'Anonyme',
        ]);
        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('quote_index');
        $this->assertSelectorTextContains('body', 'Exemple de citation modifiée');
    }

    public function testDelete(): void
    {
        $client = static::createClient();
        $client->loginUser(UserFactory::createOne()->object());

        $client->request('GET', '/quote/new');
        $client->submitForm('Ajouter', [
            'quote[content]' => 'Exemple de citation',
            'quote[meta]' => 'Anonyme',
        ]);
        $client->followRedirect();

        $client->clickLink('supprimer');
        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('quote_index');
        $this->assertSelectorTextContains('body', 'Aucun résultat trouvé');
    }
}
