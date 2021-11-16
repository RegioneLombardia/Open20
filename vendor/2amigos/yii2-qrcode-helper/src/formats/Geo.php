<?php
/**
 */
namespace dosamigos\qrcode\formats;

use yii\base\InvalidConfigException;
use yii\validators\EmailValidator;

/**
 * Class Geo formats a string to properly create a Geo QrCode
 *
 * @package dosamigos\qrcode\formats
 */
class Geo extends FormatAbstract
{
    public $lat;
    public $lng;
    public $altitude;

    /**
     * @inheritdoc
     */
    public function getText()
    {
        return "GEO:{$this->lat},{$this->lng},{$this->altitude}";
    }
}