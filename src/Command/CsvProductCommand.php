<?php

namespace App\Command;

use App\Repository\ProductRepository;
use Carbon\Carbon;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use League\Csv\Writer;

class CsvProductCommand extends Command
{
    protected static $defaultName = 'app:csv:product';
    protected static $defaultDescription = 'Add a short description for your command';

    private $repository;
    private $csvExportPath;

    public function __construct(ProductRepository $productRepository, string $csvExportPath)
    {
        $this->repository = $productRepository;
        $this->csvExportPath = $csvExportPath;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $products = $this->repository->findAll();

        $filePath = sprintf($this->csvExportPath . '/product_%s.csv', Carbon::now()->format('YmdHis'));
        $csv = Writer::createFromPath($filePath, 'w+');
        $csv->insertOne(['id', 'name', 'description']);

        foreach($products as $product) {
            $csv->insertOne([$product->getId(), $product->getName(), $product->getDescription()]);
        }

        $io->success(sprintf('Csv generated with success to %s', $filePath));

        return Command::SUCCESS;
    }
}
