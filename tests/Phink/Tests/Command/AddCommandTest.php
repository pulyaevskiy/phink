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


use Phink\Command\AddCommand;
use Phink\Tests\TestCase;

class AddCommandTest extends TestCase {

    public function testAddWithDryRun()
    {
        $command = new AddCommand(self::$tmpDir);
        $command->dryRun();
        $this->assertEquals('add --dry-run', $command->getCommandString());
    }

    public function testAddWithForce()
    {
        $command = new AddCommand(self::$tmpDir);
        $command->force();
        $this->assertEquals('add --force', $command->getCommandString());
    }

    /**
     * @expectedException Phink\Command\Exception\BadOptionsException
     */
    public function testAdd()
    {
        $command = new AddCommand(self::$tmpDir);
        $command->update()->all();
        $command->getCommandString();
    }

    public function testAddWithUpdate()
    {
        $command = new AddCommand(self::$tmpDir);
        $command->update();
        $this->assertEquals('add --update', $command->getCommandString());
    }

    public function testAddWithAll()
    {
        $command = new AddCommand(self::$tmpDir);
        $command->all();
        $this->assertEquals('add --all', $command->getCommandString());
    }

    public function testAddWithFilePattern()
    {
        $command = new AddCommand(self::$tmpDir);
        $command->filePattern('.');
        $this->assertEquals('add .', $command->getCommandString());
    }

}
