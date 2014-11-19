<?php

namespace Diside\SecurityComponent\Interactor\Request;

use Diside\SecurityComponent\Interactor\Request;

class GetUserByEmailRequest implements Request
{
    /** @var string */
    public $email;

    public function __construct($email)
    {
        $this->email = $email;
    }
} 