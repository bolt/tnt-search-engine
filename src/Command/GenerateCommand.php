<?php

declare(strict_types=1);

namespace Bolt\TntSearch\Command;

use Bolt\TntSearch\IndexGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class GenerateCommand extends Command
{
    /** @var string */
    protected static $defaultName = 'tnt-search:generate';

    /** @var IndexGenerator */
    private $generator;

    /**
     * @required
     */
    public function init(IndexGenerator $generator): void
    {
        $this->generator = $generator;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Generate the index for searching content.');
    }

    /**
     * This method is executed after initialize(). It usually contains the logic
     * to execute to complete this command task.
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $io->text('// Generating index');

        try {
            $this->generator->generate();
            $io->success('Index generated');
        } catch (\Throwable $t) {
            $io->error('Generating index failed');
            throw $t;
        }

        return Command::SUCCESS;
    }
}
