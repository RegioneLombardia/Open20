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

namespace Elasticsearch\Endpoints\Indices;

use Elasticsearch\Common\Exceptions\RuntimeException;
use Elasticsearch\Endpoints\AbstractEndpoint;

/**
 * Class GetFieldMapping
 * Elasticsearch API name indices.get_field_mapping
 *
 * NOTE: this file is autogenerated using util/GenerateEndpoints.php
 * and Elasticsearch 6.8.16 (1f62092)
 */
class GetFieldMapping extends AbstractEndpoint
{
    protected $fields;

    public function getURI(): string
    {
        if (isset($this->fields) !== true) {
            throw new RuntimeException(
                'fields is required for Get_field_mapping'
            );
        }
        $fields = $this->fields;
        $index = $this->index ?? null;
        $type = $this->type ?? null;

        if (isset($index) && isset($type)) {
            return "/$index/_mapping/$type/field/$fields";
        }
        if (isset($index)) {
            return "/$index/_mapping/field/$fields";
        }
        if (isset($type)) {
            return "/_mapping/$type/field/$fields";
        }
        return "/_mapping/field/$fields";
    }

    public function getParamWhitelist(): array
    {
        return [
            'include_type_name',
            'include_defaults',
            'ignore_unavailable',
            'allow_no_indices',
            'expand_wildcards',
            'local'
        ];
    }

    public function getMethod(): string
    {
        return 'GET';
    }

    public function setFields($fields): GetFieldMapping
    {
        if (isset($fields) !== true) {
            return $this;
        }
        if (is_array($fields) === true) {
            $fields = implode(",", $fields);
        }
        $this->fields = $fields;

        return $this;
    }
}