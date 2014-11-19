<?php

namespace Diside\SecurityComponent\Gateway;

class GatewayRegister
{
    private $gateways = array();

    public function register(Gateway $gateway)
    {
        $this->gateways[$gateway->getName()] = $gateway;
    }

    public function get($name)
    {
        if(!array_key_exists($name, $this->gateways))
            throw new UndefinedGatewayException($name);

        return $this->gateways[$name];
    }
}

class UndefinedGatewayException extends \Exception
{
    public function __construct($name)
    {
        parent::__construct('Undefined gateway: ' . $name);
    }
}
