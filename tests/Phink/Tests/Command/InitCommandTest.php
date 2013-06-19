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


use Phink\Command\InitCommand;
use Phink\Tests\TestCase;

class InitCommandTest extends TestCase {

    public function testInit()
    {
        $command = new InitCommand(self::$tmpDir);
        $this->assertEquals('init', $command->getCommandString());
    }

    public function testInitQuiet()
    {
        $command = new InitCommand(self::$tmpDir);
        $command->quiet();
        $this->assertEquals('init --quiet', $command->getCommandString());
    }

    public function testInitBare()
    {
        $command = new InitCommand(self::$tmpDir);
        $command->bare();
        $this->assertEquals('init --bare', $command->getCommandString());
    }

    public function testInitTemplate()
    {
        $command = new InitCommand(self::$tmpDir);
        $command->template('dir/to/smthng');
        $this->assertEquals("init --template='dir/to/smthng'", $command->getCommandString());
    }

    public function testDirectory()
    {
        $command = new InitCommand(self::$tmpDir);
        $command->directory('dir/to/smthng');
        $this->assertEquals("init dir/to/smthng", $command->getCommandString());
    }



}
