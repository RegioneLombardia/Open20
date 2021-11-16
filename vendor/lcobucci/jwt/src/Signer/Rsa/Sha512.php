<?php
/**
 *
 */

namespace Lcobucci\JWT\Signer\Rsa;

use Lcobucci\JWT\Signer\Rsa;

/**
 * Signer for RSA SHA-512
 *
 * @since 2.1.0
 */
class Sha512 extends Rsa
{
    /**
     * {@inheritdoc}
     */
    public function getAlgorithmId()
    {
        return 'RS512';
    }

    /**
     * {@inheritdoc}
     */
    public function getAlgorithm()
    {
        return OPENSSL_ALGO_SHA512;
    }
}
