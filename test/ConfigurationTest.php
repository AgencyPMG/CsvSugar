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

class ConfigurationTest extends TestCase
{
    use Configuration;

    public function testConfigureFileObjectChangesTheCsvControlForTheFileObjectBaseOnDialect()
    {
        $dialect = new Dialect('|', "'");
        $fh = new \SplFileObject(__FILE__, 'r');
        self::configureFileObject($fh, $dialect);

        list($delimiter, $enclosure) = $fh->getCsvControl();
        $this->assertEquals('|', $delimiter);
        $this->assertEquals("'", $enclosure);
    }
}
