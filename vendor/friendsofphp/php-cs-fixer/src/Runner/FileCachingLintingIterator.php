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
final class FileCachingLintingIterator extends \CachingIterator
{
    /**
     * @var LintingResultInterface
     */
    private $currentResult;

    /**
     * @var LinterInterface
     */
    private $linter;

    /**
     * @var LintingResultInterface
     */
    private $nextResult;

    public function __construct(\Iterator $iterator, LinterInterface $linter)
    {
        parent::__construct($iterator);

        $this->linter = $linter;
    }

    public function currentLintingResult()
    {
        return $this->currentResult;
    }

    public function next()
    {
        parent::next();

        $this->currentResult = $this->nextResult;

        if ($this->hasNext()) {
            $this->nextResult = $this->handleItem($this->getInnerIterator()->current());
        }
    }

    public function rewind()
    {
        parent::rewind();

        if ($this->valid()) {
            $this->currentResult = $this->handleItem($this->current());
        }

        if ($this->hasNext()) {
            $this->nextResult = $this->handleItem($this->getInnerIterator()->current());
        }
    }

    private function handleItem(\SplFileInfo $file)
    {
        return $this->linter->lintFile($file->getRealPath());
    }
}
