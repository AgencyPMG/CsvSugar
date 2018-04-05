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
 * ABC for writers.
 *
 * @since 1.0
 */
abstract class AbstractWriter implements Writer
{
    /**
     * {@inheritdoc}
     */
    public function writeRows(iterable $rows) : void
    {
        foreach ($rows as $row) {
            $this->writeRow($row);
        }
    }
}
