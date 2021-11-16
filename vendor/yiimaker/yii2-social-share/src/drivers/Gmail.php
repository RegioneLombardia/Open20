<?php
/**
 */

namespace ymaker\social\share\drivers;

use ymaker\social\share\base\AbstractMailDriver;

/**
 * Driver for Gmail.
 *
 *
 * @since 1.0
 */
class Gmail extends AbstractMailDriver
{
    /**
     * {@inheritdoc}
     */
    protected function buildLink()
    {
        return 'https://mail.google.com/mail/?view=cm&fs=1&su={title}&body={body}';
    }
}
