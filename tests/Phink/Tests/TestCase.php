<?php

namespace Phink\Tests;

use Symfony\Component\Filesystem\Filesystem;

class TestCase extends \PHPUnit_Framework_TestCase
{
    protected static $tmpDir;

    public static function setUpBeforeClass()
    {
        static::$tmpDir = sys_get_temp_dir() . '/phink-' . md5(time() . mt_rand());
        $fs = new Filesystem();
        $fs->mkdir(static::$tmpDir);
        if (!is_writable(static::$tmpDir)) {
            static::markTestSkipped('There is no write permission in order to create repositories');
        }
    }

    public static function tearDownAfterClass()
    {
        $fs = new Filesystem();
        $fs->remove(static::$tmpDir);
    }
}
