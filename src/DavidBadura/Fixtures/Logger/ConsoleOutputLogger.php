<?php

namespace DavidBadura\Fixtures\Logger;

use Symfony\Component\Console\Output\OutputInterface;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class ConsoleOutputLogger implements Logger
{

    /**
     *
     * @var OutputInterface
     */
    protected $output;

    /**
     *
     * @param OutputInterface $output
     */
    function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     *
     * @param string $message
     */
    public function headline($message)
    {
        $this->output->writeln('<info>' . $message . '</info>');
    }

    /**
     *
     * @param string $message
     */
    public function log($message)
    {
        $this->output->writeln('<comment>> ' . $message . '</comment>');
    }

}