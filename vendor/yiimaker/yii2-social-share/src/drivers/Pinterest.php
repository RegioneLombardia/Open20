<?php
/**
 */

namespace ymaker\social\share\drivers;

use ymaker\social\share\base\AbstractDriver;

/**
 * Driver for Pinterest.
 *
 *
 * @since 1.0
 */
class Pinterest extends AbstractDriver
{
    /**
     * {@inheritdoc}
     */
    protected function processShareData()
    {
        $this->url = static::encodeData($this->url);
        $this->imageUrl = static::encodeData($this->imageUrl);
        $this->description = static::encodeData($this->description);
    }

    /**
     * {@inheritdoc}
     */
    protected function buildLink()
    {
        return 'https://www.pinterest.com/pin/create/link/?'
            . 'url={url}'
            . '&media={imageUrl}'
            . '&description={description}';
    }
}
