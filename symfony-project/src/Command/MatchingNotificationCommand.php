<?php

namespace App\Command;

use App\Service\MatchingService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MatchingNotificationCommand extends Command
{
    protected static $defaultName = 'app:notify-matching';

    public function __construct(private MatchingService $matchingService)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->matchingService->calculateMatchAndNotify();
        $output->writeln('Notifications envoyées aux développeurs.');
        return Command::SUCCESS;
    }
}
