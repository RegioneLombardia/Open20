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

namespace Elasticsearch\Endpoints;

use Elasticsearch\Common\Exceptions\RuntimeException;
use Elasticsearch\Endpoints\AbstractEndpoint;

/**
 * Class Update
 * Elasticsearch API name update
 *
 * NOTE: this file is autogenerated using util/GenerateEndpoints.php
 * and Elasticsearch 6.8.16 (1f62092)
 */
class Update extends AbstractEndpoint
{

    public function getURI(): string
    {
        if (isset($this->id) !== true) {
            throw new RuntimeException(
                'id is required for Update'
            );
        }
        $id = $this->id;
        if (isset($this->index) !== true) {
            throw new RuntimeException(
                'index is required for Update'
            );
        }
        $index = $this->index;
        if (isset($this->type) !== true) {
            throw new RuntimeException(
                'type is required for Update'
            );
        }
        $type = $this->type;

        return "/$index/$type/$id/_update";
    }

    public function getParamWhitelist(): array
    {
        return [
            'wait_for_active_shards',
            'fields',
            '_source',
            '_source_excludes',
            '_source_includes',
            'lang',
            'parent',
            'refresh',
            'retry_on_conflict',
            'routing',
            'timeout',
            'if_seq_no',
            'if_primary_term',
            'version',
            'version_type'
        ];
    }

    public function getMethod(): string
    {
        return 'POST';
    }

    public function setBody($body): Update
    {
        if (isset($body) !== true) {
            return $this;
        }
        $this->body = $body;

        return $this;
    }
}