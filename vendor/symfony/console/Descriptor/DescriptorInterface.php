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
    public function describe(OutputInterface $output, object $object, array $options = []);
}
