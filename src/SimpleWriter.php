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
 * A simple CSV writer. Doesn't do anything special, just writes CSV
 *
 * @since 1.0
 */
final class SimpleWriter extends AbstractWriter
{
    use Configuration;

    /**
     * @var SplFileObject
     */
    private $fileObject;

    /**
     * @var Dialect
     */
    private $dialect;

    /**
     * @param SplFileObject|string $file The file to use
     * @param Dialect|null $dialect The CSV dialect
     * @return void
     */
    public function __construct($file, Dialect $dialect=null)
    {
        try {
            $this->fileObject = $file instanceof \SplFileObject ? $file : new \SplFileObject($file, 'w');
        } catch (\RuntimeException $e) {
            throw Exception\CouldNotOpenFile::wrap($e);
        }
        $this->dialect = $dialect ?: Dialect::csv();
        self::configureFileObject($this->fileObject, $this->dialect);
    }

    /**
     * {@inheritdoc}
     * @param array|Traversable $row The row to write
     */
    public function writeRow($row)
    {
        if ($row instanceof \Traversable) {
            $row = iterator_to_array($row);
        }

        $this->fileObject->fputcsv(is_array($row) ? $row : [$row]);
    }
}
