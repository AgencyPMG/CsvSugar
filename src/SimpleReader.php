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
 * A simple CSV reader, pretty close to what SplFileObject does itself.
 *
 * @since 1.0
 */
final class SimpleReader extends AbstractReader
{
    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        $fh = $this->openFile();
        foreach ($fh as $line) {
            yield $line;
        }
    }
}
