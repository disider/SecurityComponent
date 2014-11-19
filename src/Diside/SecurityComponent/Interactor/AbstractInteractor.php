<?php

namespace Diside\SecurityComponent\Interactor;

use Diside\SecurityComponent\Gateway\Gateway;
use Diside\SecurityComponent\Gateway\GatewayRegister;
use Diside\SecurityComponent\Interactor\Presenter;
use Diside\SecurityComponent\Interactor\Request;
use Diside\SecurityComponent\Logger\Logger;

abstract class AbstractInteractor implements Interactor
{
    /** @var GatewayRegister */
    private $registry;

    /** @var Logger */
    private $logger;

    public function __construct(GatewayRegister $registry, Logger $logger)
    {
        $this->registry = $registry;
        $this->logger = $logger;
    }

    /**
     * @return Logger
     */
    protected function getLogger()
    {
        return $this->logger;
    }

    /**
     * @return Gateway
     */
    protected function getGateway($name)
    {
        return $this->registry->get($name);
    }
}