<?php

namespace SecurityComponent\Interactor\Request;

use SecurityComponent\Interactor\Request;

class ChangePasswordRequest implements Request
{
    /** @var string */
    public $userId;

    /** @var string */
    public $id;

    /** @var string */
    public $currentPassword;

    /** @var string */
    public $newPassword;

    public function __construct($userId, $id, $currentPassword, $newPassword)
    {
        $this->userId = $userId;
        $this->id = $id;
        $this->currentPassword = $currentPassword;
        $this->newPassword = $newPassword;
    }

}