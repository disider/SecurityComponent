<?php

namespace SecurityComponent\Interactor\Request;

use MyProject\Proxies\__CG__\stdClass;
use SecurityComponent\Interactor\Request;

class SaveRunningChecklistRequest implements Request
{
    /** @var string */
    public $userId;

    /** @var string */
    public $id;

    /** @var */
    public $ownerId;

    /** @var string */
    public $subtitle;

    /** @var array */
    public $assignees;

    public function __construct($userId, $id, $ownerId, $subtitle)
    {
        $this->userId = $userId;
        $this->id = $id;
        $this->ownerId = $ownerId;
        $this->subtitle = $subtitle;
        $this->assignees = array();
    }

    public function addAssignee($userId)
    {
        $this->assignees[] = $userId;
    }
}