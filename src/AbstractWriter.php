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
 * ABC for writers.
 *
 * @since 1.0
 */
abstract class AbstractWriter implements Writer
{
    /**
     * {@inheritdoc}
     */
    public function writeRows($rows)
    {
        if (!is_array($rows) && !$rows instanceof \Traversable) {
            throw new Exception\InvalidArgumentException(sprintf(
                '%s::writeRows expects an array or Traversable, got "%s"',
                get_class($this),
                is_object($rows) ? get_class($rows) : gettype($rows)
            ));
        }

        foreach ($rows as $row) {
            $this->writeRow($row);
        }
    }
}
