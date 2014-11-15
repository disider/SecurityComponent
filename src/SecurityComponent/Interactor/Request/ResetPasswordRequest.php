<?php

namespace SecurityComponent\Interactor\Request;

use SecurityComponent\Interactor\Request;

class ResetPasswordRequest implements Request
{
    /** @var string */
    public $userId;

    /** @var string */
    public $password;

    public function __construct($userId, $password)
    {
        $this->userId = $userId;
        $this->password = $password;
    }

}