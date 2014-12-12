<?php

namespace Diside\SecurityComponent\Tests\Interactor\Interactor;

use Diside\SecurityComponent\Interactor\Interactor\FindCompaniesInteractor;
use Diside\SecurityComponent\Interactor\Presenter\CompaniesPresenter;
use Diside\SecurityComponent\Interactor\Request\FindCompaniesRequest;
use Diside\SecurityComponent\Model\User;

class FindCompaniesInteractorTest extends BaseInteractorTest
{
    /** @var FindCompaniesInteractor */
    private $interactor;

    /**
     * @before
     */
    public function setUp()
    {
        parent::setUp();

        $this->interactor = new FindCompaniesInteractor($this->gatewayRegistry, $this->logger);
    }

    /**
     * @test
     */
    public function testWhenThereAreNoCompanies_thenReturnEmptyList()
    {
        $user = $this->givenUser();
        $request = new FindCompaniesRequest($user->getId());

        $this->interactor->process($request, $this->presenter);

        $companies = $this->presenter->getCompanies();

        $this->assertThat(count($companies), $this->equalTo(0));
    }

    /**
     * @test
     */
    public function testPagination()
    {
        $admin = $this->givenSuperadmin('adam@example.com');

        $total = 10;

        $this->givenCompanies($total, 'Acme');

        $this->assertPage($admin, 0, 5, 5, $total);
        $this->assertPage($admin, 1, 5, 5, $total);
        $this->assertPage($admin, 2, 5, 0, $total);
    }

    private function givenCompanies($number, $company)
    {
        for ($i = 0; $i < $number; ++$i) {
            $this->givenCompany($company . ' ' . $i);
        }
    }

    private function assertPage(User $user, $start, $end, $value, $total)
    {
        $request = new FindCompaniesRequest($user->getId(), $start, $end);

        $this->interactor->process($request, $this->presenter);

        $this->assertThat(count($this->presenter->getCompanies()), $this->equalTo($value));
        $this->assertThat($this->presenter->getTotalCompanies(), $this->equalTo($total));
    }

    protected function buildPresenter()
    {
        return new FindCompaniesPresenterMock();
    }
}

class FindCompaniesPresenterMock implements CompaniesPresenter
{
    private $total;
    private $errors;
    private $users;

    public function getCompanies()
    {
        return $this->users;
    }

    public function setCompanies(array $users)
    {
        $this->users = $users;
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

    /** @return int */
    public function getTotalCompanies()
    {
        return $this->total;
    }

    public function setTotalCompanies($total)
    {
        $this->total = $total;
    }
}
