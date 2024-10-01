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
final class SwitchAnalysis
{
    /**
     * @var int
     */
    private $casesStart;

    /**
     * @var int
     */
    private $casesEnd;

    /**
     * @var CaseAnalysis[]
     */
    private $cases = [];

    /**
     * @param int            $casesStart
     * @param int            $casesEnd
     * @param CaseAnalysis[] $cases
     */
    public function __construct($casesStart, $casesEnd, array $cases)
    {
        $this->casesStart = $casesStart;
        $this->casesEnd = $casesEnd;
        $this->cases = $cases;
    }

    /**
     * @return int
     */
    public function getCasesStart()
    {
        return $this->casesStart;
    }

    /**
     * @return int
     */
    public function getCasesEnd()
    {
        return $this->casesEnd;
    }

    /**
     * @return CaseAnalysis[]
     */
    public function getCases()
    {
        return $this->cases;
    }
}
