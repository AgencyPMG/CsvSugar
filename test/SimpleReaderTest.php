<?php
/*
 * This file is part of pmg/csv-sugar.
 *
 * Copyright (c) PMG <https://www.pmg.com>.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PMG\CsvSugar;

use PMG\CsvSugar\Exception\CouldNotOpenFile;

class SimpleReaderTest extends TestCase
{
    public static function dialects()
    {
        return [
            ['simple.csv', Dialect::csv()],
            ['simple.tsv', Dialect::tsv()],
            ['simple.psv', Dialect::pipe()],
            ['simple.tilde', Dialect::tilde()],
        ];
    }

    /**
     * @dataProvider dialects
     */
    public function testFileReaderTurnsTheFileIntoExpectedValues($filename, $dialect)
    {
        $reader = new SimpleReader(__DIR__.'/Fixtures/'.$filename, $dialect);

        $rows = iterator_to_array($reader);

        $this->assertEquals([
            ['one', 'two'],
            ['three', 'four'],
        ], $rows);
    }

    public function testEmptyLinesAreNotIncludedInTheOutput()
    {
        $reader = new SimpleReader(__DIR__.'/Fixtures/simple_with_empties.csv', Dialect::csv());

        $rows = iterator_to_array($reader);

        $this->assertEquals([
            ['one', 'two'],
            ['three', 'four'],
        ], $rows);
    }

    public function testReaderErrorsWhenTheFileCannotBeOpened()
    {
        $this->expectException(CouldNotOpenFile::class);
        $reader = new SimpleReader(__DIR__.'/does/not/exist');

        foreach ($reader as $row) {
        }
    }
}
