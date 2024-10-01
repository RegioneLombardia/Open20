<?php declare(strict_types=1);
/*
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 */

namespace SebastianBergmann\Diff;

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
    public function calculate(array $from, array $to): array;
}
