<?php
/**
 */

namespace ymaker\social\share\drivers;

use ymaker\social\share\base\AbstractDriver;

/**
 * Driver for LinkedIn.
 *
 *
 * @property bool|string $siteName
 *
 * @since 1.0
 */
class LinkedIn extends AbstractDriver
{
    /**
     * @var bool|string
     */
    public $siteName = false;


    /**
     * {@inheritdoc}
     */
    protected function processShareData()
    {
        $this->url = static::encodeData($this->url);
        $this->title = static::encodeData($this->title);
        $this->description = static::encodeData($this->description);

        if (\is_string($this->siteName)) {
            $this->appendToData('siteName', $this->siteName);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function buildLink()
    {
        $link = 'https://www.linkedin.com/shareArticle?mini=true'
                    . '&url={url}'
                    . '&title={title}'
                    . '&summary={description}';

        if ($this->siteName) {
            $this->addUrlParam($link, 'source', '{siteName}');
        }

        return $link;
    }
}
