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
            'JUnitXmlReporter.constructor::Errored and will stay errored' => [
                'result' => 'error',
                'message' => 'value of getMessage',
                'type' => 'Exception',
                'info' => 'This might be a stack-trace',
            ],
            'JUnitXmlReporter.constructor::Errored and will fail' => [
                'result' => 'error',
                'message' => 'value of getMessage',
                'type' => 'Exception',
                'info' => 'This might be a stack-trace',
            ],
            'JUnitXmlReporter.constructor::Errored and will be skipped' => [
                'result' => 'error',
                'message' => 'value of getMessage',
                'type' => 'Exception',
                'info' => 'This might be a stack-trace',
            ],
            'JUnitXmlReporter.constructor::Errored and will be restored' => [
                'result' => 'error',
                'message' => 'value of getMessage',
                'type' => 'Exception',
                'info' => 'This might be a stack-trace',
            ],
            'JUnitXmlReporter.constructor::Failed and will error' => [
                'result' => 'failure',
                'message' => 'value of getMessage',
                'type' => 'assertEquals',
                'info' => 'This might be a stack-trace',
            ],
            'JUnitXmlReporter.constructor::Failed and will stay failed' => [
                'result' => 'failure',
                'message' => 'value of getMessage',
                'type' => 'assertEquals',
                'info' => 'This might be a stack-trace',
            ],
            'JUnitXmlReporter.constructor::Failed and will be skipped' => [
                'result' => 'failure',
                'message' => 'value of getMessage',
                'type' => 'assertEquals',
                'info' => 'This might be a stack-trace',
            ],
            'JUnitXmlReporter.constructor::Failed and will be restored' => [
                'result' => 'failure',
                'message' => 'value of getMessage',
                'type' => 'assertEquals',
                'info' => 'This might be a stack-trace',
            ],
            'JUnitXmlReporter.constructor::Skipped and will error' => [
                'result' => 'skipped',
                'message' => '',
                'type' => '',
                'info' => ''
            ],
            'JUnitXmlReporter.constructor::Skipped and will fail' => [
                'result' => 'skipped',
                'message' => '',
                'type' => '',
                'info' => ''
            ],
            'JUnitXmlReporter.constructor::Skipped and will stay skipped' => [
                'result' => 'skipped',
                'message' => '',
                'type' => '',
                'info' => ''
            ],
            'JUnitXmlReporter.constructor::Skipped and will be restored' => [
                'result' => 'skipped',
                'message' => '',
                'type' => '',
                'info' => ''
            ],
            'JUnitXmlReporter.constructor::Success and will error' => [
                'result' => 'success',
                'message' => '',
                'type' => '',
                'info' => ''
            ],
            'JUnitXmlReporter.constructor::Success and will fail' => [
                'result' => 'success',
                'message' => '',
                'type' => '',
                'info' => ''
            ],
            'JUnitXmlReporter.constructor::Success and will be skipped' => [
                'result' => 'success',
                'message' => '',
                'type' => '',
                'info' => ''
            ],
            'JUnitXmlReporter.constructor::Success and will stay success' => [
                'result' => 'success',
                'message' => '',
                'type' => '',
                'info' => ''
            ],
        ];
            
        $this->assertEquals($expectedResult, $result);

    }

}
