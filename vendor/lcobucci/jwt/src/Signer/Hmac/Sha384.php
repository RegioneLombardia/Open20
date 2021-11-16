<?php
/**
 *
 */

namespace Lcobucci\JWT\Signer\Hmac;

use Lcobucci\JWT\Signer\Hmac;

/**
 * Signer for HMAC SHA-384
 *
 * @since 0.1.0
 */
class Sha384 extends Hmac
{
    /**
     * {@inheritdoc}
     */
    public function getAlgorithmId()
    {
        return 'HS384';
    }

    /**
     * {@inheritdoc}
     */
    public function getAlgorithm()
    {
        return 'sha384';
    }
}
