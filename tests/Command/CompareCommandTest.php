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
 * @since     15.06.2016
 * @link      http://github.com/heiglandreas/org.heigl.junitdiff
 */

namespace Org_Heigl\JUnitDiffTest\Command;

use Org_Heigl\JUnitDiff\Command\CompareCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Console\Output\OutputInterface;

class CompareCommandTest extends TestCase
{

    public function testExecute()
    {
        // mock the Kernel or create one depending on your needs
        $application = new Application();
        $application->add(new CompareCommand());

        $command = $application->find('compare');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'base'    => __DIR__ . '/../_assets/log1.xml',
            'current' => __DIR__ . '/../_assets/log.xml',
        ]);

        $this->assertEquals('
Console Tool
============

 - Org_Heigl_HyphenatorTest::testHyphenateEntweder
 + Org_Heigl_HyphenatorTest::testHyphenatorSingletonReturnsHyphenatorObject
 o PdfAnnotationsModelTest::testStoringIdWorks changed from success to error
 + Wdv_Acl_DbTest::testSettingDefaultModelWithInstance
 - Wdv_Filter_HyphenCleanerTest::testHyphenCleanerFilter with data set #2
 Analyzed 615 tests in total, 613 tests in file log1.xml and 613 tests in file log.xml
 Added: 2 , Removed: 2 , Changed: 1 
', $commandTester->getDisplay());
    }

    public function testExecuteQuietly()
    {
        // mock the Kernel or create one depending on your needs
        $application = new Application();
        $application->add(new CompareCommand());

        $command = $application->find('compare');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'base'    => __DIR__ . '/../_assets/log1.xml',
            'current' => __DIR__ . '/../_assets/log.xml',
        ], [
            'verbosity' => OutputInterface::VERBOSITY_QUIET
        ]);

        $this->assertEquals('
 Added: 2 , Removed: 2 , Changed: 1 
', $commandTester->getDisplay());
    }

    public function testThatNonExistingFilesRaiseError()
    {
        $application = new Application();
        $application->add(new CompareCommand());

        $command = $application->find('compare');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'base'    => __DIR__ . '/../_assets/log1.xml',
            'current' => __DIR__ . '/../_assets/log.xm',
        ]);

        $this->assertContains('[ERROR] File log.xm is not readable', $commandTester->getDisplay());
    }

    public function testThatInvalidFileRaisesError()
    {
        $application = new Application();
        $application->add(new CompareCommand());

        $command = $application->find('compare');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'base'    => __DIR__ . '/../_assets/log1.xml',
            'current' => __DIR__ . '/../_assets/log.empty',
        ]);

        $this->assertContains('[ERROR] File log.empty seems not to be a JUnit-File', $commandTester->getDisplay());
    }


    public function testThatNoFileRaisesError()
    {
        $application = new Application();
        $application->add(new CompareCommand());

        $command = $application->find('compare');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['base' => '', 'current' => '']);

        $this->assertContains('[ERROR] File  is not readable', $commandTester->getDisplay());
    }
}
