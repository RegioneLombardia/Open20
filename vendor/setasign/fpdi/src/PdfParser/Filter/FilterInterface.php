<?php

/**
 *
 * @package   setasign\Fpdi
 */

namespace setasign\Fpdi\PdfParser\Filter;

/**
 * Interface for filters
 */
interface FilterInterface
{
    /**
     * Decode a string.
     *
     * @param string $data The input string
     * @return string
     */
    public function decode($data);
}
