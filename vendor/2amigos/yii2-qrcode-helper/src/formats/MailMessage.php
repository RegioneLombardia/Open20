<?php
/**
 */
namespace dosamigos\qrcode\formats;

use dosamigos\qrcode\traits\EmailTrait;

/**
 * Class MailMessage formats a string to properly create a NNTMail QrCode
 *
 * @package dosamigos\qrcode\formats
 */
class MailMessage extends FormatAbstract
{
    use EmailTrait;

    /**
     * @var string the subject
     */
    public $subject;
    /**
     * @var string the body of the mail message
     */
    public $body;

    /**
     * @inheritdoc
     */
    public function getText()
    {
        return "MATMSG:TO:{$this->email};SUB:{$this->subject};BODY:{$this->body};;";
    }
}