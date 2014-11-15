<?php

namespace SecurityComponent\Tests\Interactor;

use SecurityComponent\Gateway\CompanyGateway;
use SecurityComponent\Gateway\InMemory\InMemoryCompanyGateway;
use SecurityComponent\Interactor\Presenter\CompanyPresenter;
use SecurityComponent\Interactor\SaveCompanyInteractor;
use SecurityComponent\Interactor\Presenter\SaveCompanyPresenter;
use SecurityComponent\Interactor\Request\SaveCompanyRequest;
use SecurityComponent\Model\Company;

class SaveCompanyInteractorTest extends \PHPUnit_Framework_TestCase
{
    /** @var SaveCompanyInteractor */
    private $interactor;

    /** @var CompanyGateway */
    private $companyGateway;

    /** @var CompanyPresenter */
    private $presenter;

    /**
     * @before
     */
    public function setUp()
    {
        $this->companyGateway = new InMemoryCompanyGateway();
        $this->interactor = new SaveCompanyInteractor($this->companyGateway);
        $this->presenter = new SaveCompanyPresenterSpy();
    }

    /**
     * @test
     */
    public function whenSavingWithoutName_thenReturnEmptyName()
    {
        $request = new SaveCompanyRequest(null, null);

        $this->interactor->process($request, $this->presenter);

        $this->assertNull($this->presenter->getCompany());
        $this->assertTrue($this->presenter->hasErrors());
        $this->assertThat($this->presenter->getErrors(), $this->equalTo(array(CompanyPresenter::EMPTY_NAME)));
    }

    /**
     * @test
     */
    public function whenSavingNewCompany_thenReturnSavedCompany()
    {
        $request = new SaveCompanyRequest(null, 'Acme');

        $this->interactor->process($request, $this->presenter);

        $company = $this->presenter->getCompany();

        $this->assertNotNull($company->getId());

        $this->assertCompany($company, $request);
    }

    /**
     * @test
     */
    public function whenSavingExistingCompany_thenReturnSavedCompany()
    {
        $company = new Company(1, 'Acme');
        $this->companyGateway->save($company);

        $request = new SaveCompanyRequest(1, 'Acme');

        $this->interactor->process($request, $this->presenter);

        $company = $this->presenter->getCompany();

        $this->assertCompany($company, $request);

        $this->assertThat(count($this->companyGateway->findAll()), $this->equalTo(1));
    }

    private function assertCompany(Company $company, $request)
    {
        $this->assertThat($company->getName(), $this->equalTo($request->name));
    }
}

class SaveCompanyPresenterSpy implements CompanyPresenter
{
    private $company;
    private $errors;

    public function getErrors()
    {
        return $this->errors;
    }

    public function getCompany()
    {
        return $this->company;
    }

    public function setErrors(array $errors)
    {
        $this->errors = $errors;
    }

    public function setCompany(Company $company)
    {
        $this->company = $company;
    }

    public function hasErrors()
    {
        return $this->errors != null;
    }
}