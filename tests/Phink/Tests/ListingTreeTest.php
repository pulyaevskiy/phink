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

use Phink\ListingTree;
use Phink\Object;
use Phink\Repository;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Tests for \Phink\ListingTree
 *
 * @author Anatoly Pulyaevsky <anatoly@abstractionpower.net>
 */
class ListingTreeTest extends TestCase
{

    protected static $repoPath;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        $fs = new Filesystem();
        static::$repoPath = static::$tmpDir . '/listingtree';
        $fs->mkdir(static::$repoPath);
        $repo = new Repository(static::$repoPath, true);
        $fs->touch(static::$repoPath . '/cde.php');
        $fs->touch(static::$repoPath . '/abc.php');
        $fs->mkdir(static::$repoPath . '/bcd');
        $fs->touch(static::$repoPath . '/bcd/abc.php');
        $fs->touch(static::$repoPath . '/bcd/5bc.php');
        $repo->add();
        $repo->commit('Initial commit');
    }

    public function testListing()
    {
        $ls = new ListingTree(static::$repoPath);
        $expected = array(
            'bcd' => 'tree',
            'abc.php' => 'blob',
            'cde.php' => 'blob',
        );
        reset($expected);
        /**
         * @var Object $object
         */
        foreach ($ls as $object) {
            $this->assertEquals(key($expected), $object->getName());
            $this->assertEquals(current($expected), $object->getType());
            next($expected);
        }
    }

    public function testListingSubtree()
    {
        $ls = new ListingTree(static::$repoPath, 'bcd');
        $expected = array(
            '5bc.php' => 'blob',
            'abc.php' => 'blob',
        );
        reset($expected);
        /**
         * @var Object $object
         */
        foreach ($ls as $object) {
            $this->assertEquals(key($expected), $object->getName());
            $this->assertEquals(current($expected), $object->getType());
            next($expected);
        }
    }

}
