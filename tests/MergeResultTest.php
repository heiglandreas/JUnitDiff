<?php
/**
 * Copyright (c) Andreas Heigl<andreas@heigl.org>
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
 * @copyright Andreas Heigl
 * @license   http://www.opensource.org/licenses/mit-license.php MIT-License
 * @since     17.06.2016
 * @link      http://github.com/heiglandreas/org.heigl.junitdiff
 */

namespace Org_Heigl\JUnitDiffTest;

use Org_Heigl\JUnitDiff\MergeResult;
use Mockery as M;

class MergeResultTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        M::close();
    }

    public function testThatCreatingWorks()
    {
        $style = M::mock('\Symfony\Component\Console\Style\StyleInterface');
        $mergeResult = new MergeResult($style);

        $this->assertAttributeSame($style, 'style', $mergeResult);
        $this->assertAttributeEquals([], 'content', $mergeResult);
    }

    public function testThatAddingABaseWorks()
    {
        $style = M::mock('\Symfony\Component\Console\Style\StyleInterface');
        $mergeResult = new MergeResult($style);

        $this->assertAttributeEquals([], 'content', $mergeResult);
        $mergeResult->addBase('a', 'b');
        $this->assertAttributeEquals(['a' => ['base' => 'b']], 'content', $mergeResult);
    }

    public function testThatAddingACurrentWorks()
    {
        $style = M::mock('\Symfony\Component\Console\Style\StyleInterface');
        $mergeResult = new MergeResult($style);

        $this->assertAttributeEquals([], 'content', $mergeResult);
        $mergeResult->addCurrent('a', 'b');
        $this->assertAttributeEquals(['a' => ['current' => 'b']], 'content', $mergeResult);
    }

    public function testThatSortingWorks()
    {
        $style       = M::mock('\Symfony\Component\Console\Style\StyleInterface');
        $mergeResult = new MergeResult($style);

        $this->assertAttributeEquals([], 'content', $mergeResult);
        $mergeResult->addCurrent('c', 'b');
        $mergeResult->addCurrent('b', 'b');
        $mergeResult->addCurrent('a', 'b');
        $this->assertAttributeEquals([
            'c' => ['current' => 'b'],
            'b' => ['current' => 'b'],
            'a' => ['current' => 'b'],
        ], 'content', $mergeResult);

        $mergeResult->sort();
        $this->assertAttributeEquals([
            'a' => ['current' => 'b'],
            'b' => ['current' => 'b'],
            'c' => ['current' => 'b'],
        ], 'content', $mergeResult);
    }

    public function testThatCountingTotalsWorks()
    {
        $style       = M::mock('\Symfony\Component\Console\Style\StyleInterface');
        $mergeResult = new MergeResult($style);

        $this->assertAttributeEquals([], 'content', $mergeResult);
        $mergeResult->addCurrent('c', 'b');
        $mergeResult->addCurrent('b', 'b');
        $mergeResult->addCurrent('a', 'b');
        $mergeResult->addBase('c', 'b');
        $mergeResult->addBase('b', 'b');
        $mergeResult->addBase('d', 'b');
        $mergeResult->addBase('e', 'b');

        $this->assertAttributeEquals([
            'c' => ['current' => 'b', 'base' => 'b'],
            'b' => ['current' => 'b', 'base' => 'b'],
            'a' => ['current' => 'b'],
            'd' => ['base' => 'b'],
            'e' => ['base' => 'b'],
        ], 'content', $mergeResult);

        $this->assertEquals(5, $mergeResult->countTotal());
    }

    public function testThatCountingBaseWorks()
    {
        $style       = M::mock('\Symfony\Component\Console\Style\StyleInterface');
        $mergeResult = new MergeResult($style);

        $this->assertAttributeEquals([], 'content', $mergeResult);
        $mergeResult->addCurrent('c', 'b');
        $mergeResult->addCurrent('b', 'b');
        $mergeResult->addCurrent('a', 'b');
        $mergeResult->addBase('c', 'b');
        $mergeResult->addBase('b', 'b');
        $mergeResult->addBase('d', 'b');
        $mergeResult->addBase('e', 'b');

        $this->assertAttributeEquals([
            'c' => ['current' => 'b', 'base' => 'b'],
            'b' => ['current' => 'b', 'base' => 'b'],
            'a' => ['current' => 'b'],
            'd' => ['base' => 'b'],
            'e' => ['base' => 'b'],
        ], 'content', $mergeResult);

        $this->assertEquals(4, $mergeResult->countBase());
    }

    public function testThatCountingCurrentWorks()
    {
        $style       = M::mock('\Symfony\Component\Console\Style\StyleInterface');
        $mergeResult = new MergeResult($style);

        $this->assertAttributeEquals([], 'content', $mergeResult);
        $mergeResult->addCurrent('c', 'b');
        $mergeResult->addCurrent('b', 'b');
        $mergeResult->addCurrent('a', 'b');
        $mergeResult->addBase('c', 'b');
        $mergeResult->addBase('b', 'b');
        $mergeResult->addBase('d', 'b');
        $mergeResult->addBase('e', 'b');

        $this->assertAttributeEquals([
            'c' => ['current' => 'b', 'base' => 'b'],
            'b' => ['current' => 'b', 'base' => 'b'],
            'a' => ['current' => 'b'],
            'd' => ['base' => 'b'],
            'e' => ['base' => 'b'],
        ], 'content', $mergeResult);

        $this->assertEquals(3, $mergeResult->countCurrent());
    }

    public function testThatCountingNewWorks()
    {
        $style = M::mock('\Symfony\Component\Console\Style\StyleInterface');
        $mergeResult = new MergeResult($style);

        $this->assertAttributeEquals([], 'content', $mergeResult);
        $mergeResult->addCurrent('e', 'b');
        $mergeResult->addCurrent('d', 'b');
        $mergeResult->addCurrent('c', 'b');
        $mergeResult->addCurrent('b', 'b');
        $mergeResult->addCurrent('a', 'b');
        $mergeResult->addBase('c', 'a');
        $mergeResult->addBase('b', 'b');

        $this->assertAttributeEquals([
            'c' => ['current' => 'b', 'base' => 'a'],
            'b' => ['current' => 'b', 'base' => 'b'],
            'a' => ['current' => 'b'],
            'd' => ['current' => 'b'],
            'e' => ['current' => 'b'],
        ], 'content', $mergeResult);

        $this->assertEquals(3, $mergeResult->countNew());
    }

    public function testThatCountingRemovedWorks()
    {
        $style = M::mock('\Symfony\Component\Console\Style\StyleInterface');
        $mergeResult = new MergeResult($style);

        $this->assertAttributeEquals([], 'content', $mergeResult);
        $mergeResult->addCurrent('c', 'b');
        $mergeResult->addCurrent('b', 'b');
        $mergeResult->addCurrent('a', 'b');
        $mergeResult->addBase('c', 'a');
        $mergeResult->addBase('b', 'b');
        $mergeResult->addBase('d', 'b');
        $mergeResult->addBase('e', 'b');

        $this->assertAttributeEquals([
            'c' => ['current' => 'b', 'base' => 'a'],
            'b' => ['current' => 'b', 'base' => 'b'],
            'a' => ['current' => 'b'],
            'd' => ['base' => 'b'],
            'e' => ['base' => 'b'],
        ], 'content', $mergeResult);

        $this->assertEquals(2, $mergeResult->countRemoved());
    }

    public function testThatCountingChangedWorks()
    {
        $style = M::mock('\Symfony\Component\Console\Style\StyleInterface');
        $mergeResult = new MergeResult($style);

        $this->assertAttributeEquals([], 'content', $mergeResult);
        $mergeResult->addCurrent('c', 'b');
        $mergeResult->addCurrent('b', 'b');
        $mergeResult->addCurrent('a', 'b');
        $mergeResult->addBase('c', 'a');
        $mergeResult->addBase('b', 'a');
        $mergeResult->addBase('d', 'b');
        $mergeResult->addBase('e', 'b');

        $this->assertAttributeEquals([
            'c' => ['current' => 'b', 'base' => 'a'],
            'b' => ['current' => 'b', 'base' => 'a'],
            'a' => ['current' => 'b'],
            'd' => ['base' => 'b'],
            'e' => ['base' => 'b'],
        ], 'content', $mergeResult);

        $this->assertEquals(2, $mergeResult->countChanged());
    }
}
