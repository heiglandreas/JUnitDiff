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

class JUnitMerger
{
    /** @var  MergeResult */
    protected $mergeResult;

    public function __construct(MergeResult $mergeResult)
    {
        $this->mergeResult = $mergeResult;
    }

    /**
     * @param array $array1
     * @param array $array2
     *
     * @return array
     */
    public function merge(array $array1, array $array2)
    {
        foreach ($array1 as $key => $value) {
            $this->mergeResult->addBase($key, $value);
        }

        foreach ($array2 as $key => $value) {
            $this->mergeResult->addCurrent($key, $value);
        }

        return $this->mergeResult;
    }
}
