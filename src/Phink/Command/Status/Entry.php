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


class Entry {

    private $status;

    private $path;

    private $oldPath;

    public static function createWithInitialValues($values)
    {
        $instance = new self();
        $instance->status = $values['status'];
        $instance->path = $values['path'];
        $instance->oldPath = $values['oldPath'];

        return $instance;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getOldPath()
    {
        return $this->oldPath;
    }

    public function isRenamed()
    {
        return is_string($this->oldPath);
    }

}
