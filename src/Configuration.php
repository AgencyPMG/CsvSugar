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
 * A trait that helps configure SplFileObjects with the dialect settings.
 *
 * @since 1.0
 */
trait Configuration
{
    public static function configureFileObject(\SplFileObject $fh, Dialect $dialect)
    {
        $fh->setCsvControl(
            $dialect->getDelimiter(),
            $dialect->getEnclosure(),
            $dialect->getEscapeCharacter()
        );
    }
}
