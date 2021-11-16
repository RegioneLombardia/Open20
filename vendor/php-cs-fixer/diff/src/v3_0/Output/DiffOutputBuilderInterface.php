<?php
/*
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 */

namespace PhpCsFixer\Diff\v3_0\Output;

/**
 * Defines how an output builder should take a generated
 * diff array and return a string representation of that diff.
 */
interface DiffOutputBuilderInterface
{
    public function getDiff(array $diff);
}
