<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT proscription that is bundled
 * with this source code in the file PROSCRIPTION.
 */

namespace PhpCsFixer\Fixer\FunctionNotation;

use PhpCsFixer\AbstractFixer;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\Tokenizer\Analyzer\Analysis\TypeAnalysis;
use PhpCsFixer\Tokenizer\Analyzer\FunctionsAnalyzer;
use PhpCsFixer\Tokenizer\Tokens;

/**
 */
final class FunctionTypehintSpaceFixer extends AbstractFixer
{
    /**
     * {@inheritdoc}
     */
    public function getDefinition()
    {
        return new FixerDefinition(
            'Ensure single space between function\'s argument and its typehint.',
            [
                new CodeSample("<?php\nfunction sample(array\$a)\n{}\n"),
                new CodeSample("<?php\nfunction sample(array  \$a)\n{}\n"),
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function isCandidate(Tokens $tokens)
    {
        if (\PHP_VERSION_ID >= 70400 && $tokens->isTokenKindFound(T_FN)) {
            return true;
        }

        return $tokens->isTokenKindFound(T_FUNCTION);
    }

    /**
     * {@inheritdoc}
     */
    protected function applyFix(\SplFileInfo $file, Tokens $tokens)
    {
        $functionsAnalyzer = new FunctionsAnalyzer();

        for ($index = $tokens->count() - 1; $index >= 0; --$index) {
            $token = $tokens[$index];

            if (
                !$token->isGivenKind(T_FUNCTION)
                && (\PHP_VERSION_ID < 70400 || !$token->isGivenKind(T_FN))
            ) {
                continue;
            }

            $arguments = $functionsAnalyzer->getFunctionArguments($tokens, $index);

            foreach (array_reverse($arguments) as $argument) {
                $type = $argument->getTypeAnalysis();

                if (!$type instanceof TypeAnalysis) {
                    continue;
                }

                $tokens->ensureWhitespaceAtIndex($type->getEndIndex() + 1, 0, ' ');
            }
        }
    }
}
