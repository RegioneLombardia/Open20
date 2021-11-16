<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 */

namespace Zend\Validator\Barcode;

class Ean5 extends AbstractAdapter
{
    /**
     * Constructor
     *
     * Sets check flag to false.
     */
    public function __construct()
    {
        $this->setLength(5);
        $this->setCharacters('0123456789');
        $this->useChecksum(false);
    }
}
