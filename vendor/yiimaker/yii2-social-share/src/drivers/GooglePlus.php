<?php
/**
 */

namespace ymaker\social\share\drivers;

use ymaker\social\share\base\AbstractDriver;

/**
 * Driver for Google Plus.
 *
 *
 * @since 1.0
 */
class GooglePlus extends AbstractDriver
{
    /**
     * {@inheritdoc}
     */
    protected function processShareData()
    {
        $this->url = static::encodeData($this->url);
    }

    /**
     * {@inheritdoc}
     */
    protected function buildLink()
    {
        return 'https://plusone.google.com/_/+1/confirm?hl=en&url={url}';
    }
}
