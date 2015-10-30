<?php

namespace DavidBadura\Fixtures\Extension\Symfony\Command;

use DavidBadura\Fixtures\FixtureManager\FixtureManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Load data fixtures from bundles.
 *
 * @author David Badura <d.badura@gmx.de>
 */
class LoadFixturesCommand extends Command
{
    /**
     * @var FixtureManagerInterface
     */
    protected $fixtureManager;

    /**
     * @param FixtureManagerInterface $fixtureManager
     */
    public function __construct(FixtureManagerInterface $fixtureManager)
    {
        parent::__construct();

        $this->fixtureManager = $fixtureManager;
    }

    /**
     *
     */
    protected function configure()
    {
        $this
            ->setName('davidbadura:fixtures:load')
            ->setDescription('Load data fixtures and save it.')
            ->addOption('fixture', 'f', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
                'The directory or file to load data fixtures from.', array())
            ->addOption('tag', 't', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'Load fixtures by tag',
                array())
            ->addOption('dry_run', null, InputOption::VALUE_NONE, 'Test process (dont save fixtures)');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->fixtureManager->load($input->getOption('fixture'), array(
            'tags' => $input->getOption('tag'),
            'dry_run' => $input->getOption('dry_run')
        ));
    }
}
