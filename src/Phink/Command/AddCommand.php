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


use Phink\Command\Exception\BadOptionsException;

class AddCommand extends AbstractCommand {

    /** @var bool */
    private $dryRun = false;

    /** @var bool */
    private $force = false;

    /** @var bool */
    private $update = false;

    /** @var bool */
    private $all = false;

    /** @var string */
    private $filePattern;

    public function getCommandString()
    {
        $this->validateOptions();

        $command = 'add';

        if ($this->dryRun) {
            $command .= ' --dry-run';
        }

        if ($this->force) {
            $command .= ' --force';
        }

        if ($this->update) {
            $command .= ' --update';
        }

        if ($this->all) {
            $command .= ' --all';
        }

        if ($this->filePattern) {
            $command .= " $this->filePattern";
        }

        return $command;
    }

    protected function validateOptions()
    {
        if ($this->update && $this->all) {
            throw new BadOptionsException("Both --update and --all options provided for 'git add'. Can be used only one.");
        }
    }

    /**
     * Don’t actually add the file(s), just show if they exist and/or will be ignored.
     */
    public function dryRun()
    {
        $this->dryRun = true;
        return $this;
    }

    /**
     * Allow adding otherwise ignored files.
     */
    public function force()
    {
        $this->force = true;
        return $this;
    }

    /**
     * Only match $this->filePattern against already tracked files in the index rather than the working tree.
     *
     * That means that it will never stage new files, but that it will stage modified new contents of tracked files and
     * that it will remove files from the index if the corresponding files in the working tree have been removed.
     */
    public function update()
    {
        $this->update = true;
        return $this;
    }

    /**
     * Like update(), but match $this->filePattern against files in the working tree in addition to the index.
     *
     * That means that it will find new files as well as staging modified content and removing files that are no longer
     * in the working tree.
     */
    public function all()
    {
        $this->all = true;
        return $this;
    }

    /**
     * Files to add content from.
     *
     * Fileglobs (e.g. *.c) can be given to add all matching files.
     * Also a leading directory name (e.g. dir to add dir/file1 and dir/file2) can be given to add all files
     * in the directory, recursively.
     */
    public function filePattern($pattern)
    {
        $this->filePattern = $pattern;
        return $this;
    }
}
