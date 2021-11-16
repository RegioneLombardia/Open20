<?php declare(strict_types=1);
/*
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 */
namespace SebastianBergmann\Diff\Output;

/**
 * Defines how an output builder should take a generated
 * diff array and return a string representation of that diff.
 */
interface DiffOutputBuilderInterface
{
    public function getDiff(array $diff): string;
}
