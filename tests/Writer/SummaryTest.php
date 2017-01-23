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
 * @since     16.06.2016
 * @link      http://github.com/heiglandreas/org.heigl.junitdiff
 */

namespace Org_Heigl\JUnitDiffTest\Writer;

use Mockery as M;
use Org_Heigl\JUnitDiff\Writer\Quiet;
use Org_Heigl\JUnitDiff\Writer\Summary;

class SummaryTest extends \PHPUnit_Framework_TestCase
{
    public function testThatQuietSummaryWorks()
    {
        $styleInterface = M::mock('\Symfony\Component\Console\Style\StyleInterface');
        $styleInterface->shouldReceive('writeQuiet')
            ->with('Added:<bg=green;fg=black> 3 </>, Removed:<bg=red;fg=yellow> 5 </>, Changed:<bg=blue;fg=yellow> 7 </>');
        $mergeresult = M::mock('\Org_Heigl\JUnitDiff\MergeResult');
        $mergeresult->shouldReceive('countNew')->andReturn(3);
        $mergeresult->shouldReceive('countRemoved')->andReturn(5);
        $mergeresult->shouldReceive('countChanged')->andReturn(7);

        $quiet = new Summary($styleInterface, 'a', 'b');
        $this->assertNull($quiet->write($mergeresult));
    }
}
