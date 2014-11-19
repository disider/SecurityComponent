<?php

namespace Diside\SecurityComponent\Interactor\Request;

use Diside\SecurityComponent\Interactor\Request;

class DeleteUserRequest implements Request
{
    /** @var int */
    public $userId;

    /** @var int */
    public $id;

    public function __construct($userId, $id)
    {
        $this->userId = $userId;
        $this->id = $id;
    }
} 