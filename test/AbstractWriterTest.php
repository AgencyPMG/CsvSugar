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

class AbstractWriterTest extends TestCase
{
    private $writer;

    public static function iterable()
    {
        return [
            'array' => [
                [['row']],
            ],
            'traversable' => [
                new \ArrayIterator([['row']]),
            ],
        ];
    }

    /**
     * @dataProvider iterable
     */
    public function testWriteRowsPassesRowsOffToWriteRowWhenGivenAnIterable($rows)
    {
        $this->writer->expects($this->once())
            ->method('writeRow')
            ->with(['row']);
        $this->writer->writeRows($rows);
    }

    protected function setUp()
    {
        $this->writer = $this->getMockForAbstractClass(AbstractWriter::class);
    }
}
