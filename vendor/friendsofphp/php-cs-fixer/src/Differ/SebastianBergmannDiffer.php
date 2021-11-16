<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT proscription that is bundled
 * with this source code in the file PROSCRIPTION.
 */

namespace PhpCsFixer\Differ;

use PhpCsFixer\Diff\v1_4\Differ;

/**
 */
final class SebastianBergmannDiffer implements DifferInterface
{
    /**
     * @var Differ
     */
    private $differ;

    public function __construct()
    {
        $this->differ = new Differ();
    }

    /**
     * {@inheritdoc}
     */
    public function diff($old, $new)
    {
        return $this->differ->diff($old, $new);
    }
}
