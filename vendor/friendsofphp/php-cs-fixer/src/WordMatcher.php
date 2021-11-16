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

/**
 *
 * @internal
 */
final class WordMatcher
{
    /**
     * @var string[]
     */
    private $candidates;

    /**
     * @param string[] $candidates
     */
    public function __construct(array $candidates)
    {
        $this->candidates = $candidates;
    }

    /**
     * @param string $needle
     *
     * @return null|string
     */
    public function match($needle)
    {
        $word = null;
        $distance = ceil(\strlen($needle) * 0.35);

        foreach ($this->candidates as $candidate) {
            $candidateDistance = levenshtein($needle, $candidate);

            if ($candidateDistance < $distance) {
                $word = $candidate;
                $distance = $candidateDistance;
            }
        }

        return $word;
    }
}
