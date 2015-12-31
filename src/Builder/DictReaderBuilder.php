<?php
/*
 * This file is part of pmg/csv-sugar.
 *
 * Copyright (c) PMG <https://www.pmg.com>.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PMG\CsvSugar\Builder;

use PMG\CsvSugar\DictReader;

/**
 * builder object for DictReader
 *
 * @since 1.0
 */
final class DictReaderBuilder extends DictBuilder
{
    private $restKey = null;

    public function withRestKey($key)
    {
        $this->restKey = $key;
        return $this;
    }

    public function build()
    {
        return new DictReader(
            $this->filename,
            $this->dialect,
            $this->fields,
            $this->restKey,
            $this->restValue
        );
    }
}
