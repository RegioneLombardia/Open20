<?php
/**
 */
namespace dosamigos\qrcode\formats;

use dosamigos\qrcode\traits\EmailTrait;
use yii\base\InvalidConfigException;
use yii\validators\EmailValidator;

/**
 * Class MailTo formats a string to properly create a MailID QrCode
 *
 * @package dosamigos\qrcode\formats
 */
class MailTo extends FormatAbstract
{
    use EmailTrait;

    /**
     * @inheritdoc
     */
    public function getText()
    {
        return "MAILTO:{$this->email}";
    }
}