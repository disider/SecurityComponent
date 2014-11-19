<?php

namespace Diside\SecurityComponent\Tests\Interactor\Interactor;

use Diside\SecurityComponent\Gateway\CompanyGateway;
use Diside\SecurityComponent\Gateway\InMemory\InMemoryCompanyGateway;
use Diside\SecurityComponent\Gateway\InMemory\InMemoryUserGateway;
use Diside\SecurityComponent\Gateway\UserGateway;
use Diside\SecurityComponent\Interactor\Presenter\DeleteCompanyPresenter;
use Diside\SecurityComponent\Interactor\Presenter\CompanyPresenter;
use Diside\SecurityComponent\Interactor\Request\DeleteCompanyRequest;
use Diside\SecurityComponent\Interactor\Interactor\DeleteCompanyInteractor;
use Diside\SecurityComponent\Model\Company;
use Diside\SecurityComponent\Model\User;

class DeleteCompanyInteractorTest extends BaseUserInteractorTest
{
    /** @var DeleteCompanyInteractor */
    private $interactor;

    /**
     * @before
     */
    public function setUp()
    {
        parent::setUp();

        $this->interactor = new DeleteCompanyInteractor($this->gatewayRegistry, $this->logger);
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

        $company = $this->givenCompany('Acme');

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
        $company = $this->givenCompany('Acme');
        $request = new DeleteCompanyRequest($admin->getId(), $company->getId());

        $this->interactor->process($request, $this->presenter);

        $this->assertFalse($this->presenter->hasErrors());

        $this->assertThat($this->companyGateway->countAll(), $this->equalTo(0));
    }

    protected function buildPresenter()
    {
        return new DeleteCompanyPresenterSpy();
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