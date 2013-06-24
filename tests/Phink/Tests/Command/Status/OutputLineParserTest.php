<?php

/*
 * This file is part of the Phink library.
 *
 * (c) Anatoly Pulyaevsky <anatoly@abstractionpower.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phink\Tests\Command\Status;


use Phink\Command\Status\OutputLineParser;

class OutputLineParserTest extends \PHPUnit_Framework_TestCase {

    /**
     * @dataProvider regexDataProvider
     */
    public function testParseRegex($dataString, $status, $path, $oldPath)
    {
        $actual = OutputLineParser::parse($dataString);
        $this->assertEquals($status, $actual['status']);
        $this->assertEquals($path, $actual['path']);
        $this->assertEquals($oldPath, $actual['oldPath']);
    }

    public static function regexDataProvider()
    {
        $data = array(
            array('R  bin/library -> bin/library.sh', 'R ', 'bin/library.sh', 'bin/library'),
            array('M  src/Library/Library.php', 'M ', 'src/Library/Library.php', ''),
            array(' D bin/library', ' D', 'bin/library', ''),
            array(' M src/Library/Application.php', ' M', 'src/Library/Application.php', ''),
            array('?? bin/library.sh', '??', 'bin/library.sh', ''),
            array('!! bin/library.sh', '!!', 'bin/library.sh', ''),
        );
        return $data;
    }

}
