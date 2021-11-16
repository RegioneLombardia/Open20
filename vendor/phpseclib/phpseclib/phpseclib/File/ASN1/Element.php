<?php
/**
 * Pure-PHP ASN.1 Parser
 *
 * PHP version 5
 *
 * @category  File
 * @package   ASN1
 */

namespace phpseclib\File\ASN1;

/**
 * ASN.1 Element
 *
 * Bypass normal encoding rules in phpseclib\File\ASN1::encodeDER()
 *
 * @package ASN1
 * @access  public
 */
class Element
{
    /**
     * Raw element value
     *
     * @var string
     * @access private
     */
    var $element;

    /**
     * Constructor
     *
     * @param string $encoded
     * @return \phpseclib\File\ASN1\Element
     * @access public
     */
    function __construct($encoded)
    {
        $this->element = $encoded;
    }
}
