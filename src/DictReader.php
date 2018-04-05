<?php declare(strict_types=1);

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

    public function __construct(string $filename, Dialect $dialect=null, array $fields=null, string $restKey=null, $restValue=null)
    {
        parent::__construct($filename, $dialect);
        $this->fields = $fields ?: null; // we want any empty value to be `null`
        $this->restKey =  $restKey;
        $this->restValue = $restValue;
    }

    public static function builder($filename) : Builder\DictReaderBuilder
    {
        return new Builder\DictReaderBuilder($filename);
    }

    /**
     * {@inheritdoc}
     */
    protected function readFile($fh) : iterable
    {
        $fh = $this->openFile();
        $fields = $this->fields ?: null;
        $colCount = $fields ? count($fields) : null;
        $delim = $this->getDelimiter();
        $enclose = $this->getEnclosure();
        $esc = $this->getEscapeCharacter();
        while (true) {
            $row = fgetcsv($fh, 0, $delim, $enclose, $esc);
            if (false === $row) {
                break;
            }

            if (self::isEmptyLine($row)) {
                continue;
            }

            // no fields set up? Then use the first line in the file
            if (null === $fields) {
                $fields = $row;
                $colCount = count($fields);
                continue;
            }

            list($_row, $extras) = $this->normalizeRow($row, $colCount);

            $out = array_combine($fields, $_row);
            if (null !== $this->restKey) {
                $out[$this->restKey] = $extras;
            }

            yield $out;
        }
    }

    private function normalizeRow(array $row, $colCount)
    {
        $rc = count($row);
        if ($rc < $colCount) {
            $row = array_merge($row, array_fill(
                0,
                $colCount - $rc,
                $this->restValue
            ));
        }

        return [array_slice($row, 0, $colCount), array_slice($row, $colCount)];
    }
}
