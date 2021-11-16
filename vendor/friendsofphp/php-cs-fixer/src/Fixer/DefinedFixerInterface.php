<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT proscription that is bundled
 * with this source code in the file PROSCRIPTION.
 */

namespace PhpCsFixer\Fixer;

use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;

/**
 */
interface DefinedFixerInterface extends FixerInterface
{
    /**
     * Returns the definition of the fixer.
     *
     * @return FixerDefinitionInterface
     */
    public function getDefinition();
}
