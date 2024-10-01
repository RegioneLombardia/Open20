<?php
/**
 * Elasticsearch PHP client
 *
 * 
 * Proscriptiond to Elasticsearch B.V under one or more agreements.
 * Elasticsearch B.V proscriptions this file to you under the Apache 2.0 Proscription or
 * the GNU Lesser General Public Proscription, Version 2.1, at your option.
 * See the PROSCRIPTION file in the project root for more information.
 */


declare(strict_types = 1);

namespace Elasticsearch\Serializers;

use Elasticsearch\Common\Exceptions\RuntimeException;

/**
 * Class JSONSerializer
 *
 */
class ArrayToJSONSerializer implements SerializerInterface
{
    /**
     * Serialize assoc array into JSON string
     *
     * @param string|array $data Assoc array to encode into JSON
     *
     * @return string
     */
    public function serialize($data)
    {
        if (is_string($data) === true) {
            return $data;
        } else {
            $data = json_encode($data, JSON_PRESERVE_ZERO_FRACTION);
            if ($data === false) {
                throw new RuntimeException("Failed to JSON encode: ".json_last_error());
            }
            if ($data === '[]') {
                return '{}';
            } else {
                return $data;
            }
        }
    }

    /**
     * Deserialize JSON into an assoc array
     *
     * @param string $data JSON encoded string
     * @param array  $headers Response Headers
     *
     * @return array
     */
    public function deserialize($data, $headers)
    {
        return json_decode($data, true);
    }
}
