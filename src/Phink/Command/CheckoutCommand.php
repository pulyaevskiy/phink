<?php


namespace Phink\Command;


class CheckoutCommand extends AbstractCommand {

    /** @var string Either a branch/tag name or a commit hash. */
    private $path;

    /** @var  boolean If true then new branch will be created. */
    private $createBranch;

    public function branch($branchName)
    {
        $this->path = $branchName;
        return $this;
    }

    public function tag($tagName)
    {
        $this->path = $tagName;
        return $this;
    }

    public function commit($commitHash)
    {
        $this->path = $commitHash;
        return $this;
    }

    public function createBranch()
    {
        $this->createBranch = true;
        return $this;
    }

    public function getCommandString()
    {
        $command = 'checkout';

        if ($this->createBranch) {
            $command .= ' -b';
        }

        $command .= " $this->path";

        return $command;
    }

}
