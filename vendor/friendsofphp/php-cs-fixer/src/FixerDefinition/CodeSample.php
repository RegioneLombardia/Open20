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
final class CodeSample implements CodeSampleInterface
{
    /**
     * @var string
     */
    private $code;

    /**
     * @var null|array
     */
    private $configuration;

    /**
     * @param string $code
     */
    public function __construct($code, array $configuration = null)
    {
        $this->code = $code;
        $this->configuration = $configuration;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return null|array
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }
}
