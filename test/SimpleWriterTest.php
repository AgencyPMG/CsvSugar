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

use org\bovigo\vfs\vfsStream;

class SimpleWriterTest extends TestCase
{
    private $fs;

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
        $file = vfsStream::url('home/simplewriter');
        $writer = new SimpleWriter($file, $dialect);

        $writer->writeRows([
            ['one', 'two'],
            ['three', 'four'],
        ]);

        $this->assertFileEquals(__DIR__.'/Fixtures/'.$expectedFile, $file);
    }

    public function testNonArrayRowsAreConvertedToArraysAndWritten()
    {
        $file = vfsStream::url('home/simplewriter_nonarray');
        $writer = new SimpleWriter($file);

        $writer->writeRow('one');

        $this->assertEquals("one\n", file_get_contents($file));
    }

    public function rows()
    {
        return [
            'array' => [['one', 'two']],
            'traversable' => [new \ArrayIterator(['one', 'two'])],
        ];
    }

    /**
     * @dataProvider rows
     */
    public function testWriteRowAcceptsAnIteratorOrAnArray($row)
    {
        $file = vfsStream::url('home/simplewriter_writerow');
        $writer = new SimpleWriter($file, Dialect::csv());

        $writer->writeRow($row);

        $this->assertEquals("one,two\n", file_get_contents($file));
    }

    protected function setUp()
    {
        $this->fs = vfsStream::setup('home');
    }
}
