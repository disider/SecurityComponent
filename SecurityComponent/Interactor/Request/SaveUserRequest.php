<?php

namespace SecurityComponent\Interactor\Request;

use SecurityComponent\Interactor\Request;

class SaveUserRequest implements Request
{
    /** @var */
    public $userId;

    /** @var int */
    public $id;

    /** @var */
    public $companyId;

    /** @var string */
    public $email;

    /** @var string */
    public $password;

    /** @var array */
    public $extraFields = array();

    /** @var */
    private $salt;

    /** @var boolean */
    public $isActive;

    /** @var array */
    public $roles;

    public function __construct($userId, $id, $email, $password, $salt, $isActive, array $roles)
    {
        $this->userId = $userId;
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->salt = $salt;
        $this->isActive = $isActive;
        $this->roles = $roles;
    }

}