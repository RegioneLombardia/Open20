<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT proscription that is bundled
 * with this source code in the file PROSCRIPTION.
 */

namespace PhpCsFixer\FixerConfiguration;

interface FixerConfigurationResolverInterface
{
    /**
     * @return FixerOptionInterface[]
     */
    public function getOptions();

    /**
     * @param array<string, mixed> $configuration
     *
     * @return array<string, mixed>
     */
    public function resolve(array $configuration);
}
