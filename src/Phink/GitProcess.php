<?php

/*
 * This file is part of the Phink library.
 *
 * (c) Anatoly Pulyaevsky <anatoly@abstractionpower.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phink;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\RuntimeException;
use Symfony\Component\Process\ExecutableFinder;

/**
 * GitProcess runs a Git command in an independent process.
 *
 * @author Anatoly Pulyaevsky <anatoly@abstractionpower.net>
 *
 * @api
 */
class GitProcess extends Process
{
    private $command;
    private $executableFinder;

    /**
     * Constructor.
     *
     * @param string $command  Git command to execute
     * @param string  $cwd     The working directory
     * @param array   $env     The environment variables
     * @param integer $timeout The timeout in seconds
     * @param array   $options An array of options for proc_open
     *
     * @api
     */
    public function __construct($command, $cwd = null, array $env = array(), $timeout = 60, array $options = array())
    {
        parent::__construct(null, $cwd, $env, null, $timeout, $options);
        $this->command = $command;
        $this->executableFinder = new ExecutableFinder();
    }

    /**
     * Runs the process.
     *
     * @param Closure|string|array $callback A PHP callback to run whenever there is some
     *                                       output available on STDOUT or STDERR
     *
     * @throws \Symfony\Component\Process\Exception\RuntimeException
     *
     * @return integer The exit status code
     *
     * @api
     */
    public function run($callback = null)
    {
        if (null === $this->getCommandLine()) {
            if (false === $git = $this->executableFinder->find('git')) {
                throw new RuntimeException('Unable to find the Git executable.');
            }
            $this->setCommandLine("$git {$this->command}");
        }

        return parent::run($callback);
    }

}
