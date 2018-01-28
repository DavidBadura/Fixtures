<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Extension\Symfony\Command;

use DavidBadura\Fixtures\FixtureManager\FixtureManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class LoadFixturesCommand extends Command
{
    protected $fixtureManager;

    public function __construct(FixtureManagerInterface $fixtureManager)
    {
        parent::__construct();

        $this->fixtureManager = $fixtureManager;
    }

    protected function configure(): void
    {
        $this
            ->setName('davidbadura:fixtures:load')
            ->setDescription('Load data fixtures and save it.')
            ->addOption(
                'fixture',
                'f',
                InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
                'The directory or file to load data fixtures from.',
                []
            )
            ->addOption(
                'tag',
                't',
                InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
                'Load fixtures by tag',
                []
            )
            ->addOption('dry_run', null, InputOption::VALUE_NONE, 'Test process (dont save fixtures)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->fixtureManager->load($input->getOption('fixture'), [
            'tags' => $input->getOption('tag'),
            'dry_run' => $input->getOption('dry_run'),
        ]);

        return 0;
    }
}
