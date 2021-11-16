<?php
/**
 *
 */

namespace Lcobucci\JWT\Signer\Ecdsa;

use Lcobucci\JWT\Signer\Ecdsa;

/**
 * Signer for ECDSA SHA-256
 *
 * @since 2.1.0
 */
class Sha256 extends Ecdsa
{
    /**
     * {@inheritdoc}
     */
    public function getAlgorithmId()
    {
        return 'ES256';
    }

    /**
     * {@inheritdoc}
     */
    public function getAlgorithm()
    {
        return 'sha256';
    }

    /**
     * {@inheritdoc}
     */
    public function getSignatureLength()
    {
        return 64;
    }
}
