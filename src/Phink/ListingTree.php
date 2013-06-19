<?php

/*
 * This file is part of the Phink library.
 *
 * (c) Anatoly Pulyaevsky <anatoly@abstractionpower.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phink;

use Iterator;

/**
 * ListingTree is basically wrapper around 'git ls-tree' command.
 *
 * @author Anatoly Pulyaevsky <anatoly@abstractionpower.net>
 *
 * @api
 */
class ListingTree extends AbstractGitExecutor implements Iterator
{
    protected $objects;
    protected $subPath;
    protected $treeish;

    /**
     * Class constructor.
     *
     * @param string $cwd Path to Git repository
     * @param null|string $subPath A subpath within the repository
     * @param string $treeish An ID of tree-ish
     */
    public function __construct($cwd, $subPath = null, $treeish = 'HEAD')
    {
        parent::__construct($cwd);
        $this->subPath = is_null($subPath) ? $subPath : trim($subPath, '/');
        $this->treeish = $treeish;
        $this->update();
    }

    protected function update()
    {
        $command = "ls-tree {$this->treeish}";
        $command .= is_null($this->subPath) ? '' : ":{$this->subPath}";
        $output = $this->exec($command);
        $this->objects = array();
        $path = is_null($this->subPath) ? $this->cwd : $this->cwd . '/' . $this->subPath;
        foreach (explode(PHP_EOL, $output) as $line) {
            if (trim($line) == false) continue;
            $this->append(new Object($line, $path));
        }
        // Sort tree
        uasort($this->objects, array($this, 'sort'));

    }

    protected function append(Object $object)
    {
        $this->objects[$object->getName()] = $object;
    }

    protected function sort(Object $a, Object $b)
    {
        if ($a->getType() == $b->getType()) {
            return strcmp($a->getName(), $b->getName());
        } else {
            return ($a->getType() == 'tree') ? -1 : 1;
        }
    }

    /**
     * Return the current element.
     *
     * @link http://php.net/manual/en/iterator.current.php
     *
     * @return Object
     */
    public function current()
    {
        return current($this->objects);
    }

    /**
     * Move forward to next element.
     *
     * @link http://php.net/manual/en/iterator.next.php
     *
     * @return void
     */
    public function next()
    {
        next($this->objects);
    }

    /**
     * Return the key of the current element.
     *
     * @link http://php.net/manual/en/iterator.key.php
     *
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        return key($this->objects);
    }

    /**
     * Checks if current position is valid
     *
     * @link http://php.net/manual/en/iterator.valid.php
     *
     * @return boolean The return value will be casted to boolean and then evaluated.
     *      Returns true on success or false on failure.
     */
    public function valid()
    {
        return current($this->objects);
    }

    /**
     * Rewind the Iterator to the first element
     *
     * @link http://php.net/manual/en/iterator.rewind.php
     *
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        reset($this->objects);
    }
}
