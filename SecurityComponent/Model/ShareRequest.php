<?php

namespace SecurityComponent\Model;

use SecurityComponent\Exception\InvalidEmailException;

class ShareRequest
{
    /** @var int */
    private $id;

    /** @var string */
    private $token;

    /** @var ChecklistTemplate */
    private $checklistTemplate;

    /** @var string */
    private $email;

    public function __construct($id, $token, ChecklistTemplate $checklistTemplate, $email)
    {
        $this->id = $id;
        $this->checklistTemplate = $checklistTemplate;
        $this->email = $this->validateEmail($email);
        $this->token = $token;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function getChecklistTemplate()
    {
        return $this->checklistTemplate;
    }

    public function getEmail()
    {
        return $this->email;
    }

    private function validateEmail($email)
    {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
            throw new InvalidEmailException();

        return $email;
    }

}