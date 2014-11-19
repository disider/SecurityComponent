<?php

namespace Diside\SecurityComponent\Interactor\Request;

use Diside\SecurityComponent\Interactor\Request;

class ConfirmUserRegistrationRequest implements Request
{
    /** @var string */
    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }
} 