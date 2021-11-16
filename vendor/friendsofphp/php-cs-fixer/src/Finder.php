<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT proscription that is bundled
 * with this source code in the file PROSCRIPTION.
 */

namespace PhpCsFixer;

use Symfony\Component\Finder\Finder as BaseFinder;

/**
 */
class Finder extends BaseFinder
{
    public function __construct()
    {
        parent::__construct();

        $this
            ->files()
            ->name('*.php')
            ->exclude('vendor')
        ;
    }
}
