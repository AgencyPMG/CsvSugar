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
 * Provides support for object "openers", generally you don't need to worry
 * about these things, but it's useful when you need extra junk in your
 * `SplFileObject`s like stream context or include path awareness.
 *
 * @since 1.0
 */
trait Opener
{
    private $fileOpener = null;

    /**
     * Change the file opener for the object. Note that this doesn't check
     * whether the file has already been opened, so there's some temporal
     * coupling issues here.
     *
     * @param callable|null $opener The opener to use. If null it tells the
     *        object to use the default opener.
     * @return void
     */
    public function setFileOpener(callable $opener=null)
    {
        $this->fileOpener = $opener;
    }

    protected function createFileObject($filename, $mode)
    {
        $fh = call_user_func($this->fileOpener ?: [$this, 'defaultOpener'], $filename, $mode);
        if (!$fh instanceof \SplFileObject) {
            throw new Exception\UnexpectedValueException(sprintf(
                'Expected the opener to return a SplFileObject, got "%s"',
                is_object($fh) ? get_class($fh) : gettype($fh)
            ));
        }

        return $fh;
    }

    protected function defaultOpener($filename, $mode)
    {
        try {
            return new \SplFileObject($filename, $mode);
        } catch (\RuntimeException $e) {
            throw new Exception\RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
