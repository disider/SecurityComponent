<?php

namespace SecurityComponent\Tests\Interactor\Manager;

use SecurityComponent\Gateway\CompanyGateway;
use SecurityComponent\Gateway\InMemory\InMemoryCategoryGateway;
use SecurityComponent\Gateway\InMemory\InMemoryChecklistTemplateGateway;
use SecurityComponent\Gateway\InMemory\InMemoryCompanyGateway;
use SecurityComponent\Gateway\InMemory\InMemoryLogGateway;
use SecurityComponent\Gateway\InMemory\InMemoryRunningChecklistGateway;
use SecurityComponent\Gateway\InMemory\InMemoryShareRequestGateway;
use SecurityComponent\Gateway\InMemory\InMemoryUserGateway;
use SecurityComponent\Gateway\RunningChecklistGateway;
use SecurityComponent\Gateway\ShareRequestGateway;
use SecurityComponent\Gateway\UserGateway;
use SecurityComponent\Interactor\InteractorFactory;

class InteractorFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var CompanyGateway */
    private $companyGateway;

    /** @var UserGateway */
    private $userGateway;

    /** @var InteractorFactory */
    private $interactorFactory;

    /**
     * @before
     */
    public function setUp()
    {
        $this->companyGateway = new InMemoryCompanyGateway();
        $this->userGateway = new InMemoryUserGateway();

        $this->interactorFactory = new InteractorFactory(
            $this->companyGateway,
            $this->userGateway
        );
    }

    /**
     * @test
     */
    public function buildCompanyInteractors()
    {
        $this->assertInteractor(InteractorFactory::FIND_COMPANIES, '\SecurityComponent\Interactor\FindCompaniesInteractor');
        $this->assertInteractor(InteractorFactory::GET_COMPANY, '\SecurityComponent\Interactor\GetCompanyInteractor');
        $this->assertInteractor(InteractorFactory::SAVE_COMPANY, '\SecurityComponent\Interactor\SaveCompanyInteractor');
        $this->assertInteractor(InteractorFactory::DELETE_COMPANY, '\SecurityComponent\Interactor\DeleteCompanyInteractor');
    }

    /**
     * @test
     */
    public function buildSecurityInteractors()
    {
        $this->assertInteractor(InteractorFactory::REGISTER_USER, '\SecurityComponent\Interactor\RegisterUserInteractor');
        $this->assertInteractor(InteractorFactory::CONFIRM_USER_REGISTRATION, '\SecurityComponent\Interactor\ConfirmUserRegistrationInteractor');
        $this->assertInteractor(InteractorFactory::REQUEST_RESET_PASSWORD, '\SecurityComponent\Interactor\RequestResetPasswordInteractor');
        $this->assertInteractor(InteractorFactory::RESET_PASSWORD, '\SecurityComponent\Interactor\ResetPasswordInteractor');
    }

    /**
     * @test
     */
    public function buildUserInteractors()
    {
        $this->assertInteractor(InteractorFactory::FIND_USERS, '\SecurityComponent\Interactor\FindUsersInteractor');
        $this->assertInteractor(InteractorFactory::GET_USER, '\SecurityComponent\Interactor\GetUserInteractor');
        $this->assertInteractor(InteractorFactory::SAVE_USER, '\SecurityComponent\Interactor\SaveUserInteractor');
        $this->assertInteractor(InteractorFactory::DELETE_USER, '\SecurityComponent\Interactor\DeleteUserInteractor');
    }

    private function assertInteractor($type, $class)
    {
        $interactor = $this->interactorFactory->get($type);
        $this->assertInstanceOf($class, $interactor);
        $this->assertInstanceOf('SecurityComponent\Interactor\Interactor', $interactor);
    }
}