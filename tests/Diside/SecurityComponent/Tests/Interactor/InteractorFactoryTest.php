<?php

namespace Diside\SecurityComponent\Tests\Interactor;

use Diside\SecurityComponent\Gateway\GatewayRegister;
use Diside\SecurityComponent\Gateway\InMemory\InMemoryCompanyGateway;
use Diside\SecurityComponent\Gateway\InMemory\InMemoryLogGateway;
use Diside\SecurityComponent\Gateway\InMemory\InMemoryUserGateway;
use Diside\SecurityComponent\Interactor\AbstractInteractor;
use Diside\SecurityComponent\Interactor\InteractorFactory;
use Diside\SecurityComponent\Interactor\InteractorRegister;
use Diside\SecurityComponent\Interactor\Presenter;
use Diside\SecurityComponent\Interactor\Request;
use Diside\SecurityComponent\Interactor\SecurityInteractorRegister;
use Diside\SecurityComponent\Logger\Logger;

class InteractorFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var InteractorFactory */
    private $interactorFactory;

    /**
     * @before
     */
    public function setUp()
    {
        $gatewayRegister = new GatewayRegister();

        $logGateway = new InMemoryLogGateway();
        $logger = new Logger($logGateway);

        $this->interactorFactory = new InteractorFactory($gatewayRegister, $logger);
    }

    /**
     * @test
     */
    public function buildInteractors()
    {
        $interactorRegister = new SecurityInteractorRegister();
        $this->interactorFactory->addRegister($interactorRegister);

        $this->assertInteractor(SecurityInteractorRegister::FIND_COMPANIES, '\Diside\SecurityComponent\Interactor\Interactor\FindCompaniesInteractor');
        $this->assertInteractor(SecurityInteractorRegister::GET_COMPANY, '\Diside\SecurityComponent\Interactor\Interactor\GetCompanyInteractor');
        $this->assertInteractor(SecurityInteractorRegister::SAVE_COMPANY, '\Diside\SecurityComponent\Interactor\Interactor\SaveCompanyInteractor');
        $this->assertInteractor(SecurityInteractorRegister::DELETE_COMPANY, '\Diside\SecurityComponent\Interactor\Interactor\DeleteCompanyInteractor');
        $this->assertInteractor(SecurityInteractorRegister::REGISTER_USER, '\Diside\SecurityComponent\Interactor\Interactor\RegisterUserInteractor');
        $this->assertInteractor(SecurityInteractorRegister::CONFIRM_USER_REGISTRATION, '\Diside\SecurityComponent\Interactor\Interactor\ConfirmUserRegistrationInteractor');
        $this->assertInteractor(SecurityInteractorRegister::REQUEST_RESET_PASSWORD, '\Diside\SecurityComponent\Interactor\Interactor\RequestResetPasswordInteractor');
        $this->assertInteractor(SecurityInteractorRegister::RESET_PASSWORD, '\Diside\SecurityComponent\Interactor\Interactor\ResetPasswordInteractor');
        $this->assertInteractor(SecurityInteractorRegister::FIND_USERS, '\Diside\SecurityComponent\Interactor\Interactor\FindUsersInteractor');
        $this->assertInteractor(SecurityInteractorRegister::GET_USER, '\Diside\SecurityComponent\Interactor\Interactor\GetUserInteractor');
        $this->assertInteractor(SecurityInteractorRegister::SAVE_USER, '\Diside\SecurityComponent\Interactor\Interactor\SaveUserInteractor');
        $this->assertInteractor(SecurityInteractorRegister::DELETE_USER, '\Diside\SecurityComponent\Interactor\Interactor\DeleteUserInteractor');
        $this->assertInteractor(SecurityInteractorRegister::FIND_LOGS, '\Diside\SecurityComponent\Interactor\Interactor\FindLogsInteractor');
    }

    /**
     * @test
     */
    public function testAddRegistry()
    {
        $register = new DummyRegister();

        $this->interactorFactory->addRegister($register);
        $this->assertInstanceOf('Diside\SecurityComponent\Tests\Interactor\DummyInteractor', $this->interactorFactory->get('dummy'));
    }

    /**
     * @test
     * @expectedException \Diside\SecurityComponent\Interactor\UndefinedInteractorException
     */
    public function whenRetrievingUndefinedInteractor_thenThrow()
    {
        $this->interactorFactory->get('unknown');

    }


    private function assertInteractor($type, $class)
    {
        $interactor = $this->interactorFactory->get($type);
        $this->assertInstanceOf($class, $interactor);
        $this->assertInstanceOf('Diside\SecurityComponent\Interactor\Interactor', $interactor);
    }
}

class DummyRegister extends InteractorRegister
{
    public function __construct()
    {
        $this->register('dummy', 'Diside\SecurityComponent\Tests\Interactor\DummyInteractor');
    }
}

class DummyInteractor extends AbstractInteractor
{
    function process(Request $request, Presenter $presenter)
    {
    }
}