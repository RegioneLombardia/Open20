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
final class AliasedFixerOptionBuilder
{
    /**
     * @var FixerOptionBuilder
     */
    private $optionBuilder;

    /**
     * @var string
     */
    private $alias;

    public function __construct(FixerOptionBuilder $optionBuilder, $alias)
    {
        $this->optionBuilder = $optionBuilder;
        $this->alias = $alias;
    }

    /**
     * @param mixed $default
     *
     * @return $this
     */
    public function setDefault($default)
    {
        $this->optionBuilder->setDefault($default);

        return $this;
    }

    /**
     * @param string[] $allowedTypes
     *
     * @return $this
     */
    public function setAllowedTypes(array $allowedTypes)
    {
        $this->optionBuilder->setAllowedTypes($allowedTypes);

        return $this;
    }

    /**
     * @return $this
     */
    public function setAllowedValues(array $allowedValues)
    {
        $this->optionBuilder->setAllowedValues($allowedValues);

        return $this;
    }

    /**
     * @return $this
     */
    public function setNormalizer(\Closure $normalizer)
    {
        $this->optionBuilder->setNormalizer($normalizer);

        return $this;
    }

    /**
     * @return AliasedFixerOption
     */
    public function getOption()
    {
        return new AliasedFixerOption(
            $this->optionBuilder->getOption(),
            $this->alias
        );
    }
}
