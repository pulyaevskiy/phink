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
use Phink\AbstractGitExecutor;
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

    public function testGetList()
    {
        $branch = new Branch(self::$repoDir);
        $list = $branch->getList();
        $this->assertEquals(array('master'), $list, 'There must be only master branch.');
        return $branch;
    }

    /**
     * @depends testGetList
     */
    public function testCreate(Branch $branch)
    {
        $branch->create('staging');
        $list = $branch->getList();
        $this->assertEquals(array('master', 'staging'), $list);
        return $branch;
    }

    /**
     * @depends testCreate
     * @expectedException \Phink\Exception
     */
    public function testExistingNotCreated(Branch $branch)
    {
        $branch->create('staging');
    }

    /**
     * @depends testCreate
     */
    public function testGetCurrent(Branch $branch)
    {
        $current = $branch->getCurrent();
        $this->assertEquals('master', $current, 'Current branch doesn\'t match. Must be \'master\'.');
        return $branch;
    }

    /**
     * @depends testGetCurrent
     */
    public function testSetCurrent(Branch $branch)
    {
        $branch->setCurrent('staging');
        $this->assertEquals('staging', $branch->getCurrent(), 'Current branch doesn\'t match. Must be \'staging\'.');
        return $branch;
    }

    /**
     * @depends testSetCurrent
     * @expectedException \Phink\Exception
     */
    public function testSetCurrentNotExisting(Branch $branch)
    {
        $branch->setCurrent('not_exists');
    }

}
