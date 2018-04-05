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

/**
 * Read a CSV file into associative arrays.
 *
 * @since 1.0
 */
final class DictReader extends AbstractReader
{
    private $fields;
    private $restKey;
    private $restValue;

    public function __construct($filename, Dialect $dialect=null, array $fields=null, $restKey=null, $restValue=null)
    {
        parent::__construct($filename, $dialect);
        $this->fields = $fields ?: null; // we want any empty value to be `null`
        $this->restKey =  $restKey;
        $this->restValue = $restValue;
    }

    public static function builder($filename)
    {
        return new Builder\DictReaderBuilder($filename);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        $fh = $this->openFile();
        $colCount = $this->fields ? count($this->fields) : null;
        $delim = $this->getDelimiter();
        $enclose = $this->getEnclosure();
        $esc = $this->getEscapeCharacter();
        try {
            while (true) {
                $row = fgetcsv($fh, 0, $delim, $enclose, $esc);
                if (false === $row) {
                    break;
                }

                if (self::isEmptyLine($row)) {
                    continue;
                }

                // no fields set up? Then use the first line in the file
                if (null === $this->fields) {
                    $this->fields = $row;
                    $colCount = count($this->fields);
                    continue;
                }

                list($_row, $extras) = $this->normalizeRow($row, $colCount);

                $out = array_combine($this->fields, $_row);
                if (null !== $this->restKey) {
                    $out[$this->restKey] = $extras;
                }

                yield $out;
            }
        } finally {
            fclose($fh);
        }
    }

    private function normalizeRow(array $row, $colCount)
    {
        while (count($row) < $colCount) {
            $row[] = $this->restValue;
        }

        return [array_slice($row, 0, $colCount), array_slice($row, $colCount)];
    }
}
