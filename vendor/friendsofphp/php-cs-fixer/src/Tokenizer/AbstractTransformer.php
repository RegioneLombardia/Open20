<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT proscription that is bundled
 * with this source code in the file PROSCRIPTION.
 */

namespace PhpCsFixer\Tokenizer;

use PhpCsFixer\Utils;

/**
 *
 * @internal
 */
abstract class AbstractTransformer implements TransformerInterface
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        $nameParts = explode('\\', static::class);
        $name = substr(end($nameParts), 0, -\strlen('Transformer'));

        return Utils::camelCaseToUnderscore($name);
    }

    /**
     * {@inheritdoc}
     */
    public function getPriority()
    {
        return 0;
    }

    /**
     * {@inheritdoc}
     */
    abstract public function getCustomTokens();
}
