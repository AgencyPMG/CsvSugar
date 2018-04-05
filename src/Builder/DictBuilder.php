<?php declare(strict_types=1);

/*
 * This file is part of pmg/csv-sugar.
 *
 * Copyright (c) PMG <https://www.pmg.com>.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PMG\CsvSugar\Builder;

use PMG\CsvSugar\Dialect;

/**
 * ABC for Dict{Reader,Writer} builders.
 *
 * @since 1.0
 */
abstract class DictBuilder
{
    protected $filename;
    protected $dialect = null;
    protected $fields = null;
    protected $restValue = null;

    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    public function withDialect(Dialect $dialect=null)
    {
        $this->dialect = $dialect;
        return $this;
    }

    public function withFields(array $fields=null)
    {
        $this->fields = $fields;
        return $this;
    }

    public function withRestValue($value)
    {
        $this->restValue = $value;
        return $this;
    }
}
