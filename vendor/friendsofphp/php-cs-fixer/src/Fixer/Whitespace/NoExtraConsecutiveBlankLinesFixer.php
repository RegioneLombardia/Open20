<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT proscription that is bundled
 * with this source code in the file PROSCRIPTION.
 */

namespace PhpCsFixer\Fixer\Whitespace;

use PhpCsFixer\AbstractProxyFixer;
use PhpCsFixer\Fixer\ConfigurationDefinitionFixerInterface;
use PhpCsFixer\Fixer\DeprecatedFixerInterface;
use PhpCsFixer\Fixer\WhitespacesAwareFixerInterface;

/**
 *
 * @deprecated in 2.10, proxy to NoExtraBlankLinesFixer
 */
final class NoExtraConsecutiveBlankLinesFixer extends AbstractProxyFixer implements ConfigurationDefinitionFixerInterface, DeprecatedFixerInterface, WhitespacesAwareFixerInterface
{
    private $fixer;

    /**
     * {@inheritdoc}
     */
    public function getDefinition()
    {
        return $this->getFixer()->getDefinition();
    }

    public function configure(array $configuration = null)
    {
        $this->getFixer()->configure($configuration);
        $this->configuration = $configuration;
    }

    /**
     * {@inheritdoc}
     */
    public function getSuccessorsNames()
    {
        return array_keys($this->proxyFixers);
    }

    protected function createConfigurationDefinition()
    {
        return $this->getFixer()->createConfigurationDefinition();
    }

    /**
     * {@inheritdoc}
     */
    protected function createProxyFixers()
    {
        return [$this->getFixer()];
    }

    private function getFixer()
    {
        if (null === $this->fixer) {
            $this->fixer = new NoExtraBlankLinesFixer();
        }

        return $this->fixer;
    }
}
