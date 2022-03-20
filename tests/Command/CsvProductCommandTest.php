<?php

namespace App\Tests\Command;

use App\Factory\ProductFactory;
use Carbon\Carbon;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class CsvProductCommandTest extends KernelTestCase
{
    use Factories;
    use ResetDatabase;

    protected function setUp(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1, 0, 0, 0));
    }

    protected function tearDown(): void
    {
        $file = __DIR__.'/../../export/csv/product_20180101000000.csv';

        if (file_exists($file)) {
            unlink($file);
        }
        parent::tearDown();
    }

    public function testExecute()
    {
        ProductFactory::createOne(['name' => 'Floppy Disk', 'description' => 'Lorem ipsum']);
        ProductFactory::createOne(['name' => 'Compact Disk', 'description' => 'Dolores']);

        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('app:csv:product');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $output = $commandTester->getStatusCode();
        $this->assertSame(Command::SUCCESS, $output);
        $this->assertStringContainsString('[OK] Csv generated with success to '.$kernel->getProjectDir().'/export/csv/product_20180101000000.csv', $commandTester->getDisplay());

        $generatedCsv = __DIR__.'/../../export/csv/product_20180101000000.csv';
        $expectedCsv = __DIR__.'/fixtures/export.csv';

        $this->assertFileEquals($expectedCsv, $generatedCsv);
    }
}
