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

class SimpleWriterTest extends TestCase
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
    public function testWriteRowsProducesTheExpectedFile($expectedFile, $dialect)
    {
        $file = tempnam(__DIR__.'/Fixtures/tmp', 'simplewriter_');
        $writer = new SimpleWriter($file, $dialect);

        $writer->writeRows([
            ['one', 'two'],
            ['three', 'four'],
        ]);

        $this->assertFileEquals(__DIR__.'/Fixtures/'.$expectedFile, $file);

        @unlink($file);
    }

    public static function notIterable()
    {
        return [
            [null],
            [false],
            [1],
            [1.0],
            [new \stdClass],
        ];
    }

    /**
     * @dataProvider notIterable
     * @expectedException PMG\CsvSugar\Exception\InvalidArgumentException
     */
    public function testWriteRowsErrorWhenAnNonIterableIsPassedIn($rows)
    {
        $file = tempnam(__DIR__.'/Fixtures/tmp', 'simplewriter_');
        $writer = new SimpleWriter($file);
        try {
            $writer->writeRows($rows);
        } finally {
            @unlink($file);
        }
    }
}
