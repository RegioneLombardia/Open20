<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\Console\Helper;

/**
 * Marks a row as being a separator.
 *
 */
class TableSeparator extends TableCell
{
    public function __construct(array $options = [])
    {
        parent::__construct('', $options);
    }
}
