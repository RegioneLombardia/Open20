<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 */

namespace Zend\Feed\PubSubHubbub\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\TableGatewayInterface;

class AbstractModel
{
    /**
     * Zend\Db\TableGateway\TableGatewayInterface instance to host database methods
     *
     * @var TableGatewayInterface
     */
    protected $db = null;

    /**
     * Constructor
     *
     * @param null|TableGatewayInterface $tableGateway
     */
    public function __construct(TableGatewayInterface $tableGateway = null)
    {
        if ($tableGateway === null) {
            $parts = explode('\\', get_class($this));
            $table = strtolower(array_pop($parts));
            $this->db = new TableGateway($table, null);
        } else {
            $this->db = $tableGateway;
        }
    }
}
