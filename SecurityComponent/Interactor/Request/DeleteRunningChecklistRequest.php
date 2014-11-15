<?php

namespace SecurityComponent\Interactor\Request;

use SecurityComponent\Interactor\Request;

class DeleteRunningChecklistRequest implements Request
{
    public $userId;
    public $id;

    public function __construct($userId, $id)
    {
        $this->userId = $userId;
        $this->id = $id;
    }
} 