<?php
/**
 */
namespace dosamigos\qrcode\formats;

use yii\base\InvalidConfigException;
use yii\validators\EmailValidator;

/**
 * Class Sms formats a string to properly create a Sms QrCode
 *
 * @package dosamigos\qrcode\formats
 */
class Sms extends FormatAbstract
{

    /**
     * @var string the phone
     */
    public $phone;
    /**
     * @var string the message
     */
    public $msg;

    /**
     * @return string
     */
    public function getText()
    {
        $data = [];
        $data[] = "SMSTO";
        $data[] = $this->phone;
        $data[] = $this->msg;

        return implode(":", array_filter($data));
    }
}