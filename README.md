[![Build Status](https://travis-ci.org/heiglandreas/JUnitDiff.svg?branch=master)](https://travis-ci.org/heiglandreas/JUnitDiff)

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
