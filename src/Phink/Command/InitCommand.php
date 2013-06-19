<?php

/*
 * This file is part of the Phink library.
 *
 * (c) Anatoly Pulyaevsky <anatoly@abstractionpower.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phink\Command;

/**
 * InitCommand creates an empty git repository or re-initializes an existing one.
 */
class InitCommand extends AbstractCommand {

    /** @var bool Only print error and warning messages, all other output will be suppressed. */
    private $quiet = false;

    /** @var bool Create a bare repository. */
    private $bare = false;

    /** @var string The directory from which templates will be used. */
    private $templateDirectory;

    /** @var string Optionally specifies directory where to initialize new repository. */
    private $directory;

    public function getCommandString()
    {
        $command = 'init';

        return $command;
    }

    public function quiet()
    {
        $this->quiet = true;
        return $this;
    }

    public function bare()
    {
        $this->bare = true;
        return $this;
    }

    public function template($templateDirectory)
    {
        $this->templateDirectory = $templateDirectory;
        return $this;
    }

    public function directory($directory)
    {
        $this->directory = $directory;
        return $this;
    }

}
