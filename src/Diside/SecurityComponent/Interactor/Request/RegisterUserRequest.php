<?php

namespace Diside\SecurityComponent\Interactor\Request;

use Diside\SecurityComponent\Interactor\Request;

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