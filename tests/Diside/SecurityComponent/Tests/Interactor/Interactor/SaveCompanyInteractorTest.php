<?php

namespace Diside\SecurityComponent\Tests\Interactor\Interactor;

use Diside\SecurityComponent\Gateway\CompanyGateway;
use Diside\SecurityComponent\Interactor\Interactor\SaveCompanyInteractor;
use Diside\SecurityComponent\Interactor\Presenter\CompanyPresenter;
use Diside\SecurityComponent\Interactor\Presenter\SaveCompanyPresenter;
use Diside\SecurityComponent\Interactor\Request\SaveCompanyRequest;
use Diside\SecurityComponent\Model\Company;

class SaveCompanyInteractorTest extends BaseInteractorTest
{
    /** @var SaveCompanyInteractor */
    private $interactor;

    /**
     * @before
     */
    public function setUp()
    {
        parent::setUp();

        $this->interactor = new SaveCompanyInteractor($this->gatewayRegistry, $this->logger);
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
        $this->getGateway(CompanyGateway::NAME)->save($company);

        $request = new SaveCompanyRequest(1, 'Acme');

        $this->interactor->process($request, $this->presenter);

        $company = $this->presenter->getCompany();

        $this->assertCompany($company, $request);

        $this->assertThat(count($this->getGateway(CompanyGateway::NAME)->findAll()), $this->equalTo(1));
    }

    private function assertCompany(Company $company, $request)
    {
        $this->assertThat($company->getName(), $this->equalTo($request->name));
    }

    protected function buildPresenter()
    {
        return new SaveCompanyPresenterSpy();
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