<?php

namespace App\Tests;

use Symfony\Component\Panther\PantherTestCase;
use Zenstruck\Browser\Test\HasBrowser;

class DummyPantherControllerTest extends PantherTestCase
{
    use HasBrowser;

    public function testCreatePantherClient(): void
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/');

        $this->assertSelectorTextContains('h1', 'Welcome to your new controller');
    }

    public function testPantherBrowser(): void
    {
        $this->pantherBrowser()
            ->visit('/')
            ->assertSeeIn('h1', 'Welcome to your new controller');
    }
}
