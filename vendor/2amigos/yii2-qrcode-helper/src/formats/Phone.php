<?php
/**
 */
namespace dosamigos\qrcode\formats;

use yii\base\InvalidConfigException;
use yii\validators\EmailValidator;

/**
 * Class Phone formats a string to properly create a Phone QrCode
 *
 * @package dosamigos\qrcode\formats
 */
class Phone extends FormatAbstract
{

    /**
     * @var string the phone
     */
    public $phone;

    /**
     * @return string
     */
    public function getText()
    {
        return "TEL:{$this->phone}";
    }
}