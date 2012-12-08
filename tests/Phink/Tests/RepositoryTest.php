<?php

namespace Phink\Tests;

use Phink\Repository;
use Symfony\Component\Filesystem\Filesystem;

class RepositoryTest extends TestCase
{
    public function testInit()
    {
        $cwd = static::$tmpDir .'/repositorytest';
        $fs = new Filesystem();
        $fs->mkdir($cwd);
        $repo = new Repository($cwd);
        $this->assertFalse($repo::exists($cwd));
        $repo->init();
        $this->assertTrue($repo::exists($cwd), 'Repository wasn\'t created');
        return $cwd;
    }

    /**
     * @expectedException \Phink\Exception
     */
    public function testInitForUnexistedDir()
    {
        new Repository(static::$tmpDir .'/somedir', true);
    }

    /**
     * @depends testInit
     */
    public function testIsDirty($cwd)
    {
        $repo = new Repository($cwd);
        $this->assertFalse($repo->isDirty());
        $fs = new Filesystem();
        $fs->touch($cwd . '/file1.php');
        $this->assertTrue($repo->isDirty());
        return $cwd;
    }

    /**
     * @depends testIsDirty
     */
    public function testGetUnstagedChanges($cwd)
    {
        $repo = new Repository($cwd);
        $list = $repo->getUnstagedChanges();
        $this->assertEquals(array('file1.php'), $list, 'Unstaged changes doesn\'t match.');
        return $cwd;
    }

    /**
     * @depends testGetUnstagedChanges
     */
    public function testGetStagedChangesEmpty($cwd)
    {
        $repo = new Repository($cwd);
        $list = $repo->getStagedChanges();
        $this->assertEquals(array(), $list, 'List of staged files must be empty.');
        return $cwd;
    }

    /**
     * @depends testGetStagedChangesEmpty
     */
    public function testAddFile($cwd)
    {
        $repo = new Repository($cwd);
        $repo->add('file1.php');
        $list = $repo->getStagedChanges();
        $this->assertEquals(array('file1.php'), $list);
        return $cwd;
    }

    /**
     * @depends testAddFile
     */
    public function testAddAllFiles($cwd)
    {
        $repo = new Repository($cwd);
        $fs = new Filesystem();
        $fs->touch($cwd . '/file2.php');
        $fs->mkdir($cwd . '/asubdir');
        $fs->touch($cwd . '/asubdir/file3.php');
        $list = $repo->getUnstagedChanges();
        // Files and directories are ordered alphabetically
        $this->assertEquals(array('asubdir/', 'file2.php'), $list);
        $repo->add();
        $list = $repo->getStagedChanges();
        $this->assertEquals(array('asubdir/file3.php', 'file1.php', 'file2.php'), $list);
        return $cwd;
    }

    /**
     * @depends testAddAllFiles
     */
    public function testCommit($cwd)
    {
        $repo = new Repository($cwd);
        $repo->commit('Added new files');
        // Staged changes must be empty after successful commit
        $this->assertEquals(array(), $repo->getStagedChanges());
        return $cwd;
    }
}

