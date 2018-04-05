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
 * A simple CSV reader, pretty close to what SplFileObject does itself.
 *
 * @since 1.0
 */
final class SimpleReader extends AbstractReader
{
    /**
     * {@inheritdoc}
     */
    protected function readFile($fh) : iterable
    {
        $fh = $this->openFile();
        $delim = $this->getDelimiter();
        $enclose = $this->getEnclosure();
        $esc = $this->getEscapeCharacter();
        while (true) {
            $line = fgetcsv($fh, 0, $delim, $enclose, $esc);
            if (false === $line) {
                break;
            }
            if (self::isEmptyLine($line)) {
                continue;
            }
            yield $line;
        }
    }
}
