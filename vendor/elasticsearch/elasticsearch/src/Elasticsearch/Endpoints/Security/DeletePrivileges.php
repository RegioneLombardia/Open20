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

namespace Elasticsearch\Endpoints\Security;

use Elasticsearch\Common\Exceptions\RuntimeException;
use Elasticsearch\Endpoints\AbstractEndpoint;

/**
 * Class DeletePrivileges
 * Elasticsearch API name xpack.security.delete_privileges
 *
 * NOTE: this file is autogenerated using util/GenerateEndpoints.php
 * and Elasticsearch 6.8.16 (1f62092)
 */
class DeletePrivileges extends AbstractEndpoint
{
    protected $application;
    protected $name;

    public function getURI(): string
    {
        if (isset($this->application) !== true) {
            throw new RuntimeException(
                'application is required for Delete_privileges'
            );
        }
        $application = $this->application;
        if (isset($this->name) !== true) {
            throw new RuntimeException(
                'name is required for Delete_privileges'
            );
        }
        $name = $this->name;

        return "/_xpack/security/privilege/$application/$name";
    }

    public function getParamWhitelist(): array
    {
        return [
            'refresh'
        ];
    }

    public function getMethod(): string
    {
        return 'DELETE';
    }

    public function setApplication($application): DeletePrivileges
    {
        if (isset($application) !== true) {
            return $this;
        }
        $this->application = $application;

        return $this;
    }

    public function setName($name): DeletePrivileges
    {
        if (isset($name) !== true) {
            return $this;
        }
        $this->name = $name;

        return $this;
    }
}