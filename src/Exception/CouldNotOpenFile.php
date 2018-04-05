<?php
/*
 * This file is part of pmg/csv-sugar.
 *
 * Copyright (c) PMG <https://www.pmg.com>.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PMG\CsvSugar\Exception;

use PMG\CsvSugar\CsvException;

final class CouldNotOpenFile extends RuntimeException
{
    public static function wrap(\Throwable $e) : self
    {
        return new self($e->getMessage(), $e->getCode(), $e);
    }
}
