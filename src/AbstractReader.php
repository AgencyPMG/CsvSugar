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

use Generator;

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

    public function __construct(string $filename, Dialect $dialect=null)
    {
        $this->filename = $filename;
        $this->dialect = $dialect ?: Dialect::csv();
    }

    public function getIterator() : Generator
    {
        $fh = $this->openFile();
        try {
            yield from $this->readFile($fh);
        } finally {
            @fclose($fh);
        }
    }

    /**
     * Actually read the file from its resource handle
     *
     * @param resource $fh;
     */
    abstract protected function readFile($fh) : iterable;

    protected function getDialect() : Dialect
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
            error_clear_last();
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
