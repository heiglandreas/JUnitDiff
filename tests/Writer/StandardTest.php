<?php
/**
 * Copyright (c) 2016-2016} Andreas Heigl<andreas@heigl.org>
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
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
 * @version   0.0
 * @since     16.06.2016
 * @link      http://github.com/heiglandreas/org.heigl.junitdiff
 */

namespace Org_Heigl\JUnitDiffTest\Writer;

use Mockery as M;
use Org_Heigl\JUnitDiff\Writer\FileSummary;
use Org_Heigl\JUnitDiff\Writer\Legend;
use Org_Heigl\JUnitDiff\Writer\Standard;

class StandardTest extends \PHPUnit_Framework_TestCase
{
    public function testThatSummaryWorks()
    {
        $styleInterface = M::mock('\Symfony\Component\Console\Style\StyleInterface');
        $styleInterface->shouldReceive('text')
            ->with('<bg=red;fg=yellow>-</> a');
        $styleInterface->shouldReceive('text')
            ->with('<bg=green;fg=black>+</> c');
        $styleInterface->shouldReceive('text')
            ->with('<bg=blue;fg=yellow>o</> d changed from <fg=cyan>success</> to <fg=magenta>failure</>');
        $styleInterface->shouldReceive('text')
            ->with('<bg=blue;fg=yellow>o</> e changed from <fg=cyan>failure</> to <fg=magenta>success</>');

        $mergeresult = M::mock('\Org_Heigl\JUnitDiff\MergeResult');
        $mergeresult->shouldReceive('sort');
        $mergeresult->shouldReceive('rewind');
        $mergeresult->shouldReceive('next');
        $mergeresult->shouldReceive('valid')->andReturnValues([true, true, true, true, true, false]);
        $mergeresult->shouldReceive('key')->andReturnValues(['a', 'b', 'c', 'd', 'e']);
        $mergeresult->shouldReceive('current')->andReturnValues([
            ['base'=>'Success'],
            ['base' => 'success', 'current' => 'success'],
            ['current' => 'success'],
            ['base' => 'success', 'current' => 'failure'],
            ['base' => 'failure', 'current' => 'success'],
        ]);

        $fileSummary = new Standard($styleInterface, 'a', 'b');
        $this->assertNull($fileSummary->write($mergeresult));
    }
}
