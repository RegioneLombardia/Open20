<?php
/**
 */

namespace yii\authclient\signature;

/**
 * PlainText represents 'PLAINTEXT' signature method.
 *
 * @since 2.0
 */
class PlainText extends BaseMethod
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'PLAINTEXT';
    }

    /**
     * {@inheritdoc}
     */
    public function generateSignature($baseString, $key)
    {
        return $key;
    }
}
