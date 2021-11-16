<?php
/**
 */

namespace yii\web;

use yii\base\Exception;

/**
 * HeadersAlreadySentException represents an exception caused by
 * any headers that were already sent before web response was sent.
 *
 * @since 2.0.14
 */
class HeadersAlreadySentException extends Exception
{
    /**
     * {@inheritdoc}
     */
    public function __construct($file, $line)
    {
        $message = YII_DEBUG ? "Headers already sent in {$file} on line {$line}." : 'Headers already sent.';
        parent::__construct($message);
    }
}
