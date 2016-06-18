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

namespace Org_Heigl\JUnitDiff\Writer;

use Org_Heigl\JUnitDiff\MergeResult;
use Symfony\Component\Console\Style\StyleInterface;

class FileSummary implements WriterInterface
{
    protected $style;

    protected $file1;

    protected $file2;

    public function __construct(StyleInterface $style, $file1, $file2)
    {
        $this->style = $style;
        $this->file1 = $file1;
        $this->file2 = $file2;
    }

    public function write(MergeResult $mergeResult)
    {
        $this->style->text(sprintf(
            '<fg=yellow>Analyzed %s tests in total, %s tests in file %s and %s tests in file %s</>',
            $mergeResult->countTotal(),
            $mergeResult->countBase(),
            basename($this->file1),
            $mergeResult->countCurrent(),
            basename($this->file2)
        ));

    }
}
