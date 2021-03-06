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

class DictReaderTest extends TestCase
{
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
    public function testDictReaderTransformsTheFileIntoAnArrayOfAssociativeArrays($file, $dialect)
    {
        $reader = new DictReader(__DIR__.'/Fixtures/'.$file, $dialect);

        $this->assertEquals([
            ['one' => '1', 'two' => '2'],
        ], iterator_to_array($reader));
    }

    public function testCsvFileWithExtraFieldsDiscardsThemWhenNoRestKeyIsSet()
    {
        $reader = DictReader::builder(__DIR__.'/Fixtures/dictreader_extra.csv')
            ->withRestKey(null)
            ->build();

        $this->assertEquals([
            ['one' => '1', 'two' => '2'],
        ], iterator_to_array($reader));
    }

    public function testReaderWithRestKeyPutsExtraValuesIntoRestKeyWhenPresent()
    {
        $reader = DictReader::builder(__DIR__.'/Fixtures/dictreader_extra.csv')
            ->withRestKey('_extra')
            ->build();

        $this->assertEquals([
            ['one' => '1', 'two' => '2', '_extra' => ['3', '4', '5']],
        ], iterator_to_array($reader));
    }

    public function testReaderWithRestKeyAndNoExtraColumnsPutsAnEmptyArrayInRestKey()
    {
        $reader = DictReader::builder(__DIR__.'/Fixtures/dictreader.csv')
            ->withRestKey('_extra')
            ->build();

        $this->assertEquals([
            ['one' => '1', 'two' => '2', '_extra' => []],
        ], iterator_to_array($reader));
    }

    public static function restValues()
    {
        return [
            [null],
            ['missing'],
        ];
    }

    /**
     * @dataProvider restValues
     */
    public function testReaderWithPutsRestValueIntoRowsThatAreTooShort($restValue)
    {
        $reader = DictReader::builder(__DIR__.'/Fixtures/dictreader_missing.csv')
            ->withRestValue($restValue)
            ->build();

        $this->assertEquals([
            ['one' => '1', 'two' => $restValue],
        ], iterator_to_array($reader));
    }

    /**
     * @requires extension zip
     */
    public function testReaderCanReadFilesFromZipStreams()
    {
        $reader = DictReader::builder(sprintf(
            'zip://%s/Fixtures/dictreader.zip#dictreader.csv',
            __DIR__
        ))->build();

        $this->assertEquals([[
            'one' => 1,
            'two' => 2,
        ]], iterator_to_array($reader));
    }

    public function testReaderErrorsWhenTheFileCannotBeOpened()
    {
        $this->expectException(CouldNotOpenFile::class);
        $reader = DictReader::builder(__DIR__.'/does/not/exist')->build();

        foreach ($reader as $row) {
        }
    }

    public function testReaderSkipsEmptyLinesInOutput()
    {
        $reader = DictReader::builder(__DIR__.'/Fixtures/dictreader_with_empties.csv')->build();

        $this->assertEquals([[
            'one' => 1,
            'two' => 2,
        ]], iterator_to_array($reader));
    }

    public function testReaderCanBeUsedMoreThanOnce()
    {
        $reader = DictReader::builder(__DIR__.'/Fixtures/dictreader.csv')->build();

        foreach ($reader as $row) {
            // once
        }

        // twice
        $this->assertEquals([[
            'one' => 1,
            'two' => 2,
        ]], iterator_to_array($reader));
    }
}
