<?php
/**
 */

namespace ymaker\social\share\drivers;

use ymaker\social\share\base\AbstractMailDriver;

/**
 * Driver for Yahoo.
 *
 *
 * @since 2.0
 */
class Yahoo extends AbstractMailDriver
{
    /**
     * {@inheritdoc}
     */
    protected function buildLink()
    {
        return 'https://compose.mail.yahoo.com/?subject={title}&body={body}';
    }
}
