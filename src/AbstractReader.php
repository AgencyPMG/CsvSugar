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
 * ABC for readers.
 *
 * @since 1.0
 */
abstract class AbstractReader implements \IteratorAggregate, Reader
{
    /**
     * @var string
     */
    private $filename;

    /**
     * @var Dialect
     */
    private $dialect;

    public function __construct($filename, Dialect $dialect=null)
    {
        $this->filename = $filename;
        $this->dialect = $dialect ?: Dialect::csv();
    }

    protected function getDialect()
    {
        return $this->dialect;
    }

    protected function getDelimiter() : string
    {
        return $this->getDialect()->getDelimiter();
    }

    protected function getEnclosure() : string
    {
        return $this->getDialect()->getEnclosure();
    }

    protected function getEscapeCharacter() : string
    {
        return $this->getDialect()->getEscapeCharacter();
    }

    protected function openFile()
    {
        $fh = @fopen($this->filename, 'r');
        if (false === $fh) {
            $err = error_get_last();
            throw new Exception\CouldNotOpenFile(sprintf(
                'Could not open "%s" for reading: %s',
                $this->filename,
                isset($err['message']) ? $err['message'] : 'unknown error'
            ));
        }

        return $fh;
    }

    protected static function isEmptyLine($line) : bool
    {
        return [null] === $line;
    }
}
