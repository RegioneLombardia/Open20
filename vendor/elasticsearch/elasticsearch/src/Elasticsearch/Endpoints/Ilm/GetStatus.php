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

namespace Elasticsearch\Endpoints\Ilm;

use Elasticsearch\Endpoints\AbstractEndpoint;

/**
 * Class GetStatus
 * Elasticsearch API name ilm.get_status
 *
 * NOTE: this file is autogenerated using util/GenerateEndpoints.php
 * and Elasticsearch 6.8.16 (1f62092)
 */
class GetStatus extends AbstractEndpoint
{

    public function getURI(): string
    {

        return "/_ilm/status";
    }

    public function getParamWhitelist(): array
    {
        return [
            
        ];
    }

    public function getMethod(): string
    {
        return 'GET';
    }
}
