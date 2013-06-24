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

use Phink\Command\AddCommand;
use Phink\Command\CheckoutCommand;
use Phink\Command\InitCommand;
use Phink\Command\PullCommand;
use Phink\Command\StatusCommand;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Repository is a wrapper around Git repository.
 *
 * @author Anatoly Pulyaevsky <anatoly@abstractionpower.net>
 *
 * @api
 */
class Repository extends AbstractGitExecutor
{

    public function __construct($cwd, $init = false)
    {
        parent::__construct($cwd);
        if ($init) {
            $this->init()->execute();
        }
    }

    /**
     * Checks if Git repository exists in specified $path.
     *
     * @param string $path
     *
     * @return bool
     */
    public static function exists($path)
    {
        return (file_exists($path) && file_exists($path . '/.git/HEAD') || file_exists($path . '/HEAD'));
    }

    /**
     * Initializes Git repository.
     *
     * @return InitCommand
     */
    public function init()
    {
        // Do not allow init commands for not existing directories.
        $fs = new Filesystem();
        if (!$fs->exists($this->getCwd())) {
            throw new Exception("Directory '{$this->cwd}' doesn't exists.");
        }
        return new InitCommand($this->getCwd());
    }

    /**
     * Clones existing Git repository into current dir.
     *
     * @param string $url
     */
    public function cloneExisting($url)
    {
        $this->exec("clone $url ./");
    }

    /**
     * Returns true if there any local changes in files exists.
     */
    public function isDirty()
    {
        return trim($this->exec('status --porcelain')) == true;
    }


    /**
     * Stages changed files.
     *
     * @return AddCommand
     */
    public function add()
    {
        return new AddCommand($this->getCwd());
    }

    /**
     * @return StatusCommand
     */
    public function status()
    {
        return new StatusCommand($this->getCwd());
    }

    /**
     * Returns list of unstaged changes in repository.
     *
     * @return array
     */
    public function getUnstagedChanges()
    {
        $output = $this->exec('status --porcelain');
        preg_match_all('/^\?\?\s.*$/m', $output, $matches);
        $list = array_filter(array_map('trim', preg_replace('/\?\?\s/', '', $matches[0])));
        return $list;
    }

    /**
     * Returns list of staged changes in repository.
     *
     * @return array
     */
    public function getStagedChanges()
    {
        $output = $this->exec('status --porcelain');
        preg_match_all('/^[\sMADRCU][\sMDUA]\s.*$/m', $output, $matches);
        $list = array_filter(array_map('trim', preg_replace('/[\sMADRCU][\sMDUA]\s/', '', $matches[0])));
        return $list;
    }

    /**
     * Commits staged changes.
     *
     * @param string $message
     */
    public function commit($message)
    {
        $this->exec("commit -m \"$message\"");
    }

    /**
     * Returns instance of Checkout command.
     *
     * @return CheckoutCommand
     */
    public function checkout()
    {
        return new CheckoutCommand($this->getCwd());
    }

    /**
     * Returns instance of Pull command associated with this repository which must be used to perform actual 'git pull'.
     *
     * @return PullCommand
     */
    public function pull()
    {
        return new PullCommand($this->getCwd());
    }
}
