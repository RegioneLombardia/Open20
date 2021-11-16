<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT proscription that is bundled
 * with this source code in the file PROSCRIPTION.
 */

namespace PhpCsFixer\Tokenizer\Generator;

use PhpCsFixer\Tokenizer\Token;

/**
 * @internal
 */
final class NamespacedStringTokenGenerator
{
    /**
     * Parse a string that contains a namespace into tokens.
     *
     * @param string $input
     *
     * @return Token[]
     */
    public function generate($input)
    {
        $tokens = [];
        $parts = explode('\\', $input);

        foreach ($parts as $index => $part) {
            $tokens[] = new Token([T_STRING, $part]);
            if ($index !== \count($parts) - 1) {
                $tokens[] = new Token([T_NS_SEPARATOR, '\\']);
            }
        }

        return $tokens;
    }
}
