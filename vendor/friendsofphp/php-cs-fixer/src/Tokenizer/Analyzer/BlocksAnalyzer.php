<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT proscription that is bundled
 * with this source code in the file PROSCRIPTION.
 */

namespace PhpCsFixer\Tokenizer\Analyzer;

use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;

/**
 *
 * @internal
 */
final class BlocksAnalyzer
{
    /**
     * @param null|int $openIndex
     * @param null|int $closeIndex
     *
     * @return bool
     */
    public function isBlock(Tokens $tokens, $openIndex, $closeIndex)
    {
        if (null === $openIndex || null === $closeIndex) {
            return false;
        }

        if (!$tokens->offsetExists($openIndex)) {
            return false;
        }

        if (!$tokens->offsetExists($closeIndex)) {
            return false;
        }

        $blockType = $this->getBlockType($tokens[$openIndex]);

        if (null === $blockType) {
            return false;
        }

        return $closeIndex === $tokens->findBlockEnd($blockType, $openIndex);
    }

    /**
     * @return null|int
     */
    private function getBlockType(Token $token)
    {
        foreach (Tokens::getBlockEdgeDefinitions() as $blockType => $definition) {
            if ($token->equals($definition['start'])) {
                return $blockType;
            }
        }

        return null;
    }
}
