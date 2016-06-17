<?php

/**
 * Copyright (c) Andreas Heigl<andreas@heigl.org>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @author    Andreas Heigl<andreas@heigl.org>
 * @copyright 2016-2016 Andreas Heigl
 * @license   http://www.opensource.org/licenses/mit-license.php MIT-License
 * @since     17.06.2016
 * @link      http://github.com/heiglandreas/org.heigl.junitdiff
 */

namespace Org_Heigl\JUnitDiffTest;

use Mockery as M;
use Org_Heigl\JUnitDiff\JUnitParser;

class JUnitParserTest extends \PHPUnit_Framework_TestCase
{
    /** @expectedException \UnexpectedValueException */
    public function testParsingAnInvalidFileThrowsException()
    {
        $parser = new JUnitParser();
        $parser->parseFile(__DIR__ . '/_assets/log.empty');
    }

    /** @expectedException \UnexpectedValueException */
    public function testThatParsingAnNonexistendFileThrowsException()
    {
        $parser = new JUnitParser();
        $parser->parseFile(__DIR__ . '/_assets/not_available_at_all');
    }

    public function testThatParsingAJunitFileResultsInAnArray()
    {
        $parser = new JUnitParser();
        $result = $parser->parseFile(__DIR__ . '/_assets/exampleJUnit.xml');

        $expectedResult = [
            'JUnitXmlReporter.constructor::should default path to an empty string' => 'failure',
            'JUnitXmlReporter.constructor::should default consolidate to true' => 'skipped',
            'JUnitXmlReporter.constructor::should default useDotNotation to true' => 'success',
        ];    
            
        $this->assertEquals($expectedResult, $result);

    }

}
