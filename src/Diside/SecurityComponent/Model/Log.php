<?php

namespace Diside\SecurityComponent\Model;

class Log
{
    /** @var int */
    private $id;

    /** @var string */
    private $action;

    /** @var string */
    private $details;

    /** @var User */
    private $user;

    /** @var \DateTime */
    private $date;

    public function __construct($id, $action, $details, User $user, \DateTime $date)
    {
        $this->id = $id;
        $this->action = $action;
        $this->details = $details;
        $this->user = $user;
        $this->date = $date;
    }

    public function __toString()
    {
        return $this->action;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function setAction($type)
    {
        $this->action = $type;
    }

    public function getDetails()
    {
        return $this->details;
    }

    public function setDetails($action)
    {
        $this->details = $action;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate(\DateTime $date)
    {
        $this->date = $date;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getUserId()
    {
        return $this->user->getId();
    }

}