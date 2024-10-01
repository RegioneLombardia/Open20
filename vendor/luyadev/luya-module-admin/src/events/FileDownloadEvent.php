<?php

namespace luya\admin\events;

/**
 * File Download Event.
 *
 * @since 1.0.0
 */
class FileDownloadEvent extends \yii\base\Event
{
    /**
     * {@inheritDoc}
     */
    public $isValid = true;
    
    /**
     * @var \luya\admin\file\Item $file The file to handle
     */
    public $file;
}
