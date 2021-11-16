<?php
/**
 *
 */

namespace Lcobucci\JWT\Signer\Rsa;

use Lcobucci\JWT\Signer\Rsa;

/**
 * Signer for RSA SHA-384
 *
 * @since 2.1.0
 */
class Sha384 extends Rsa
{
    /**
     * {@inheritdoc}
     */
    public function getAlgorithmId()
    {
        return 'RS384';
    }

    /**
     * {@inheritdoc}
     */
    public function getAlgorithm()
    {
        return OPENSSL_ALGO_SHA384;
    }
}
