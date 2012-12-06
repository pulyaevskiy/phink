<?php
/*
 * This file is part of the Phink library.
 *
 * (c) Anatoly Pulyaevsky <anatoly@abstractionpower.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phink\Tests;

use Phink\Object;

/**
 * Tests for \Phink\Object
 *
 * @author Anatoly Pulyaevsky <anatoly@abstractionpower.net>
 */
class ObjectTest extends TestCase
{

    public function testDataParsedCorrectly()
    {
        $data = '100644 blob fd1aa0effc88d9e37e356c0150f7e1f64a16d2ae	composer.json';
        $object = new Object($data, static::$tmpDir);
        $this->assertEquals('100644', $object->getMode());
        $this->assertEquals('blob', $object->getType());
        $this->assertEquals('composer.json', $object->getName());
        $this->assertEquals(static::$tmpDir . DIRECTORY_SEPARATOR . 'composer.json', $object->getName(true));
        // Test __toString()
        $this->assertEquals('composer.json', (string) $object);
    }

}
