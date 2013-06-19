<?php

/*
 * This file is part of the Phink library.
 *
 * (c) Anatoly Pulyaevsky <anatoly@abstractionpower.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phink\Command;


class PullCommand extends AbstractCommand {

    /** @var string Remote name and branch name from where to pull changes. */
    private $from;

    /** @var bool If set to 'true' then changes will be rebased instead of standard merge. */
    private $rebase = false;

    public function getCommandString()
    {
        $command = 'pull';

        if ($this->rebase) {
            $command .= ' --rebase';
        }

        if ($this->from) {
            $command .= " $this->from";
        }

        return $command;
    }

    /**
     * Specify which $remote and $branch to pull changes from.
     *
     * @param string $remote The name of a remote.
     * @param string $refspec Can be the name of a branch, tag or even a collection of refs.
     *
     * @return $this
     */
    public function from($remote, $refspec)
    {
        $this->from = "$remote $refspec";
        return $this;
    }

    /**
     * @return $this
     */
    public function rebase()
    {
        $this->rebase = true;
        return $this;
    }

}
