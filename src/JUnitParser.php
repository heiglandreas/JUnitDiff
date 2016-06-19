<?php

/**
 * Copyright (c) Andreas Heigl<andreas@heigl.org>
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

class JUnitParser
{
    public function __construct()
    {
        libxml_use_internal_errors(true);
    }

    public function getLastLibXmlError()
    {
        $error = libxml_get_last_error();
        if (! $error instanceof \LibXMLError) {
            return false;
        }

        libxml_clear_errors();
        return $error;
    }

    /**
     * @param string $filename
     *
     * @return array
     */
    public function parseFile($filename)
    {
        if (! is_readable($filename)) {
            throw new \UnexpectedValueException(sprintf(
                'File %s is not readable',
                basename($filename)
            ));
        }
        $dom = new \DOMDocument(1.0, 'UTF-8');
        $dom->load($filename);

        if ($this->getLastLibXmlError()) {
            throw new \UnexpectedValueException(sprintf(
                'File %s seems not to be a JUnit-File',
                basename($filename)
            ));
        }

        $xpath = new \DOMXPath($dom);

        $result = [];

        $items = $xpath->query('//testcase');
        foreach ($items as $item) {

            $class = $item->getAttribute('class');
            if (! $class) {
                $class = explode('::', $item->parentNode->getAttribute('name'))[0];
            }

            $type = 'success';

            foreach ($item->childNodes as $child) {
                if ($child->nodeType != XML_ELEMENT_NODE) {
                    continue;
                }
                $type = $child->nodeName;
            }

            $result[$class . '::' . $item->getAttribute('name')] = $type;

        }

        return $result;
    }
}
