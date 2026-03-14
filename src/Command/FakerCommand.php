<?php

namespace App\Command;

use App\Service\FakerService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:faker',
    description: 'Create fake datas',
)]
class FakerCommand extends Command
{
    private $fakerService;

    public function __construct(FakerService $fakerService)
    {
        $this->fakerService = $fakerService;
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
        $this->fakerService->createAll();
        
        return Command::SUCCESS;
    }
}
