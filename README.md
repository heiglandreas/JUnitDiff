# JUnitDiff

A small CLI to check which tests have changed

This is early alpha-stage. PRs ans improvement-ideas are more than welcome

## Installation

JUnitDiff is best installed using ```composer```

```bash
$ composer require --dev org_heigl/junitdiff
```

## Usage

JUnitDiff compares two JUnit-Files. They can be generated with f.e. ```phpunit```

```bash
$ phpunit --log-junit logfile.xml
```

When you have two files from two different runs you can check what tests have
changed between those runs by calling this:

```bash
$ junitdiff diff -1 <path to the first logfile> -2 <path to the last logfile>
```

An example output might be:

```bash
Test MyTest::testStoringIdWorks changed from success to error
New Test MyTest::testStoringIdWorksBetter with data set #7
Removed Test MyTest::testStoringIdWorksBest
```

