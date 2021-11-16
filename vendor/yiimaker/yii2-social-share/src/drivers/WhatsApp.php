<?php
/**
 */

namespace ymaker\social\share\drivers;

use ymaker\social\share\base\AbstractDriver;

/**
 * Driver for WhatsApp messenger.
 *
 *
 * WARNING: This driver works only in mobile devices
 * with installed WhatsApp client.
 *
 * @since 1.0
 */
class WhatsApp extends AbstractDriver
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
        return 'whatsapp://send?text={url}';
    }
}
