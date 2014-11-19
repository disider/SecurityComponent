<?php

namespace Diside\SecurityComponent\Tests\Interactor;

use Diside\SecurityComponent\Interactor\InteractorRegister;
use Diside\SecurityComponent\Interactor\SecurityInteractorRegister;

class SecurityInteractorRegisterTest extends \PHPUnit_Framework_TestCase
{
    /** @var InteractorRegister */
    private $interactorRegister;

    /**
     * @before
     */
    public function setUp()
    {
        $this->interactorRegister = new SecurityInteractorRegister();
    }

    /**
     * @test
     */
    public function countClasses()
    {
        $this->assertThat(count($this->interactorRegister->getAll()), $this->equalTo(13));
    }

    /**
     * @test
     */
    public function testRegisteredInteractors()
    {
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
     * @expectedException \Diside\SecurityComponent\Interactor\UndefinedInteractorClassException
     */
    public function whenRetrievingUndefinedInteractor_thenThrow()
    {
        $this->interactorRegister->get('unknown');

    }

    private function assertInteractor($type, $class)
    {
        $interactorClass = $this->interactorRegister->get($type);
        $this->assertThat($interactorClass, $this->equalTo($class));
    }
}