[![Build Status](https://travis-ci.org/heiglandreas/JUnitDiff.svg?branch=master)](https://travis-ci.org/heiglandreas/JUnitDiff)
[![Code Climate](https://codeclimate.com/github/heiglandreas/JUnitDiff/badges/gpa.svg)](https://codeclimate.com/github/heiglandreas/JUnitDiff)
[![Coverage Status](https://coveralls.io/repos/github/heiglandreas/JUnitDiff/badge.svg?branch=master)](https://coveralls.io/github/heiglandreas/JUnitDiff?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/heiglandreas/JUnitDiff/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/heiglandreas/JUnitDiff/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/46c6fa0c-fcfd-468f-82e1-2c23256e1cd6/mini.png)](https://insight.sensiolabs.com/projects/46c6fa0c-fcfd-468f-82e1-2c23256e1cd6)

[![Latest Stable Version](https://poser.pugx.org/org_heigl/junitdiff/v/stable)](https://packagist.org/packages/org_heigl/junitdiff)
[![Total Downloads](https://poser.pugx.org/org_heigl/junitdiff/downloads)](https://packagist.org/packages/org_heigl/junitdiff)
[![License](https://poser.pugx.org/org_heigl/junitdiff/license)](https://packagist.org/packages/org_heigl/junitdiff)
[![composer.lock](https://poser.pugx.org/org_heigl/junitdiff/composerlock)](https://packagist.org/packages/org_heigl/junitdiff)

# JUnitDiff

A small CLI to check which tests have changed between test runs.

This software is in an early alpha-stage. PRs and improvement-ideas are more than welcome.

## Installation

JUnitDiff is best installed globally using `composer`.

```bash
$ composer global require --dev org_heigl/junitdiff
```

## Usage

JUnitDiff compares two JUnit log files which can be generated with e.g. `phpunit`.

```bash
$ phpunit --log-junit logfile.xml
```

When you have two JUnit log files from two different test runs available you can check which tests have changed between those with `junitdiff`.

```bash
$ junitdiff compare </path/to/first/logfile> </path/to/last/logfile>
```

An example output might be:

```bash
[o] Test MyTest::testStoringIdWorks changed from success to error
[+] New Test MyTest::testStoringIdWorksBetter with data set #7
[-] Removed Test MyTest::testStoringIdWorksBest
```

## Caveat

Currently the JUnit-log file does not provide information (Or I was too stubborn to find it)
about skipped or ignored tests. They will **not** be shown in the output! But as they are
ignored or skipped and therefore not executed they are just like not existing tests at
all, so it shouldn't be an issue.
