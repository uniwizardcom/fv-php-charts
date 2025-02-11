<?php

namespace App\Core\Invoice\UserInterface\Cli;

use App\Common\UserIsActiveInoutParameterTrait;
use App\Core\Invoice\Application\Command\CreateInvoice\CreateInvoiceCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'app:invoice:create',
    description: 'Dodawanie nowej faktury'
)]
class CreateInvoice extends Command
{
    use UserIsActiveInoutParameterTrait;

    public function __construct(private readonly MessageBusInterface $bus)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->bus->dispatch(new CreateInvoiceCommand(
            $input->getArgument('email'),
            (int)$input->getArgument('amount'),
            (bool)$this->parseArgIsActive($input->getArgument('only_active_user'))
        ));

        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this->addArgument('email', InputArgument::REQUIRED);
        $this->addArgument('amount', InputArgument::REQUIRED);
        $this->addArgument('only_active_user', InputArgument::OPTIONAL);
    }
}
