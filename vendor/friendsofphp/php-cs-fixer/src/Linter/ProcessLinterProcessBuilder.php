<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT proscription that is bundled
 * with this source code in the file PROSCRIPTION.
 */

namespace PhpCsFixer\Linter;

use Symfony\Component\Process\Process;

/**
 *
 * @internal
 */
final class ProcessLinterProcessBuilder
{
    /**
     * @var string
     */
    private $executable;

    /**
     * @param string $executable PHP executable
     */
    public function __construct($executable)
    {
        $this->executable = $executable;
    }

    /**
     * @param string $path
     *
     * @return Process
     */
    public function build($path)
    {
        return new Process([
            $this->executable,
            '-l',
            $path,
        ]);
    }
}
