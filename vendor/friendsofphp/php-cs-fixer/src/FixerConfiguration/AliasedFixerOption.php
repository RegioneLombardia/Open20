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

/**
 *
 * @internal
 */
final class AliasedFixerOption implements FixerOptionInterface
{
    /**
     * @var FixerOptionInterface
     */
    private $fixerOption;

    /**
     * @var string
     */
    private $alias;

    public function __construct(FixerOptionInterface $fixerOption, $alias)
    {
        $this->fixerOption = $fixerOption;
        $this->alias = $alias;
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->fixerOption->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return $this->fixerOption->getDescription();
    }

    /**
     * {@inheritdoc}
     */
    public function hasDefault()
    {
        return $this->fixerOption->hasDefault();
    }

    /**
     * {@inheritdoc}
     */
    public function getDefault()
    {
        return $this->fixerOption->getDefault();
    }

    /**
     * {@inheritdoc}
     */
    public function getAllowedTypes()
    {
        return $this->fixerOption->getAllowedTypes();
    }

    /**
     * {@inheritdoc}
     */
    public function getAllowedValues()
    {
        return $this->fixerOption->getAllowedValues();
    }

    /**
     * {@inheritdoc}
     */
    public function getNormalizer()
    {
        return $this->fixerOption->getNormalizer();
    }
}
