<?php

namespace SecurityComponent\Interactor\Request;

use SecurityComponent\Interactor\Request;

class GetUserByResetPasswordTokenRequest implements Request
{
    /** @var string */
    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }
} 