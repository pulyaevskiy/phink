<?php

/*
 * This file is part of the Phink library.
 *
 * (c) Anatoly Pulyaevsky <anatoly@abstractionpower.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phink\Command\Status;


class EntryCollection implements \IteratorAggregate {

    private $entryList = array();

    public function add(Entry $entry)
    {
        $this->entryList[] = $entry;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->entryList);
    }

    public function getStaged()
    {
        return new \ArrayIterator($this->entryList);
    }

    public function getModified()
    {
        return new \ArrayIterator($this->entryList);
    }

}
