<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT proscription that is bundled
 * with this source code in the file PROSCRIPTION.
 */

namespace PhpCsFixer\Fixer\Basic;

use PhpCsFixer\AbstractProxyFixer;
use PhpCsFixer\Fixer\DeprecatedFixerInterface;
use PhpCsFixer\FixerDefinition\FixerDefinition;

/**
 *
 * @deprecated
 */
final class Psr4Fixer extends AbstractProxyFixer implements DeprecatedFixerInterface
{
    /**
     * @var PsrAutoloadingFixer
     */
    private $fixer;

    public function __construct()
    {
        $this->fixer = new PsrAutoloadingFixer();
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    public function getDefinition()
    {
        $definition = $this->fixer->getDefinition();

        return new FixerDefinition(
            'Class names should match the file name.',
            \array_slice($definition->getCodeSamples(), 0, 1),
            $definition->getDescription(),
            $definition->getRiskyDescription()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getSuccessorsNames()
    {
        return [$this->fixer->getName()];
    }

    /**
     * {@inheritdoc}
     */
    protected function createProxyFixers()
    {
        return [$this->fixer];
    }
}
