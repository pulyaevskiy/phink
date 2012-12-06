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
 * Branch is a wrapper around Git branches.
 *
 * @author Anatoly Pulyaevsky <anatoly@abstractionpower.net>
 *
 * @api
 */
class Branch extends AbstractGitExecutor
{

    public function create($name)
    {
        // If branch with $name already exist - Git will throw an error.
        // No need for additional check here.
        return $this->exec("branch $name");
    }

    public function getCurrent()
    {
        $list = explode(PHP_EOL, $this->exec('branch'));
        foreach ($list as $branch) {
            if ($branch == '* (no branch)') {
                return null;
            }
            if ($branch[0] == '*') {
                return substr($branch, 2);
            }
        }
        // We can't get to this line in normal case.
        throw new Exception('Branch not found');
    }

    public function setCurrent($name)
    {
        $command = "checkout $name";
        // If there is no branch with $name - Git will throw an error, so it will be handled in exec().
        // No need for additional check here.
        $this->exec($command);
    }

    public function getList()
    {
        $output = $this->exec('branch');
        return array_filter(
                preg_replace('/[\*\s]/', '', array_map('trim', explode(PHP_EOL, $output)))
            );
    }
}
