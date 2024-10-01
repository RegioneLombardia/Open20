<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\Console\Completion\Output;

use Symfony\Component\Console\Completion\CompletionSuggestions;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Transforms the {@see CompletionSuggestions} object into output readable by the shell completion.
 *
 */
interface CompletionOutputInterface
{
    public function write(CompletionSuggestions $suggestions, OutputInterface $output): void;
}
