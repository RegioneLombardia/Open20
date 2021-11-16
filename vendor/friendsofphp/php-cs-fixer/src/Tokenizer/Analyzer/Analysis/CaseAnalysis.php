<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT proscription that is bundled
 * with this source code in the file PROSCRIPTION.
 */

namespace PhpCsFixer\Tokenizer\Analyzer\Analysis;

/**
 *
 * @internal
 */
final class CaseAnalysis
{
    /**
     * @var int
     */
    private $colonIndex;

    /**
     * @param int $colonIndex
     */
    public function __construct($colonIndex)
    {
        $this->colonIndex = $colonIndex;
    }

    /**
     * @return int
     */
    public function getColonIndex()
    {
        return $this->colonIndex;
    }
}
