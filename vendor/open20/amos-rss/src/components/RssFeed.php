<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\rss\components
 * @category   CategoryName
 */

namespace amos\rss\components;

use yii\base\Component;
use Zend\Feed\Reader\Reader;
use Zend\Feed\Writer\Feed;

/**
 * Class RssFeed
 * @package amos\rss\components
 */
class RssFeed extends Component
{
    /**
     * Loads read Zend-feed component
     * @return mixed object Zend\Feed\Reader component
     */
    public function reader()
    {
        return new Reader;
    }

    /**
     * Loads read Zend-feed component
     * @return mixed object Zend\Feed\Writer component
     */
    public function writer()
    {
        return new Feed;
    }
}
