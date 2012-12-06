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

/**
 * Object represents Git objects.
 *
 * @author Anatoly Pulyaevsky <anatoly@abstractionpower.net>
 *
 * @api
 */
class Object
{

    protected $mode;
    protected $type;
    protected $object;
    protected $path;
    protected $name;

    public function __construct($data, $path)
    {
        $this->path = $path;
        $this->updateFromData($data);
    }

    protected function updateFromData($data)
    {
        preg_match('/(\d{6})\s([a-z]*)\s([a-zA-Z0-9]{40})\t(.*)/', $data, $matches);
        $this->mode = $matches[1];
        $this->type = $matches[2];
        $this->object = $matches[3];
        $this->name = $matches[4];
    }

    public function getMode()
    {
        return $this->mode;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getName($absolute = false)
    {
        return $absolute ? $this->path . '/' . $this->name : $this->name;
    }

    public function __toString()
    {
        return $this->getName();
    }

}
