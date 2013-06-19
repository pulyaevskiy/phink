<?php

namespace Phink\Tests;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

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

    public static function createTestRepository($name)
    {
        $cwd = self::$tmpDir . '/' . $name;

        $fs = new Filesystem();
        if ($fs->exists($cwd)) {
            $fs->remove($cwd);
        }

        $fs->mkdir($cwd);

        $cmd = __DIR__ . '/Fixtures/create_test_repository.sh';
        $process = new Process($cmd, $cwd);
        $process->run();
        if ($process->isSuccessful()) {
            throw new \RuntimeException("Unable to create test repository.");
        }
        return $cwd;
    }

    public static function tearDownAfterClass()
    {
        $fs = new Filesystem();
        $fs->remove(self::$tmpDir);
    }
}
