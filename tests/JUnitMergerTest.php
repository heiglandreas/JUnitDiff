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
 * @copyright Andreas Heigl
 * @license   http://www.opensource.org/licenses/mit-license.php MIT-License
 * @since     17.06.2016
 * @link      http://github.com/heiglandreas/org.heigl.junitdiff
 */

namespace Org_Heigl\JUnitDiffTest;

use Org_Heigl\JUnitDiff\JUnitMerger;
use Mockery as M;
use PHPUnit\Framework\TestCase;

class JUnitMergerTest extends TestCase
{
    public function tearDown() : void
    {
        M::close();
    }

    public function testThatSettingMergerWorks()
    {
        $mergeresult = M::mock('\Org_Heigl\JUnitDiff\MergeResult');

        $merger = new JUnitMerger($mergeresult);

        $this->assertAttributeSame($mergeresult, 'mergeResult', $merger);
    }

    public function testThatMergingTwoArraysWorks()
    {
        $mergeresult = M::mock('\Org_Heigl\JUnitDiff\MergeResult');
        $mergeresult->shouldReceive('addBase')->withArgs(['a', 'a']);
        $mergeresult->shouldReceive('addBase')->withArgs(['b', 'b']);
        $mergeresult->shouldReceive('addBase')->withArgs(['d', 'd']);
        $mergeresult->shouldReceive('addCurrent')->withArgs(['a', 'a']);
        $mergeresult->shouldReceive('addCurrent')->withArgs(['c', 'c']);
        $mergeresult->shouldReceive('addCurrent')->withArgs(['d', 'e']);

        $array1 = [
            'a' => 'a',
            'b' => 'b',
            'd' => 'd',
        ];

        $array2 = [
            'a' => 'a',
            'c' => 'c',
            'd' => 'e',
        ];

        $merger = new JUnitMerger($mergeresult);
        $this->assertSame($mergeresult, $merger->merge($array1, $array2));
    }
}
