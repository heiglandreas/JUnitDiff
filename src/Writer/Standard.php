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
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Style\StyleInterface;

class Standard implements WriterInterface
{
    protected $style;

    private $verbosity;

    public function __construct(StyleInterface $style, $verbosity = Output::VERBOSITY_NORMAL)
    {
        $this->style = $style;
        $this->verbosity = $verbosity;
    }

    public function write(MergeResult $mergeResult)
    {
        $mergeResult->sort();

        foreach ($mergeResult as $key => $value) {
            if (! isset($value['base'])) {
                $this->writeAddedTest($key);
                continue;
            }
            if (! isset($value['current'])) {
                $this->writeRemovedTest($key);
                continue;
            }

            if ($value['base']['result'] != $value['current']['result']) {
                $this->writeChangedTest($value['base'], $value['current']);
                continue;
            }
        }
    }

    /**
     * @param string $key
     *
     * @return void;
     */
    private function writeAddedTest($key)
    {
        $this->style->text('<bg=green;fg=black>+</> ' . $key);
    }

    /**
     * @param string $key
     *
     * @return void
     */
    private function writeRemovedTest($key)
    {
        $this->style->text('<bg=red;fg=yellow>-</> ' . $key);
    }

    /**
     * @param array $base
     * @param array $current
     * @param string $key
     *
     * @return void
     */
    private function writeChangedTest($base, $current, $key)
    {
        $this->style->text(sprintf(
            '<bg=blue;fg=yellow>o</> %s changed from <fg=cyan>%s</> to <fg=magenta>%s</>',
            $key,
            $base['result'],
            $current['result']
        ));

        if ($base['result'] === 'success') {
            $this->addVerboseInformationToChangedTest($current);
            $this->addVeryVerboseInformationToChangedTest($current);
        }

    }

    private function addVerboseInformationToChangedTest($current)
    {
        if (! $current['message']) {
            return;
        }

        if ($this->verbosity < Output::VERBOSITY_VERBOSE) {
            return;
        }

        $this->style->text(sprintf(
            "\t<fg=yellow>%s</>: <fg=green>%s</>",
            $current['type'],
            $current['message']
        ));
    }

    private function addVeryVerboseInformationToChangedTest($current)
    {
        if (! $current['info']) {
            return;
        }

        if ($this->verbosity < Output::VERBOSITY_VERY_VERBOSE) {
            return;
        }

        $this->style->text(sprintf(
            "\t<fg=cyan>%s</>",
            $current['info']
        ));
    }
}
