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


/**
 * Base abstract class for any ancestor who want to execute Git commands.
 *
 * @author Anatoly Pulyaevsky <anatoly@abstractionpower.net>
 *
 * @api
 */
abstract class AbstractGitExecutor
{
    protected $cwd;

    public function __construct($cwd)
    {
        $this->cwd = $cwd;
    }

    protected function exec($command)
    {
        $process = new GitProcess($command, $this->cwd);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new Exception($process->getErrorOutput());
        }

        return $process->getOutput();
    }

    public function getCwd()
    {
        return $this->cwd;
    }

}
