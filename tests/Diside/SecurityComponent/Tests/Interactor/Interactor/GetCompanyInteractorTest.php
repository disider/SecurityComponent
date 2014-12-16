<?php

namespace Diside\SecurityComponent\Tests\Interactor\Interactor;

use Diside\SecurityComponent\Interactor\Interactor\GetCompanyInteractor;
use Diside\SecurityComponent\Interactor\Presenter\CompanyPresenter;
use Diside\SecurityComponent\Interactor\Request\GetCompanyRequest;
use Diside\SecurityComponent\Model\Company;
use Diside\SecurityComponent\Model\User;

class GetCompanyInteractorTest extends BaseInteractorTest
{
    /** @var GetCompanyInteractor */
    private $interactor;

    /**
     * @before
     */
    public function setUp()
    {
        parent::setUp();

        $this->interactor = new GetCompanyInteractor($this->gatewayRegistry, $this->logger);
    }

    /**
     * @test
     */
    public function testProcess()
    {
        $user = $this->givenUser();
        $company = $this->givenCompany('Acme');

        $request = new GetCompanyRequest($user->getId());
        $this->interactor->process($request, $this->presenter);

        /** @var Company $user */
        $user = $this->presenter->getCompany();
        $this->assertThat($company, $this->equalTo('Acme'));
    }

    protected function buildPresenter()
    {
        return new CompanyPresenterMock();
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