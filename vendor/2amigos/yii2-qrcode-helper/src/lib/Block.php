<?php
/**
 */
namespace dosamigos\qrcode\lib;

/**
 * Class Block
 *
 * Based on libqrencode C library distributed under LGPL 2.1
 * Copyleft (C) 2006, 2007, 2008, 2009 Kentaro Fukuchi <fukuchi@megaui.net>
 *
 * @package dosamigos\qrcode\lib
 */
class Block
{
    /**
     * @var int the data length
     */
    public $dataLength;
    /**
     * @var array code block data
     */
    public $data = [];
    /**
     * @var int ecc length
     */
    public $eccLength;
    /**
     * @var array ecc
     */
    public $ecc = [];

    /**
     * @param int $dl data length
     * @param array $data the data of the code block
     * @param int $el length of the ecc
     * @param array $ecc specs
     * @param RsItem $rs
     *
     */
    public function __construct($dl, $data, $el, $ecc, RsItem $rs)
    {
        $rs->encodeRsChar($data, $ecc);

        $this->dataLength = $dl;
        $this->data = $data;
        $this->eccLength = $el;
        $this->ecc = $ecc;
    }
}