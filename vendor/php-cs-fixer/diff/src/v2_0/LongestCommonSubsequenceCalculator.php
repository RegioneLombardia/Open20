<?php
/*
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 */

namespace PhpCsFixer\Diff\v2_0;

interface LongestCommonSubsequenceCalculator
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
