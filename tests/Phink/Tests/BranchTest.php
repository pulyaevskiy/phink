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

use Phink\Branch;
use Phink\Repository;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Tests for \Phink\Branch
 *
 * @author Anatoly Pulyaevsky <anatoly@abstractionpower.net>
 */
class BranchTest extends TestCase
{

    protected static $repoDir;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        self::$repoDir = static::$tmpDir .'/branchtest';
        $fs = new Filesystem();
        $fs->mkdir(self::$repoDir);
        $repo = new Repository(self::$repoDir, true);

        // Create master branch for tests
        $fs->touch(self::$repoDir .'/file1.php');
        $repo->add();
        $repo->commit('Initial commit');
    }

    protected function getBranch()
    {
        return new Branch(self::$repoDir);
    }

    public function testGetList()
    {
        $branch = $this->getBranch();
        $list = $branch->getList();
        $this->assertEquals(array('master'), $list, 'There must be only master branch.');
    }

    public function testCreate()
    {
        $branch = $this->getBranch();
        $branch->create('staging');
        $list = $branch->getList();
        $this->assertEquals(array('master', 'staging'), $list);
    }

    /**
     * @expectedException \Phink\Exception
     */
    public function testExistingNotCreated()
    {
        $this->getBranch()->create('staging');
    }

    public function testGetCurrent()
    {
        $current = $this->getBranch()->getCurrent();
        $this->assertEquals('master', $current, 'Current branch doesn\'t match. Must be \'master\'.');
    }

    public function testSetCurrent()
    {
        $branch = $this->getBranch();
        $branch->setCurrent('staging');
        $this->assertEquals('staging', $branch->getCurrent(), 'Current branch doesn\'t match. Must be \'staging\'.');
    }

    /**
     * @expectedException \Phink\Exception
     */
    public function testSetCurrentNotExisting()
    {
        $this->getBranch()->setCurrent('not_exists');
    }

}
