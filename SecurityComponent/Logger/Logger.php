<?php

namespace SecurityComponent\Logger;

use SecurityComponent\Gateway\LogGateway;
use SecurityComponent\Model\Log;
use SecurityComponent\Model\User;

class Logger
{
    const RUNNING_CHECKLIST_SAVED = 'running_checklist_saved';
    const RUNNING_TASK_STATUS_CHANGED = 'running_task_status_changed';

    /** @var LogGateway */
    private $logGateway;

    public function __construct(LogGateway $logGateway)
    {
        $this->logGateway = $logGateway;
    }

    public static function getActions()
    {
        return array(
            self::RUNNING_CHECKLIST_SAVED,
            self::RUNNING_TASK_STATUS_CHANGED
        );
    }

    public function log($type, $action, User $user)
    {
        $log = new Log(null, $type, $action, $user, new \DateTime);

        $this->logGateway->save($log);
    }
}