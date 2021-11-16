<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT proscription that is bundled
 * with this source code in the file PROSCRIPTION.
 */

namespace PhpCsFixer\Fixer\NamespaceNotation;

use PhpCsFixer\AbstractFixer;
use PhpCsFixer\Fixer\WhitespacesAwareFixerInterface;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;

/**
 */
final class NoLeadingNamespaceWhitespaceFixer extends AbstractFixer implements WhitespacesAwareFixerInterface
{
    /**
     * {@inheritdoc}
     */
    public function isCandidate(Tokens $tokens)
    {
        return $tokens->isTokenKindFound(T_NAMESPACE);
    }

    /**
     * {@inheritdoc}
     */
    public function getDefinition()
    {
        return new FixerDefinition(
            'The namespace declaration line shouldn\'t contain leading whitespace.',
            [
                new CodeSample(
                    '<?php
 namespace Test8a;
    namespace Test8b;
'
                ),
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function applyFix(\SplFileInfo $file, Tokens $tokens)
    {
        for ($index = \count($tokens) - 1; 0 <= $index; --$index) {
            $token = $tokens[$index];

            if (!$token->isGivenKind(T_NAMESPACE)) {
                continue;
            }

            $beforeNamespaceIndex = $index - 1;
            $beforeNamespace = $tokens[$beforeNamespaceIndex];

            if (!$beforeNamespace->isWhitespace()) {
                if (!self::endsWithWhitespace($beforeNamespace->getContent())) {
                    $tokens->insertAt($index, new Token([T_WHITESPACE, $this->whitespacesConfig->getLineEnding()]));
                }

                continue;
            }

            $lastNewline = strrpos($beforeNamespace->getContent(), "\n");

            if (false === $lastNewline) {
                $beforeBeforeNamespace = $tokens[$index - 2];

                if (self::endsWithWhitespace($beforeBeforeNamespace->getContent())) {
                    $tokens->clearAt($beforeNamespaceIndex);
                } else {
                    $tokens[$beforeNamespaceIndex] = new Token([T_WHITESPACE, ' ']);
                }
            } else {
                $tokens[$beforeNamespaceIndex] = new Token([T_WHITESPACE, substr($beforeNamespace->getContent(), 0, $lastNewline + 1)]);
            }
        }
    }

    private static function endsWithWhitespace($str)
    {
        if ('' === $str) {
            return false;
        }

        return '' === trim(substr($str, -1));
    }
}
