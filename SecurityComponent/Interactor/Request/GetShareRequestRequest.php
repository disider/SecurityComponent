<?php

namespace SecurityComponent\Interactor\Request;

use SecurityComponent\Interactor\Request;

class GetShareRequestRequest implements Request
{
    /** @var */
    public $userId;

    /** @var int */
    public $token;

    public function __construct($userId, $token)
    {
        $this->userId = $userId;
        $this->token = $token;
    }
} 