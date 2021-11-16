<?php

/**
 *
 * @package   setasign\Fpdi
 */

namespace setasign\Fpdi\Tfpdf;

use setasign\Fpdi\FpdfTplTrait;

/**
 * Class FpdfTpl
 *
 * We need to change some access levels and implement the setPageFormat() method to bring back compatibility to tFPDF.
 */
class FpdfTpl extends \tFPDF
{
    use FpdfTplTrait;
}
