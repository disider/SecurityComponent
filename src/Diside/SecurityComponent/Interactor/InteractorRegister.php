<?php

namespace Diside\SecurityComponent\Interactor;

class InteractorRegister
{
    private $interactorClasses = array();

    protected function register($type, $class)
    {
        $this->interactorClasses[$type] = $class;
    }

    public function getAll()
    {
        return $this->interactorClasses;
    }

    public function has($type)
    {
        return array_key_exists($type, $this->interactorClasses);
    }

    public function get($type)
    {
        if(!$this->has($type))
            throw new UndefinedInteractorClassException($type);

        return $this->interactorClasses[$type];
    }
}

class UndefinedInteractorClassException extends \Exception
{
    public function __construct($type)
    {
        parent::__construct('Undefined interactor class type: ' . $type);
    }
}
