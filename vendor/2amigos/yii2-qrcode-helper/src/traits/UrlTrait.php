<?php
/**
 */
namespace dosamigos\qrcode\traits;


use yii\base\InvalidConfigException;
use yii\validators\UrlValidator;

trait UrlTrait
{
    /**
     * @var string a valid URL
     */
    protected $url;

    /**
     * @param string $value the URL
     *
     * @throws InvalidConfigException
     */
    public function setUrl($value)
    {
        $error = null;
        $validator = new UrlValidator();
        if (!$validator->validate($value, $error)) {
            throw new InvalidConfigException($error);
        }

        $this->url = $value;
    }

    /**
     * @return string the URL
     */
    public function getUrl() {
        return $this->url;
    }
}