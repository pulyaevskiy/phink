<?php

namespace Phink\Tests;

use Symfony\Component\Filesystem\Filesystem;

class TestCase extends \PHPUnit_Framework_TestCase
{
    protected static $tmpDir;

    protected static function generateUniqueString()
    {
        return md5(time() . mt_rand());
    }

    public static function setUpBeforeClass()
    {
        self::$tmpDir = sys_get_temp_dir() . '/phink-' . self::generateUniqueString();
        $fs = new Filesystem();
        $fs->mkdir(self::$tmpDir);
        if (!is_writable(self::$tmpDir)) {
            self::markTestSkipped('There is no write permission in order to create repositories');
        }
    }

    public static function tearDownAfterClass()
    {
        $fs = new Filesystem();
        $fs->remove(self::$tmpDir);
    }
}
