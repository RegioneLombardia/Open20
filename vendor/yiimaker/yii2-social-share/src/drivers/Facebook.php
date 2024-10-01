<?php
/**
 */

namespace ymaker\social\share\drivers;

use ymaker\social\share\base\AbstractDriver;

/**
 * Driver for Facebook.
 *
 *
 * @since 1.0
 */
class Facebook extends AbstractDriver
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
        return 'http://www.facebook.com/sharer.php?u={url}';
    }

    /**
     * @return array
     */
    protected function getMetaTags()
    {
        return [
            ['property' => 'og:url',         'content' => '{url}'],
            ['property' => 'og:type',        'content' => 'website'],
            ['property' => 'og:title',       'content' => '{title}'],
            ['property' => 'og:description', 'content' => '{description}'],
            ['property' => 'og:image',       'content' => '{imageUrl}'],
        ];
    }
}
