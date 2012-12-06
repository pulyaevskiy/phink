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
            $this->init();
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
     */
    public function init()
    {
        $this->exec('init');
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
     * @param string $file
     * @return mixed
     */
    public function add($file = '.')
    {
        return $this->exec("add $file");
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
}
