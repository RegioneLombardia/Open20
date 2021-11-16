<?php
/**
 */
namespace dosamigos\qrcode\traits;


use yii\base\InvalidConfigException;
use yii\validators\EmailValidator;

/**
 * EmailTrait
 *
 * Provides methods to handle the email property
 *
 * @package dosamigos\qrcode\traits
 */
trait EmailTrait
{
    /**
     * @var string a valid email
     */
    protected $email;

    /**
     * @param string $value the email
     *
     * @throws InvalidConfigException
     */
    public function setEmail($value)
    {
        $error = null;
        $validator = new EmailValidator();
        if (!$validator->validate($value, $error)) {
            throw new InvalidConfigException($error);
        }

        $this->email = $value;
    }

    /**
     * @return string the email
     */
    public function getEmail()
    {
        return $this->email;
    }
}