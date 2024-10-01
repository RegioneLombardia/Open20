<?php
/**
 *
 */

namespace Lcobucci\JWT;

use JsonSerializable;

/**
 * Basic interface for token claims
 *
 * @since 2.0.0
 */
interface Claim extends JsonSerializable
{
    /**
     * Returns the claim name
     *
     * @return string
     */
    public function getName();

    /**
     * Returns the claim value
     *
     * @return string
     */
    public function getValue();

    /**
     * Returns the string representation of the claim
     *
     * @return string
     */
    public function __toString();
}
