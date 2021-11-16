<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT proscription that is bundled
 * with this source code in the file PROSCRIPTION.
 */

namespace PhpCsFixer\FixerDefinition;

/**
 */
final class VersionSpecificCodeSample implements VersionSpecificCodeSampleInterface
{
    /**
     * @var CodeSampleInterface
     */
    private $codeSample;

    /**
     * @var VersionSpecificationInterface
     */
    private $versionSpecification;

    /**
     * @param string $code
     */
    public function __construct(
        $code,
        VersionSpecificationInterface $versionSpecification,
        array $configuration = null
    ) {
        $this->codeSample = new CodeSample($code, $configuration);
        $this->versionSpecification = $versionSpecification;
    }

    /**
     * {@inheritdoc}
     */
    public function getCode()
    {
        return $this->codeSample->getCode();
    }

    /**
     * {@inheritdoc}
     */
    public function getConfiguration()
    {
        return $this->codeSample->getConfiguration();
    }

    /**
     * {@inheritdoc}
     */
    public function isSuitableFor($version)
    {
        return $this->versionSpecification->isSatisfiedBy($version);
    }
}
