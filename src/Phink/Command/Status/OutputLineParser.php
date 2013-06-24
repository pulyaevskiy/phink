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

/**
 * Parses output of the 'git status' command in 'porcelain' format.
 */
class OutputLineParser {

    const PARSE_REGEX = '/^([\sMADRCU\?\!][\sMADRCU\?\!])\s([^\s]*)(\s->\s)?(.*)?$/';

    public static function parse($dataString)
    {
        preg_match(OutputLineParser::PARSE_REGEX, $dataString, $matches);
        $result = array(
            'status' => $matches[1],
            'path' => $matches[4] ? $matches[4] : $matches[2],
            'oldPath' => $matches[4] ? $matches[2] : '',
        );

        return $result;
    }

}
