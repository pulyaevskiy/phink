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


use Phink\AbstractGitExecutor;

abstract class AbstractCommand extends AbstractGitExecutor implements CommandInterface {

    abstract public function getCommandString();

    public function execute()
    {
        return $this->exec($this->getCommandString());
    }

}