<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 */

namespace Zend\Validator\Barcode;

class Upca extends AbstractAdapter
{
    /**
     * Constructor for this barcode adapter
     */
    public function __construct()
    {
        $this->setLength(12);
        $this->setCharacters('0123456789');
        $this->setChecksum('gtin');
    }
}
