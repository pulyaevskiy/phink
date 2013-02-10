<?php

namespace Phink\Tests;

use Phink\Repository;
use Symfony\Component\Filesystem\Filesystem;

class RepositoryTest extends TestCase
{

    protected function getRepository($name)
    {
        $cwd = self::$tmpDir . '/' . $name;
        if (!file_exists($cwd)) {
            $fs = new Filesystem();
            $fs->mkdir($cwd);
        }
        return new Repository($cwd);
    }

    protected function makeRepositoryDirty(Repository $repository)
    {
        $fs = new Filesystem();
        $filename = self::generateUniqueString() . '.php';
        $filepath = $repository->getCwd() . '/' . $filename;
        $fs->touch($filepath);
        return $filename;
    }

    public function testInit()
    {
        $repo = $this->getRepository('test');
        $this->assertFalse(Repository::exists($repo->getCwd()));
        $repo->init();
        $this->assertTrue(Repository::exists($repo->getCwd()), 'Repository wasn\'t created');
    }

    public function testCloneExisting()
    {
        $repo = $this->getRepository('clonetest');
        $repo->cloneExisting('git://github.com/pulyaevsky/phink.git');
        $this->assertTrue(Repository::exists($repo->getCwd()));
    }

    /**
     * @depends testCloneExisting
     * @expectedException \Phink\Exception
     */
    public function testCloneInNonEmptyDir()
    {
        $repo = $this->getRepository('clonetest');
        $repo->cloneExisting('git://github.com/pulyaevsky/phink.git');
    }

    /**
     * @expectedException \Phink\Exception
     */
    public function testInitForUnexistedDir()
    {
        new Repository(static::$tmpDir .'/notexists', true);
    }

    public function testIsDirty()
    {
        $repo = $this->getRepository('dirtytest');
        $repo->init();
        $this->assertFalse($repo->isDirty());
        $this->makeRepositoryDirty($repo);
        $this->assertTrue($repo->isDirty());
    }

    public function testGetUnstagedChanges()
    {
        $repo = $this->getRepository('unstagedtest');
        $repo->init();
        $filename = $this->makeRepositoryDirty($repo);
        $list = $repo->getUnstagedChanges();
        $this->assertEquals(array($filename), $list, 'Unstaged changes doesn\'t match.');
    }

    public function testGetStagedChangesEmpty()
    {
        $repo = $this->getRepository('stagedtest');
        $repo->init();
        $list = $repo->getStagedChanges();
        $this->assertEquals(array(), $list, 'List of staged files must be empty.');
    }

    public function testAddFile()
    {
        $repo = $this->getRepository('addtest');
        $repo->init();
        $filename = $this->makeRepositoryDirty($repo);
        $repo->add($filename);
        $list = $repo->getStagedChanges();
        $this->assertEquals(array($filename), $list);
    }

    public function testAddAllFiles()
    {
        $repo = $this->getRepository('addalltest');
        $repo->init();
        $filename1 = $this->makeRepositoryDirty($repo);
        $filename2 = $this->makeRepositoryDirty($repo);
        $expected = array($filename1, $filename2);
        sort($expected);
        $repo->add();
        $list = $repo->getStagedChanges();
        $this->assertEquals($expected, $list);
    }


    public function testCommit()
    {
        $repo = $this->getRepository('committest');
        $repo->init();
        $this->makeRepositoryDirty($repo);
        $repo->add();
        $repo->commit('Added new files');
        // Staged changes must be empty after successful commit
        $this->assertEquals(array(), $repo->getStagedChanges());
    }
}
