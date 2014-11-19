<?php

namespace Diside\SecurityComponent\Interactor;

use Diside\SecurityComponent\Gateway\CategoryGateway;
use Diside\SecurityComponent\Gateway\ChecklistTemplateGateway;
use Diside\SecurityComponent\Gateway\GatewayRegister;
use Diside\SecurityComponent\Gateway\RunningChecklistGateway;
use Diside\SecurityComponent\Gateway\ShareRequestGateway;
use Diside\SecurityComponent\Logger\Logger;

class InteractorFactory
{
    /** @var GatewayRegister */
    private $gatewayRegister;

    /** @var array */
    private $interactorRegisters = array();

    /** @var Logger */
    private $logger;

    public function __construct(GatewayRegister $gatewayRegister, Logger $logger)
    {
        $this->gatewayRegister = $gatewayRegister;
        $this->logger = $logger;
    }

    /**
     * @return AbstractInteractor
     */
    public function get($type)
    {
        /** @var InteractorRegister $interactorRegister */
        foreach ($this->interactorRegisters as $interactorRegister) {
            if ($interactorRegister->has($type)) {
                $class = $interactorRegister->get($type);

                return new $class($this->gatewayRegister, $this->logger);
            }
        }

        throw new UndefinedInteractorException($type);
    }

    public function addRegister(InteractorRegister $interactorRegister)
    {
        $this->interactorRegisters[] = $interactorRegister;
    }
}

class UndefinedInteractorException extends \Exception
{
    public function __construct($type)
    {
        parent::__construct('Undefined interactor type: ' . $type);
    }
}
