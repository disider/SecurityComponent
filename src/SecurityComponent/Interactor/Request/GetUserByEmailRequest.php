<?php

namespace SecurityComponent\Interactor\Request;

use SecurityComponent\Interactor\Request;

class GetUserByEmailRequest implements Request
{
    /** @var string */
    public $email;

    public function __construct($email)
    {
        $this->email = $email;
    }
} 