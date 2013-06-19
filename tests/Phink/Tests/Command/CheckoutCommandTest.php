<?php


namespace Phink\Tests\Command;


use Phink\Command\CheckoutCommand;
use Phink\Tests\TestCase;

class CheckoutCommandTest extends TestCase {

    public function testCheckoutBranch()
    {
        $command = new CheckoutCommand(self::$tmpDir);
        $result = $command
            ->branch('staging')
            ->getCommandString();
        $this->assertEquals('checkout staging', $result);

        $result = $command
            ->branch('foobar')
            ->createBranch()
            ->getCommandString();
        $this->assertEquals('checkout -b foobar', $result);
    }

    public function testCheckoutTag()
    {
        $command = new CheckoutCommand(self::$tmpDir);
        $result = $command
            ->tag('v1.0.0')
            ->getCommandString();
        $this->assertEquals('checkout v1.0.0', $result);
    }

    public function testCheckoutCommit()
    {
        $command = new CheckoutCommand(self::$tmpDir);
        $result = $command
            ->commit('fs9d7fsd8')
            ->getCommandString();
        $this->assertEquals('checkout fs9d7fsd8', $result);
    }

}
