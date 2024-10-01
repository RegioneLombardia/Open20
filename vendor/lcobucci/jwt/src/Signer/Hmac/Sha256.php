<?php
/**
 *
 */

namespace Lcobucci\JWT\Signer\Hmac;

use Lcobucci\JWT\Signer\Hmac;

/**
 * Signer for HMAC SHA-256
 *
 * @since 0.1.0
 */
class Sha256 extends Hmac
{
    /**
     * {@inheritdoc}
     */
    public function getAlgorithmId()
    {
        return 'HS256';
    }

    /**
     * {@inheritdoc}
     */
    public function getAlgorithm()
    {
        return 'sha256';
    }
}
