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

class OpenerTest extends TestCase
{
    use Opener;

    public static function notFiles()
    {
        return [
            [new \stdClass],
            ['astring'],
            [null],
            [false],
            [1],
            [1.0],
            [['an array']],
        ];
    }

    /**
     * @dataProvider notFiles
     * @expectedException PMG\CsvSugar\Exception\UnexpectedValueException
     */
    public function testOpenerThatDoesNotReturnAValueObjectCausesError($notAFile)
    {
        $this->setFileOpener(function () use ($notAFile) {
            return $notAFile;
        });

        $this->createFileObject(__FILE__, 'r');
    }

    /**
     * @expectedException PMG\CsvSugar\Exception\RuntimeException
     */
    public function testDefaultOpenerThrowsAnRuntimeExceptionWhenTheFileCannotBeOpened()
    {
        $this->setFileOpener(null);
        $this->createFileObject(__DIR__.'/Does/Not/Exist.csv', 'r');
    }
}
