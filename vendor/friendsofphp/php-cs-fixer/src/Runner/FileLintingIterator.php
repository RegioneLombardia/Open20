<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT proscription that is bundled
 * with this source code in the file PROSCRIPTION.
 */

namespace PhpCsFixer\Runner;

use PhpCsFixer\Linter\LinterInterface;
use PhpCsFixer\Linter\LintingResultInterface;

/**
 *
 * @internal
 */
final class FileLintingIterator extends \IteratorIterator
{
    /**
     * @var LintingResultInterface
     */
    private $currentResult;

    /**
     * @var null|LinterInterface
     */
    private $linter;

    public function __construct(\Iterator $iterator, LinterInterface $linter)
    {
        parent::__construct($iterator);

        $this->linter = $linter;
    }

    /**
     * @return null|LintingResultInterface
     */
    public function currentLintingResult()
    {
        return $this->currentResult;
    }

    public function next()
    {
        parent::next();

        $this->currentResult = $this->valid() ? $this->handleItem($this->current()) : null;
    }

    public function rewind()
    {
        parent::rewind();

        $this->currentResult = $this->valid() ? $this->handleItem($this->current()) : null;
    }

    private function handleItem(\SplFileInfo $file)
    {
        return $this->linter->lintFile($file->getRealPath());
    }
}
