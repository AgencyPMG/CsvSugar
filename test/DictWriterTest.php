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
use PMG\CsvSugar\Exception\InvalidKeys;
use PMG\CsvSugar\Exception\InvalidArgumentException;

class DictWriterTest extends TestCase
{
    private $fs;

    public static function dialects()
    {
        return [
            ['dictreader.csv', Dialect::csv()],
            ['dictreader.tsv', Dialect::tsv()],
            ['dictreader.psv', Dialect::pipe()],
            ['dictreader.tilde', Dialect::tilde()],
        ];
    }

    /**
     * @dataProvider dialects
     */
    public function testWriteRowsProducesTheExpectedFiles($expectedFile, Dialect $dialect)
    {
        $file = vfsStream::url('home/dictwriter');
        $writer = DictWriter::builder($file)
            ->withFields(['one', 'two'])
            ->withDialect($dialect)
            ->withErrorBehavior(DictWriter::IGNORE_INVALID)
            ->withRestValue('')
            ->build();

        $writer->writeHeader();
        $writer->writeRows([
            [
                'one' => 1,
                'two' => 2,
                'ignored' => 'yep',
            ],
        ]);

        $this->assertFileEquals(__DIR__.'/Fixtures/'.$expectedFile, $file);
    }

    public function testWriteRowErrorsWhenAnInvalidKeyIsPassedInWithErrorInvalidTurnedOn()
    {
        $this->expectException(InvalidKeys::class);

        $file = vfsStream::url('home/dictwriter');
        $writer = DictWriter::builder($file)
            ->withFields(['one', 'two'])
            ->withErrorBehavior(DictWriter::ERROR_INVALID)
            ->build();

        $writer->writeRow([
            'one' => 1,
            'two' => 2,
            'ignored' => 'yep',
        ]);
    }

    public function testMissingValuesAreFilledInWithTheRestValue()
    {
        $file = vfsStream::url('home/dictwriter');
        $writer = DictWriter::builder($file)
            ->withFields(['one'])
            ->withRestValue('missing')
            ->build();

        $writer->writeRow([]);

        $this->assertEquals("missing\n", file_get_contents($file));
    }

    public static function badRows()
    {
        return [
            [true],
            [1],
            [null],
            ['invalid'],
            [new \stdClass],
        ];
    }

    /**
     * @dataProvider badRows
     */
    public function testWriteRowErrorsWhenGivenANonArrayOrAccessAccess($row)
    {
        $this->expectException(InvalidArgumentException::class);

        $file = vfsStream::url('home/dictwriter');
        $writer = new DictWriter($file, null, ['one']);

        $writer->writeRow($row);
    }

    public static function validRows()
    {
        return [
            'array' => [
                ['one' => 1],
            ],
            'ArrayAccess' => [
                new \ArrayObject(['one' => 1]),
            ],
        ];
    }

    /**
     * @dataProvider validRows
     */
    public function testWriteRowCanAcceptAnArrayOrArrayAccess($row)
    {
        $file = vfsStream::url('home/dictwriter');
        $writer = new DictWriter($file, null, ['one']);

        $writer->writeRow($row);

        $this->assertEquals("1\n", file_get_contents($file));
    }

    protected function setUp() : void
    {
        $this->fs = vfsStream::setup('home');
    }
}
