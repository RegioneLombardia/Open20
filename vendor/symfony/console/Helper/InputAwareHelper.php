<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\Console\Helper;

use Symfony\Component\Console\Input\InputAwareInterface;
use Symfony\Component\Console\Input\InputInterface;

/**
 * An implementation of InputAwareInterface for Helpers.
 *
 */
abstract class InputAwareHelper extends Helper implements InputAwareInterface
{
    protected $input;

    /**
     * {@inheritdoc}
     */
    public function setInput(InputInterface $input)
    {
        $this->input = $input;
    }
}
