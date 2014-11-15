<?php

namespace SecurityComponent\Interactor\Request;

use SecurityComponent\Interactor\Request;

class DeleteShareRequestRequest implements Request
{
    /** @var int */
    public $userId;

    /** @var string */
    public $token;

    public function __construct($userId, $token)
    {
        $this->userId = $userId;
        $this->token = $token;
    }
} 