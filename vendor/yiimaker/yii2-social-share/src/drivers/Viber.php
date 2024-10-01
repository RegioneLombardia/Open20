<?php
/**
 */

namespace ymaker\social\share\drivers;

use ymaker\social\share\base\AbstractDriver;

/**
 * Driver for Viber messenger.
 *
 *
 * WARNING: This driver works only in mobile devices
 * with installed Viber client.
 *
 * @since 1.0
 */
class Viber extends AbstractDriver
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
        return 'viber://forward?text={url}';
    }
}
