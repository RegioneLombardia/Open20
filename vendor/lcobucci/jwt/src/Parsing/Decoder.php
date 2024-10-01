<?php
/**
 *
 */

namespace Lcobucci\JWT\Parsing;

use RuntimeException;

/**
 * Class that decodes data according with the specs of RFC-4648
 *
 * @since 0.1.0
 *
 */
class Decoder
{
    /**
     * Decodes from JSON, validating the errors (will return an associative array
     * instead of objects)
     *
     * @param string $json
     * @return mixed
     *
     * @throws RuntimeException When something goes wrong while decoding
     */
    public function jsonDecode($json)
    {
        $data = json_decode($json);

        if (json_last_error() != JSON_ERROR_NONE) {
            throw new RuntimeException('Error while decoding to JSON: ' . json_last_error_msg());
        }

        return $data;
    }

    /**
     * Decodes from base64url
     *
     * @param string $data
     * @return string
     */
    public function base64UrlDecode($data)
    {
        if ($remainder = strlen($data) % 4) {
            $data .= str_repeat('=', 4 - $remainder);
        }

        return base64_decode(strtr($data, '-_', '+/'));
    }
}
