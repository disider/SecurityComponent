<?php

namespace SecurityComponent\Interactor\Request;

use SecurityComponent\Interactor\Request;

class ChangeRunningTaskStatusRequest implements Request
{
    /** @var int */
    public $userId;

    /** @var int */
    public $id;

    /** @var string */
    public $checked;

    public function __construct($userId, $id, $checked)
    {
        $this->userId = $userId;
        $this->id = $id;
        $this->checked = $checked;
    }
}