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

use Org_Heigl\JUnitDiff\JUnitParser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DiffCommand extends Command
{
    protected function configure()
    {
        $this->setName('diff')
             ->setDescription('Shows a diff between two JUnit log files')
             ->setDefinition(array(
                 new InputOption('input1', '1', InputOption::VALUE_REQUIRED, 'First input file'),
                 new InputOption('input2', '2', InputOption::VALUE_REQUIRED, 'Second input file')
             ))
             ->setHelp('');
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            $this->getApplication()->getLongVersion(),
            '',
        ]);

        $parser = new JUnitParser();

        try {
            $file1  = $input->getOption('input1');
            $array1 = $parser->parseFile($file1);
        } catch(\Exception $e) {
            $output->writeln('<bg=red;fg=white>  ' . $e->getMessage() . '  </>');
            return;
        }

        try {
            $file2  = $input->getOption('input2');
            $array2 = $parser->parseFile($file2);
        } catch (\Exception $e) {
            $output->writeln('<bg=red;fg=white> ' . $e->getMessage() . '  </>');
            return;
        }

        $array = $this->merge($array1, $array2);

        foreach ($array as $key => $value) {
            if (! isset($value['base'])) {
                $output->writeln('<bg=green;fg=black>+</> ' . $key);
                continue;
            }
            if (! isset($value['current'])) {
                $output->writeln('<bg=red;fg=yellow>-</> ' . $key);
                continue;
            }

            if ($value['base'] != $value['current']) {
                $output->writeln(sprintf(
                    '<bg=blue;fg=yellow>o</> %s changed from <fg=cyan>%s</> to <fg=magenta>%s</>',
                    $key,
                    $array1[$key],
                    $array2[$key]
                ));
                continue;
            }
        }
        
        $output->writeln('');
        if ($output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
            $output->writeln([
                sprintf(
                    '<bg=green;fg=black>+</>: This test was added in file %s',
                    $file2
                ),
                sprintf(
                    '<bg=red;fg=yellow>-</>: This test was removed in file %s',
                    $file2
                ),
                sprintf(
                    '<bg=blue;fg=yellow>o</>: The test-result changed between file %s and %s',
                    $file1,
                    $file2
                ),
                ''
            ]);
        }
        $output->writeln(sprintf(
            '<fg=yellow>Analyzed %s tests in total, %s tests in file %s and %s tests in file %s',
            count($array),
            count($array1),
            basename($file1),
            count($array2),
            basename($file2)
        ));
    }

    protected function merge(array $array1, array $array2)
    {
        $merged = [];
        foreach ($array1 as $key => $value) {
            $merged[$key]['base'] = $value;
        }

        foreach($array2 as $key => $value) {
            $merged[$key]['current'] = $value;
        }

        ksort($merged);

        return $merged;
    }
}
