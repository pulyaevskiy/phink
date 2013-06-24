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


use Phink\Command\Status\Entry;
use Phink\Command\Status\EntryCollection;
use Phink\Command\Status\OutputLineParser;

class StatusCommand extends AbstractCommand {

    /** @var bool */
    private $showIgnored = false;

    /** @var string */
    public $ignoreSubmodules;

    public function getCommandString()
    {
        $command = 'status --porcelain';

        if ($this->showIgnored) {
            $command .= " --ignored";
        }

        if (is_string($this->ignoreSubmodules)) {
            $command .= " --ignore-submodules";
        }

        return $command;
    }

    /**
     * Show ignored files as well.
     */
    public function showIgnored()
    {
        $this->showIgnored = true;
        return $this;
    }

    public function ignoreSubmodules($when)
    {
        if (!in_array($when, array('none', 'untracked', 'dirty', 'all'))) {
            throw new \InvalidArgumentException('Invalid argument for StatusCommand::ignoreSubmodules() provided.');
        }
        $this->ignoreSubmodules = $when;
        return $this;
    }

    /**
     * @return EntryCollection
     */
    public function execute()
    {
        $collection = new EntryCollection();
        $output = parent::execute();

        if (trim($output)) {
            $lines = explode(PHP_EOL, $output);
            foreach ($lines as $line) {
                $values = OutputLineParser::parse($line);
                $collection->add(Entry::createWithInitialValues($values));
            }
        }

        return $collection;
    }

}
