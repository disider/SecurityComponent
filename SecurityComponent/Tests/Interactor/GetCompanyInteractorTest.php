<?php

namespace SecurityComponent\Interactor;

use SecurityComponent\Gateway\InMemory\InMemoryCompanyGateway;
use SecurityComponent\Gateway\InMemory\InMemoryUserGateway;
use SecurityComponent\Interactor\Presenter\CompanyPresenter;
use SecurityComponent\Interactor\Request\GetCompanyRequest;
use SecurityComponent\Model\Company;
use SecurityComponent\Model\User;

class GetCompanyInteractorTest extends \PHPUnit_Framework_TestCase
{
    /** @var CompanyGateway */
    private $companyGateway;

    /** @var UserGateway */
    private $userGateway;

    /** @var GetCompanyInteractor */
    private $interactor;

    /** @var CompanyPresenter */
    private $presenter;

    /**
     * @before
     */
    public function setUp()
    {
        $this->userGateway = new InMemoryUserGateway();
        $this->companyGateway = new InMemoryCompanyGateway();
        $this->interactor = new GetCompanyInteractor($this->companyGateway);
        $this->presenter = new CompanyPresenterMock();
    }

    /**
     * @test
     */
    public function testProcess()
    {
        $user = new User(null, 'admin@example.com', '', '');
        $user->addRole(User::ROLE_SUPERADMIN);

        $this->userGateway->save($user);

        $company = new Company(null, 'Acme');
        $company = $this->companyGateway->save($company);

        $request = new GetCompanyRequest($user->getId());
        $this->interactor->process($request, $this->presenter);

        /** @var Company $user */
        $user = $this->presenter->getCompany();
        $this->assertThat($company, $this->equalTo('Acme'));
    }
}

class CompanyPresenterMock implements CompanyPresenter
{
    private $errors;
    private $user;

    public function getCompany()
    {
        return $this->user;
    }

    public function setCompany(Company $user)
    {
        $this->user = $user;
    }

    public function hasErrors()
    {
        return $this->errors != null;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function setErrors(array $errors)
    {
        $this->errors = $errors;
    }
}