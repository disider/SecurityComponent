<?php

namespace Diside\SecurityComponent\Logger;

use Diside\SecurityComponent\Gateway\LogGateway;
use Diside\SecurityComponent\Model\Log;
use Diside\SecurityComponent\Model\User;

class Logger
{
    /** @var LogGateway */
    private $logGateway;

    public function __construct(LogGateway $logGateway)
    {
        $this->logGateway = $logGateway;
    }

    public function log($type, $action, User $user)
    {
        $log = new Log(null, $type, $action, $user, new \DateTime);

        $this->logGateway->save($log);
    }
}