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
 * Read a CSV file. Implementations of this perform just like an iterable.
 *
 * $reader = new SomeReaderImplementation();
 * foreach ($reader as $row) {
 * }
 *
 * @since 1.0
 *
 * @deprecated This class is deprecated. Use [Alli\Platform\Util\Csv\Reader](https://github.com/AgencyPMG/alli-platform-sdk-php/blob/master/src/Util/Csv/Reader.php) instead.
 */
interface Reader extends \Traversable
{

}
