<?php
/*
 * This file is part of pmg/csv-sugar.
 *
 * Copyright (c) PMG <https://www.pmg.com>.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

@ini_set('auto_detect_line_endings', true);

$loader = require __DIR__.'/../vendor/autoload.php';

$loader->addPsr4('PMG\\CsvSugar\\', __DIR__);
