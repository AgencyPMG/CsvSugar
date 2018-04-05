<?php declare(strict_types=1);

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

/**
 * Thrown when a DictWriter find some invalid keys and DictReader::ERROR_INVALID
 * is used.
 *
 * @since 1.0
 */
final class InvalidKeys extends \InvalidArgumentException implements CsvException
{
    /**
     * @var string[]
     */
    private $invalid;

    /**
     * @var string[]
     */
    private $valid;

    public static function fromInvalid(array $invalid, array $valid)
    {
        $s = new self(sprintf(
            'Invalid keys: %s, valid keys are %s',
            implode(', ', array_keys($invalid)),
            implode(', ', $valid)
        ));
        $s->invalid = $invalid;
        $s->valid = $valid;

        return $s;
    }

    public function getInvalidFields()
    {
        return $this->invalid;
    }

    public function getValidFields()
    {
        return $this->valid;
    }
}
