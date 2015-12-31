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

    public function __construct($delimiter, $enclosure=null, $escapeChar=null)
    {
        $this->delimiter = $delimiter;
        $this->enclosure = null === $enclosure ? self::DEFAULT_ENCLOSURE : $enclosure;
        $this->escapeChar = null === $escapeChar ? self::DEFAULT_ESCAPE : $escapeChar;
    }

    public function getDelimiter()
    {
        return $this->delimiter;
    }

    public function getEnclosure()
    {
        return $this->enclosure;
    }

    public function getEscapeCharacter()
    {
        return $this->escapeChar;
    }

    public static function csv()
    {
        return new self(',');
    }

    public static function tsv()
    {
        return new self("\t");
    }

    public static function tilde()
    {
        return new self('~');
    }

    public static function pipe()
    {
        return new self('|');
    }
}
