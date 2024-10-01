<?php declare(strict_types=1);
/* (c) Anton Medvedev <anton@medv.io>
 *
 */

namespace Deployer\Console\Input;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

final class Argument
{
    public static function toString(
        InputInterface $input,
        InputArgument $argument
    ): string {
        return $input->getArgument($argument->getName());
    }
}
