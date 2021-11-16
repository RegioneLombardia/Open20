<?php
/**
 *
 */

namespace Lcobucci\JWT\Claim;

use Lcobucci\JWT\ValidationData;

/**
 * Basic interface for validatable token claims
 *
 * @since 2.0.0
 */
interface Validatable
{
    /**
     * Returns if claim is valid according with given data
     *
     * @param ValidationData $data
     *
     * @return boolean
     */
    public function validate(ValidationData $data);
}
