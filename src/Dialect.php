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
 * A value object representing the CSV dialect (delimiter, enclosure, and
 * escape character).
 *
 * @since 1.0
 */
final class Dialect
{
    const DEFAULT_ENCLOSURE = '"';
    const DEFAULT_ESCAPE = '\\';

    private $delimiter;
    private $enclosure;
    private $escapeChar;

    public function __construct(string $delimiter, string $enclosure=null, string $escapeChar=null)
    {
        $this->delimiter = $delimiter;
        $this->enclosure = $enclosure ?? self::DEFAULT_ENCLOSURE;
        $this->escapeChar = $escapeChar ?? self::DEFAULT_ESCAPE;
    }

    public function getDelimiter() : string
    {
        return $this->delimiter;
    }

    public function getEnclosure() : string
    {
        return $this->enclosure;
    }

    public function getEscapeCharacter() : string
    {
        return $this->escapeChar;
    }

    public static function csv() : self
    {
        return new self(',');
    }

    public static function tsv() : self
    {
        return new self("\t");
    }

    public static function tilde() : self
    {
        return new self('~');
    }

    public static function pipe() : self
    {
        return new self('|');
    }
}
