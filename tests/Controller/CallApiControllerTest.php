<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CallApiControllerTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/call_api');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorTextContains('h1', 'Products');

        $this->assertSame(10, $client->getCrawler()->filter('.product')->count());
        $this->assertSame('1000004, Sausages - € 418.37', $client->getCrawler()->filter('body > ul:nth-child(2) > li:nth-child(4)')->html());
        $this->assertSame('1000009, Bottel - € 457.00', $client->getCrawler()->filter('body > ul:nth-child(2) > li:nth-child(9)')->html());
    }
}
