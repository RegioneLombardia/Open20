<?php

/**
 *
 * @package   setasign\Fpdi
 */

namespace setasign\Fpdi\PdfParser\Filter;

use setasign\Fpdi\PdfParser\PdfParserException;

/**
 * Exception for filters
 */
class FilterException extends PdfParserException
{
    const UNSUPPORTED_FILTER = 0x0201;

    const NOT_IMPLEMENTED = 0x0202;
}
