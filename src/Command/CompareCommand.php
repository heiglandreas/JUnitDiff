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
 * @since     14.06.2016
 * @link      http://github.com/heiglandreas/org.heigl.junitdiff
 */

namespace Org_Heigl\JUnitDiff\Command;

use Org_Heigl\JUnitDiff\JUnitMerger;
use Org_Heigl\JUnitDiff\JUnitParser;
use Org_Heigl\JUnitDiff\MergeResult;
use Org_Heigl\JUnitDiff\Style\DiffStyle;
use Org_Heigl\JUnitDiff\Writer\FileSummary;
use Org_Heigl\JUnitDiff\Writer\Legend;
use Org_Heigl\JUnitDiff\Writer\Standard;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Output\OutputInterface;

class CompareCommand extends Command
{
    protected function configure()
    {
        $this->setName('compare')
             ->setDescription('Compare two JUnit log files')
             ->setDefinition(array(
                 new InputArgument('input1', InputArgument::REQUIRED, 'First input file'),
                 new InputArgument('input2', InputArgument::REQUIRED, 'Second input file')
             ))
             ->setHelp('');
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $style = new DiffStyle($input, $output);
        $style->title($this->getApplication()->getLongVersion());


        $parser = new JUnitParser($style);
        $merger = new JUnitMerger(new MergeResult($style));
        try {
            $mergeResult = $merger->merge(
                $parser->parseFile($input->getArgument('input1')),
                $parser->parseFile($input->getArgument('input2'))
            );


        } catch (\Exception $e) {
            $style->error($e->getMessage());
            return;
        }

        if ($output->getVerbosity() >= Output::VERBOSITY_NORMAL) {
            $writer = new Standard($style);
            $writer->write($mergeResult);
        }

        if ($output->getVerbosity() >= Output::VERBOSITY_VERBOSE) {
            $writer = new Legend(
                $style,
                basename($input->getArgument('input1')),
                basename($input->getArgument('input2'))
            );
            $writer->write($mergeResult);
        }

        if ($output->getVerbosity() >= Output::VERBOSITY_QUIET) {
            $writer = new FileSummary(
                $style,
                basename($input->getArgument('input1')),
                basename($input->getArgument('input2'))
            );

            $writer->write($mergeResult);
        }


    }
}
