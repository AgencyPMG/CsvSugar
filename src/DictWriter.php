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
 * Write a CSV file with associative arrays.
 *
 * @since 1.0
 */
final class DictWriter extends AbstractWriter
{
    const IGNORE_INVALID = 0;
    const ERROR_INVALID = 1;

    /**
     * @var SimpleWriter
     */
    private $writer;

    /**
     * The header for the file.
     *
     * @var string[]
     */
    private $fields;

    /**
     * What to do with invalid fields.
     *
     * @var int
     */
    private $errorBehavior;

    /**
     * The value to use for fields that are missing.
     *
     * @var string
     */
    private $restValue;

    /**
     * @param string|SplFileObject $file The file to write
     * @param Dialect|null $dialect The delimiter dialect to use
     * @param array $headers The CSV file headers
     * @param int $invalidBehavior What to do with invalid columns
     */
    public function __construct($file, Dialect $dialect=null, array $fields, $errorBehavior=self::IGNORE_INVALID, $restValue='')
    {
        $this->writer = new SimpleWriter($file, $dialect);
        $this->fields = array_fill_keys(array_values($fields), true);
        $this->errorBehavior = $errorBehavior;
        $this->restValue = $restValue;
    }

    public static function builder($file)
    {
        return new Builder\DictWriterBuilder($file);
    }

    /**
     * Write the headers to the file. This is 100% optional: not all files need
     * column headers.
     *
     * @return void
     */
    public function writeHeader()
    {
        $this->writer->writeRow(array_keys($this->fields));
    }

    /**
     * {@inheritdoc}
     * @param array|ArrayAccess $row The row to write
     */
    public function writeRow($row)
    {
        if (!is_array($row) && !$row instanceof \ArrayAccess) {
            throw new Exception\InvalidArgumentException(sprintf(
                '%s expects an array or ArrayAccess implementation, got "%s"',
                __METHOD__,
                is_object($row) ? get_class($row) : gettype($row)
            ));
        }

        if (self::ERROR_INVALID === $this->errorBehavior) {
            $this->assureValidRow($row);
        }

        $this->writer->writeRow($this->cleanRow($row));
    }

    private function assureValidRow($row)
    {
        $invalid = array_diff_key($row, $this->fields);
        if ($invalid) {
            throw Exception\InvalidKeys::fromInvalid($invalid, array_keys($this->fields));
        }
    }

    private function cleanRow($row)
    {
        $out = [];
        foreach ($this->fields as $key => $_) {
            $out[] = isset($row[$key]) ? $row[$key] : $this->restValue;
        }

        return $out;
    }
}
