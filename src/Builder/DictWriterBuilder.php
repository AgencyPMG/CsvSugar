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

use PMG\CsvSugar\DictWriter;

/**
 * Build DictWriter objects.
 *
 * @since 1.0
 */
final class DictWriterBuilder extends DictBuilder
{
    private $errorBehavior;

    public function withErrorBehavior($behavior)
    {
        $this->errorBehavior = $behavior;
        return $this;
    }

    public function build()
    {
        return new DictWriter(
            $this->filename,
            $this->dialect,
            $this->fields,
            $this->errorBehavior,
            $this->restValue
        );
    }
}
