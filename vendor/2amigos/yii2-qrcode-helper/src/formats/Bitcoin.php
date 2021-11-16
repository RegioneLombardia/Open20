<?php
/**
 */
namespace dosamigos\qrcode\formats;

/**
 * Class Bitcoin formats a string to properly create a Bitcoin URI
 *
 * @package dosamigos\qrcode\formats
 */
class Bitcoin extends FormatAbstract
{
    /**
     * @var string the Bitcoin address
     */
    public $address;

    /**
     * @var string the payable amount
     */
    public $amount;

    /**
     * @inheritdoc
     */
    public function getText()
    {
        return "bitcoin:{$this->address}?amount={$this->amount}";
    }
}
