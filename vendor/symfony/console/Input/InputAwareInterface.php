<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\Console\Input;

/**
 * InputAwareInterface should be implemented by classes that depends on the
 * Console Input.
 *
 */
interface InputAwareInterface
{
    /**
     * Sets the Console Input.
     */
    public function setInput(InputInterface $input);
}
