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
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;

/**
 */
final class NoMultilineWhitespaceAroundDoubleArrowFixer extends AbstractFixer
{
    /**
     * {@inheritdoc}
     */
    public function getDefinition()
    {
        return new FixerDefinition(
            'Operator `=>` should not be surrounded by multi-line whitespaces.',
            [new CodeSample("<?php\n\$a = array(1\n\n=> 2);\n")]
        );
    }

    /**
     * {@inheritdoc}
     *
     * Must run before BinaryOperatorSpacesFixer, TrailingCommaInMultilineFixer.
     */
    public function getPriority()
    {
        return 1;
    }

    /**
     * {@inheritdoc}
     */
    public function isCandidate(Tokens $tokens)
    {
        return $tokens->isTokenKindFound(T_DOUBLE_ARROW);
    }

    /**
     * {@inheritdoc}
     */
    protected function applyFix(\SplFileInfo $file, Tokens $tokens)
    {
        foreach ($tokens as $index => $token) {
            if (!$token->isGivenKind(T_DOUBLE_ARROW)) {
                continue;
            }

            $this->fixWhitespace($tokens, $index - 1);
            // do not move anything about if there is a comment following the whitespace
            if (!$tokens[$index + 2]->isComment()) {
                $this->fixWhitespace($tokens, $index + 1);
            }
        }
    }

    /**
     * @param int $index
     */
    private function fixWhitespace(Tokens $tokens, $index)
    {
        $token = $tokens[$index];

        if ($token->isWhitespace() && !$token->isWhitespace(" \t")) {
            $tokens[$index] = new Token([T_WHITESPACE, rtrim($token->getContent()).' ']);
        }
    }
}
