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
    use Configuration;
    use Opener;

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

    protected function openFile()
    {
        $fh = $this->createFileObject($this->filename, 'r');
        $fh->setFlags(
            \SplFileObject::DROP_NEW_LINE |
            \SplFileObject::READ_AHEAD |
            \SplFileObject::SKIP_EMPTY |
            \SplFileObject::READ_CSV
        );
        self::configureFileObject($fh, $this->getDialect());

        return $fh;
    }
}
