<?php

namespace SecurityComponent\Interactor\Request;

use MyProject\Proxies\__CG__\stdClass;
use SecurityComponent\Interactor\Request;

class ProcessRunningChecklistRequest implements Request
{
    /** @var string */
    public $userId;

    /** @var string */
    public $id;

    /** @var array */
    public $tasks;

    public function __construct($userId, $id)
    {
        $this->userId = $userId;
        $this->id = $id;
        $this->tasks = array();
    }

    public function addCheckedTask($taskId)
    {
        $this->tasks[] = $taskId;
    }
}