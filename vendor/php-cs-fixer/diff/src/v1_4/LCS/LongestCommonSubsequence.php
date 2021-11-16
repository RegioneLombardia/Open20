<?php
/*
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 */

namespace PhpCsFixer\Diff\v1_4\LCS;

/**
 * Interface for implementations of longest common subsequence calculation.
 */
interface LongestCommonSubsequence
{
    /**
     * Calculates the longest common subsequence of two arrays.
     *
     * @param array $from
     * @param array $to
     *
     * @return array
     */
    public function calculate(array $from, array $to);
}
