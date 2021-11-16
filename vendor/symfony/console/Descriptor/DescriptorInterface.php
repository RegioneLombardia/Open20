<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\Console\Descriptor;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Descriptor interface.
 *
 */
interface DescriptorInterface
{
    /**
     * Describes an object if supported.
     *
     * @param object $object
     */
    public function describe(OutputInterface $output, $object, array $options = []);
}
