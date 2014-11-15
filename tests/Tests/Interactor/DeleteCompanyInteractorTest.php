<?php

namespace SecurityComponent\Tests\Interactor;

use SecurityComponent\Gateway\CompanyGateway;
use SecurityComponent\Gateway\InMemory\InMemoryCompanyGateway;
use SecurityComponent\Gateway\InMemory\InMemoryUserGateway;
use SecurityComponent\Gateway\UserGateway;
use SecurityComponent\Interactor\Presenter\DeleteCompanyPresenter;
use SecurityComponent\Interactor\Presenter\CompanyPresenter;
use SecurityComponent\Interactor\Request\DeleteCompanyRequest;
use SecurityComponent\Interactor\DeleteCompanyInteractor;
use SecurityComponent\Model\Company;
use SecurityComponent\Model\User;

class DeleteCompanyInteractorTest extends \PHPUnit_Framework_TestCase
{
    /** @var DeleteCompanyInteractor */
    private $interactor;

    /** @var UserGateway */
    private $userGateway;

    /** @var CompanyGateway */
    private $companyGateway;

    /** @var DeleteCompanyPresenter */
    private $presenter;

    /**
     * @before
     */
    public function setUp()
    {
        $this->userGateway = new InMemoryUserGateway();
        $this->companyGateway = new InMemoryCompanyGateway();
        $this->interactor = new DeleteCompanyInteractor($this->companyGateway, $this->userGateway);
        $this->presenter = new DeleteCompanyPresenterSpy();
    }

    /**
     * @test
     */
    public function whenDeletingWithUnknownOwner_thenReturnError()
    {
        $request = new DeleteCompanyRequest(null, -1);

        $this->interactor->process($request, $this->presenter);

        $this->assertTrue($this->presenter->hasErrors());
        $this->assertThat($this->presenter->getErrors(), $this->equalTo(array(CompanyPresenter::UNDEFINED_SUPERADMIN_ID)));
    }

    /**
     * @test
     */
    public function whenDeletingUndefinedCompany_thenReturnError()
    {
        $superadmin = $this->givenSuperadmin();

        $request = new DeleteCompanyRequest($superadmin->getId(), -1);

        $this->interactor->process($request, $this->presenter);

        $this->assertTrue($this->presenter->hasErrors());
        $this->assertThat($this->presenter->getErrors(), $this->equalTo(array(CompanyPresenter::UNDEFINED_COMPANY_ID)));
    }

    /**
     * @test
     */
    public function whenDeletingAndNotAuthorized_thenReturnError()
    {
        $user = $this->givenUser();

        $company = $this->givenCompany();

        $request = new DeleteCompanyRequest($user->getId(), $company->getId());

        $this->interactor->process($request, $this->presenter);

        $this->assertTrue($this->presenter->hasErrors());
        $this->assertThat($this->presenter->getErrors(), $this->equalTo(array(CompanyPresenter::FORBIDDEN)));
    }

    /**
     * @test
     */
    public function whenDeletingAndAuthorized_thenHasNoErrors()
    {
        $admin = $this->givenSuperadmin();
        $company = $this->givenCompany();
        $request = new DeleteCompanyRequest($admin->getId(), $company->getId());

        $this->interactor->process($request, $this->presenter);

        $this->assertFalse($this->presenter->hasErrors());

        $this->assertThat($this->companyGateway->countAll(), $this->equalTo(0));
    }

    /** @return Company */
    private function givenCompany()
    {
        $template = new Company(null, 'company@example.com', '', '');

        return $this->companyGateway->save($template);
    }

    private function givenSuperadmin()
    {
        $user = new User(null, 'superadmin@example.com', '', '');
        $user->addRole(User::ROLE_SUPERADMIN);

        return $this->userGateway->save($user);
    }

    private function givenUser()
    {
        $user = new User(null, 'user@example.com', '', '');

        return $this->userGateway->save($user);
    }

}

class DeleteCompanyPresenterSpy implements CompanyPresenter
{
    private $errors;

    public function getErrors()
    {
        return $this->errors;
    }

    public function setErrors(array $errors)
    {
        $this->errors = $errors;
    }

    public function hasErrors()
    {
        return $this->errors != null;
    }

    public function getCompany()
    {
    }

    public function setCompany(Company $company)
    {
    }
}