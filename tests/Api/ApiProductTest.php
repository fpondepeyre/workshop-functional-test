<?php

namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Product;
use App\Factory\ProductFactory;
use App\Message\SendEmailMessage;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

class ApiProductTest extends ApiTestCase
{
    use ResetDatabase;
    use Factories;
    use InteractsWithMessenger;

    public function setUp(): void
    {
        $this->messenger('async')->reset();
    }

    public function testList(): void
    {
        $product = ProductFactory::createOne([
            'name' => 'Floppy Disk',
            'description' => 'Lorem Ipsum',
        ]);

        static::createClient()->request('GET', '/api/products');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/api/contexts/Product',
            '@id' => '/api/products',
            '@type' => 'hydra:Collection',
            'hydra:member' => [
                [
                    '@id' => '/api/products/'.$product->getId(),
                    '@type' => 'Product',
                    'id' => $product->getId(),
                    'name' => 'Floppy Disk',
                    'description' => 'Lorem Ipsum',
                    'imageFilename' => 'floppy-disc.png',
                    'imageUrl' => '/uploads/products/floppy-disc.png',
              ],
            ],
            'hydra:totalItems' => 1,
        ]);

        $this->assertMatchesResourceCollectionJsonSchema(Product::class);
    }

    public function testGet(): void
    {
        $product = ProductFactory::createOne([
            'name' => 'Floppy Disk',
        ]);

        static::createClient()->request('GET', '/api/products/'.$product->getId(), []);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@context' => '/api/contexts/Product',
            '@id' => '/api/products/'.$product->getId(),
            '@type' => 'Product',
            'id' => $product->getId(),
            'name' => 'Floppy Disk',
        ]);
    }

    public function testPost(): void
    {
        static::createClient()->request('POST', '/api/products', [
            'json' => [
                'name' => 'Floppy Disk',
                'imageFilename' => 'pen.png',
            ],
        ]);

        $product = ProductFactory::find(['name' => 'Floppy Disk']);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@context' => '/api/contexts/Product',
            '@id' => '/api/products/'.$product->getId(),
            '@type' => 'Product',
            'id' => $product->getId(),
            'name' => 'Floppy Disk',
            'imageFilename' => 'pen.png',
            'imageUrl' => '/uploads/products/pen.png',
        ]);
    }

    public function testSendEmail(): void
    {
        $this->messenger('async')->queue()->assertEmpty();

        $product = ProductFactory::findBy(['name' => 'Floppy Disk']);
        $this->assertEmpty($product);

        $response = static::createClient()->request('POST', '/api/products', [
            'json' => [
                'name' => 'Floppy Disk',
                'imageFilename' => 'pen.png',
            ],
        ]);

        // assert db
        $product = ProductFactory::find(['name' => 'Floppy Disk']);
        $this->assertNotNull($product);
        $this->assertSame('Floppy Disk', $product->getName());

        // assert messenger
        $this->messenger('async')->queue()->assertNotEmpty();
        $this->messenger('async')->queue()->assertCount(1);
        $this->messenger('async')->queue()->assertContains(SendEmailMessage::class);

        $this->messenger('async')->process(1);
        $this->messenger('async')->queue()->assertEmpty();

        // assert mail
        $this->assertEmailCount(1);
        $email = $this->getMailerMessage();
        $this->assertSame('Time for Symfony Mailer!', $email->getSubject());
        $this->assertEmailHtmlBodyContains($email, 'See Twig integration for better HTML integration!');

        $this->assertResponseIsSuccessful();
    }
}
