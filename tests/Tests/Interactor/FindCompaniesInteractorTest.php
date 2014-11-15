<?php

namespace SecurityComponent\Interactor;

use SecurityComponent\Gateway\CompanyGateway;
use SecurityComponent\Gateway\InMemory\InMemoryCompanyGateway;
use SecurityComponent\Gateway\InMemory\InMemoryUserGateway;
use SecurityComponent\Gateway\UserGateway;
use SecurityComponent\Interactor\Presenter\FindCompaniesPresenter;
use SecurityComponent\Interactor\Request\FindCompaniesRequest;
use SecurityComponent\Model\Company;
use SecurityComponent\Model\User;

class FindCompaniesInteractorTest extends \PHPUnit_Framework_TestCase
{
    /** @var CompanyGateway */
    private $companyGateway;

    /** @var UserGateway */
    private $userGateway;

    /** @var FindCompaniesInteractor */
    private $interactor;

    /** @var FindCompaniesPresenter */
    private $presenter;

    public function __construct()
    {
        $this->companyGateway = new InMemoryCompanyGateway();
        $this->userGateway = new InMemoryUserGateway();

        $this->interactor = new FindCompaniesInteractor($this->companyGateway, $this->userGateway);

        $this->presenter = new FindCompaniesPresenterMock();
    }

    /**
     * @test
     */
    public function testWhenThereAreNoCompanies_thenReturnEmptyList()
    {
        $user = $this->givenSuperadmin('adam@example.com', 'Acme');
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
        for($i = 0; $i < $number; ++$i) {
            $this->givenCompany($company . ' ' . $i);
        }
    }

    private function givenSuperadmin($email)
    {
        $user = new User(null, $email, '', '');
        $user->addRole(User::ROLE_SUPERADMIN);

        return $this->userGateway->save($user);
    }

    /** @return Company */
    private function givenCompany($name)
    {
        $company = new Company(null, $name);

        return $this->companyGateway->save($company);
    }

    private function assertPage(User $user, $start, $end, $value, $total)
    {
        $request = new FindCompaniesRequest($user->getId(), $start, $end);

        $this->interactor->process($request, $this->presenter);

        $this->assertThat(count($this->presenter->getCompanies()), $this->equalTo($value));
        $this->assertThat($this->presenter->getTotalCompanies(), $this->equalTo($total));
    }

}

class FindCompaniesPresenterMock implements FindCompaniesPresenter
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
