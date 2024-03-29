<?php
/**
 * Created by PhpStorm.
 * User: etudiant
 * Date: 01/10/18
 * Time: 14:56
 */

namespace Tests\DockerManagerBundle\WebSocketServer\Mocks;

use Ratchet\ConnectionInterface;

class ConnectionInterfaceMock implements ConnectionInterface
{
    protected $sentData = [];

    protected $closed = false;

    public $resourceId = 0;

    /**
     * ConnectionInterfaceMock constructor.
     * @param int $resourceId
     */
    public function __construct(int $resourceId = 0)
    {
        $this->resourceId = $resourceId;
    }


    /**
     * Send data to the connection
     * @param  string $data
     * @return \Ratchet\ConnectionInterface
     */
    public function send($data)
    {
        $this->sentData[] = $data;
        return $this;
    }

    /**
     * Close the connection
     */
    public function close()
    {
        $this->closed = true;
    }
}
