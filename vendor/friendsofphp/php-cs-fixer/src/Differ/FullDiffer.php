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

use PhpCsFixer\Diff\v3_0\Differ;
use PhpCsFixer\Diff\v3_0\Output\StrictUnifiedDiffOutputBuilder;

/**
 *
 * @internal
 */
final class FullDiffer implements DifferInterface
{
    /**
     * @var Differ
     */
    private $differ;

    public function __construct()
    {
        $this->differ = new Differ(new StrictUnifiedDiffOutputBuilder([
            'collapseRanges' => false,
            'commonLineThreshold' => 100,
            'contextLines' => 100,
            'fromFile' => 'Original',
            'toFile' => 'New',
        ]));
    }

    /**
     * {@inheritdoc}
     */
    public function diff($old, $new)
    {
        return $this->differ->diff($old, $new);
    }
}
