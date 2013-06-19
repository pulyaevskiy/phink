<?php


namespace Phink\Command;


use Phink\AbstractGitExecutor;

abstract class AbstractCommand extends AbstractGitExecutor implements CommandInterface {

    abstract public function getCommandString();

    public function execute()
    {
        return $this->exec($this->getCommandString());
    }

}