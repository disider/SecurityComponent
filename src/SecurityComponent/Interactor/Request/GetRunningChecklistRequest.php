<?php

namespace SecurityComponent\Interactor\Request;

use SecurityComponent\Interactor\Request;

class GetRunningChecklistRequest implements Request
{
    /** @var int */
    public $userId;

    /** @var int */
    public $id;

    public function __construct($userId, $id)
    {
        $this->userId = $userId;
        $this->id = $id;
    }
} 