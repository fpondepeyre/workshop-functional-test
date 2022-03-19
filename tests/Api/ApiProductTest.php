<?php

namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Factory\ProductFactory;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class ApiProductTest extends ApiTestCase
{
    use ResetDatabase;
    use Factories;

    public function testList(): void
    {
        $response = static::createClient()->request('GET', '/api/products');

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['@id' => '/api/products']);
    }

    public function testGet(): void
    {
        $product = ProductFactory::createOne([
            'name' => 'Floppy Disk'
        ]);

        static::createClient()->request('GET', '/api/products/' . $product->getId(), []);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            "@context" => "/api/contexts/Product",
            "@id" => "/api/products/" . $product->getId(),
            "@type" => "Product",
            "id" => $product->getId(),
            "name" => "Floppy Disk",
            "colors" => [],
        ]);
    }

    public function testPost(): void
    {
        static::createClient()->request('POST', '/api/products', [
            'json' => [
                'name' => 'Floppy Disk'
            ]
        ]);

        $product = ProductFactory::find(['name' => 'Floppy Disk']);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            "@context" => "/api/contexts/Product",
            "@id" => "/api/products/" . $product->getId(),
            "@type" => "Product",
            "id" => $product->getId(),
            "name" => "Floppy Disk",
            "colors" => [],
        ]);
    }
}
