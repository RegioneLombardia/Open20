<?php
/**
 *
 */

namespace Lcobucci\JWT\Signer\Rsa;

use Lcobucci\JWT\Signer\Rsa;

/**
 * Signer for RSA SHA-256
 *
 * @since 2.1.0
 */
class Sha256 extends Rsa
{
    /**
     * {@inheritdoc}
     */
    public function getAlgorithmId()
    {
        return 'RS256';
    }

    /**
     * {@inheritdoc}
     */
    public function getAlgorithm()
    {
        return OPENSSL_ALGO_SHA256;
    }
}
