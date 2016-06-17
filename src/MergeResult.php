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
 * @since     16.06.2016
 * @link      http://github.com/heiglandreas/org.heigl.junitdiff
 */

namespace Org_Heigl\JUnitDiff;

use Org_Heigl\IteratorTrait\IteratorTrait;
use Symfony\Component\Console\Style\StyleInterface;

class MergeResult implements \Iterator
{
    use IteratorTrait;

    protected $content = [];

    protected $style;

    public function __construct(StyleInterface $style)
    {
        $this->style = $style;
    }
    public function addBase($test, $result)
    {
        $this->content[$test]['base'] = $result;
    }

    public function addCurrent($test, $result)
    {
        $this->content[$test]['current'] = $result;
    }

    public function sort()
    {
        ksort($this->content);
    }

    /**
     * Get the array the iterator shall iterate over.
     *
     * @return array
     */
    protected function & getIterableElement()
    {
        return $this->content;
    }

    protected function &getIteratorArray()
    {
        return $this->content;
    }

    public function countTotal()
    {
        return count($this->content);
    }

    public function countBase()
    {
        $i = 0;
        foreach ($this->content as $value) {
            if (isset($value['base'])) {
                $i++;
            }
        }

        return $i;
    }

    public function countCurrent()
    {
        $i = 0;
        foreach ($this->content as $value) {
            if (isset($value['current'])) {
                $i++;
            }
        }

        return $i;
    }
}