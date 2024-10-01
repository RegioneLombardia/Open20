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

namespace Elasticsearch\ConnectionPool\Selectors;

use Elasticsearch\Connections\ConnectionInterface;

/**
 * Class RoundRobinSelector
 *
 */
class RoundRobinSelector implements SelectorInterface
{
    /**
     * @var int
     */
    private $current = 0;

    /**
     * Select the next connection in the sequence
     *
     * @param  ConnectionInterface[] $connections an array of ConnectionInterface instances to choose from
     *
     * @return \Elasticsearch\Connections\ConnectionInterface
     */
    public function select($connections)
    {
        $returnConnection = $connections[$this->current % count($connections)];

        $this->current += 1;

        return $returnConnection;
    }
}
