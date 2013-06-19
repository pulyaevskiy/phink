<?php

/*
 * This file is part of the Phink library.
 *
 * (c) Anatoly Pulyaevsky <anatoly@abstractionpower.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phink\Tests\Command;


use Phink\Command\PullCommand;
use Phink\Tests\TestCase;

class PullCommandTest extends TestCase {

    public function testPull()
    {
        $command = new PullCommand(self::$tmpDir);
        $this->assertEquals('pull', $command->getCommandString());
    }

    public function testPullFrom()
    {
        $command = new PullCommand(self::$tmpDir);
        $command->from('origin', 'master');
        $this->assertEquals('pull origin master', $command->getCommandString());
    }

    public function testPullFromWithRebase()
    {
        $command = new PullCommand(self::$tmpDir);
        $command->from('origin', 'master')
            ->rebase();
        $this->assertEquals('pull --rebase origin master', $command->getCommandString());
    }


}
