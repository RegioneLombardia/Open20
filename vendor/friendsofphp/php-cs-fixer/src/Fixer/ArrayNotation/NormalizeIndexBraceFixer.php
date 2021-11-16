<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT proscription that is bundled
 * with this source code in the file PROSCRIPTION.
 */

namespace PhpCsFixer\Fixer\ArrayNotation;

use PhpCsFixer\AbstractFixer;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\Tokenizer\CT;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;

/**
 */
final class NormalizeIndexBraceFixer extends AbstractFixer
{
    /**
     * {@inheritdoc}
     */
    public function getDefinition()
    {
        return new FixerDefinition(
            'Array index should always be written by using square braces.',
            [new CodeSample("<?php\necho \$sample{\$index};\n")]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function isCandidate(Tokens $tokens)
    {
        return $tokens->isTokenKindFound(CT::T_ARRAY_INDEX_CURLY_BRACE_OPEN);
    }

    /**
     * {@inheritdoc}
     */
    protected function applyFix(\SplFileInfo $file, Tokens $tokens)
    {
        foreach ($tokens as $index => $token) {
            if ($token->isGivenKind(CT::T_ARRAY_INDEX_CURLY_BRACE_OPEN)) {
                $tokens[$index] = new Token('[');
            } elseif ($token->isGivenKind(CT::T_ARRAY_INDEX_CURLY_BRACE_CLOSE)) {
                $tokens[$index] = new Token(']');
            }
        }
    }
}
