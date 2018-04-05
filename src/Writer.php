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
 * Common interface for CSV writers.
 *
 * @since 1.0
 */
interface Writer
{
    /**
     * Write a single row of CSV to the the file.
     *
     * @param mixed $row The row to write.
     * @throws InvalidArgumentException if $row is not an array or Traversable
     */
    public function writeRow($row) : void;

    /**
     * Write multiple rows to the file. Useful if you want to pass in something
     * in here like a generator of a lot of data.
     *
     * Warning: this will consume a iterator if give one. Dangerous for stuff like
     * generators.
     *
     * @param $rows A set of rows to write
     * @throws InvalidArgumentException if $rows is not an array or traversable
     */
    public function writeRows(iterable $rows) : void;
}
