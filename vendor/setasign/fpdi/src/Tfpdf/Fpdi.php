<?php

/**
 *
 * @package   setasign\Fpdi
 */

namespace setasign\Fpdi\Tfpdf;

use setasign\Fpdi\FpdfTrait;
use setasign\Fpdi\FpdiTrait;

/**
 * Class Fpdi
 *
 * This class let you import pages of existing PDF documents into a reusable structure for tFPDF.
 */
class Fpdi extends FpdfTpl
{
    use FpdiTrait;
    use FpdfTrait;

    /**
     * FPDI version
     *
     * @string
     */
    const VERSION = '2.6.0';
}
