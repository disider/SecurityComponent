<?php

namespace SecurityComponent\Interactor\Request;

use SecurityComponent\Interactor\Request;

class RegisterUserRequest implements Request
{
    /** @var string */
    public $email;

    /** @var string */
    public $password;

    /** @var string */
    public $salt;

    public function __construct($email, $password, $salt)
    {
        $this->email = $email;
        $this->password = $password;
        $this->salt = $salt;
    }

}