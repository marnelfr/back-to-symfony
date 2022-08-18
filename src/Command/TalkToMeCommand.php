<?php

namespace App\Command;

use App\Service\MixRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use function Symfony\Component\String\u;

#[AsCommand(
    name: 'app:talk-to-me',
    description: 'Funny command just to learn stuff about',
)]
class TalkToMeCommand extends Command
{

    public function __construct(private MixRepository $repository, string $name = null)
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::OPTIONAL, 'Your name')
            ->addOption('yell', null, InputOption::VALUE_NONE, 'Should I yell ?')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $name = $input->getArgument('name') ?: 'who ever you are';

        if ($name) {
            $message = sprintf('Hello %s !', $name);
        }

        if ($input->getOption('yell')) {
            $message = u(sprintf('Hello %s !', $name))->upper();
        }

        $io->success($message);

        if($io->confirm('Do you want a recommendation ?')) {
            $mixes = $this->repository->findAll();
            $mix = $mixes[array_rand($mixes)];
            $io->note('I recommend ' . $mix['title']);
            $io->note('Bye Symfony 6 !!');
        }

        return Command::SUCCESS;
    }
}
