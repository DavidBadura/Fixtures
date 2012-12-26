<?php

namespace DavidBadura\Fixtures\Command;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use DavidBadura\Fixtures\Logger\ConsoleOutputLogger;

/**
 * Load data fixtures from bundles.
 *
 * @author David Badura <d.badura@gmx.de>
 */
class LoadDataFixturesCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('davidbadura:fixtures:load')
            ->setDescription('Load data fixtures and save it.')
            ->addOption('tag', 't', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'Load fixtures by tag', array())
            ->addOption('fixture', 'f', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'The directory or file to load data fixtures from.', array())
            ->addOption('test', null, InputOption::VALUE_NONE, 'Test process (dont save fixtures)')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $manager = $container->get('davidbadura_fixtures.fixture_manager');

        $logger = new ConsoleOutputLogger($output);

        $manager->load(array(
            'tags' => $input->getOption('tag'),
            'fixtures' => $input->getOption('fixture'),
            'test' => $input->getOption('test')
        ), $logger);
    }

}
