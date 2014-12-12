<?php

namespace Diside\SecurityComponent\Tests\Interactor\Interactor;

use Diside\SecurityComponent\Interactor\Interactor\FindUsersInteractor;
use Diside\SecurityComponent\Interactor\Presenter\UsersPresenter;
use Diside\SecurityComponent\Interactor\Request\FindUsersRequest;
use Diside\SecurityComponent\Model\User;

class FindUsersInteractorTest extends BaseInteractorTest
{
    /** @var FindUsersInteractor */
    private $interactor;

    /**
     * @before
     */
    public function setUp()
    {
        parent::setUp();

        $this->interactor = new FindUsersInteractor($this->gatewayRegistry, $this->logger);
    }

    /**
     * @test
     */
    public function testWhenThereAreNoUsers_thenReturnTheRequestingUser()
    {
        $user = $this->givenAdmin('Acme');
        $request = new FindUsersRequest($user->getId());

        $this->interactor->process($request, $this->presenter);

        $users = $this->presenter->getUsers();

        $this->assertThat(count($users), $this->equalTo(1));
    }

    /**
     * @test
     */
    public function testPagination()
    {
        $admin = $this->givenAdmin('Acme');

        $total = 10;

        $this->givenUsers($total, 'Acme');
        $this->givenInactiveUser('inactive@example.com', '', array(), 'Acme');

        $this->assertPage($admin, 0, 5, 5, $total + 2); // Active + Inactive + Admin
        $this->assertPage($admin, 1, 5, 5, $total + 2);
        $this->assertPage($admin, 2, 5, 2, $total + 2);
    }

    /**
     * @test
     */
    public function whenFindingAsSuperadmin_thenReturnAll()
    {
        $superadmin = $this->givenSuperadmin('superadmin@example.com');

        $this->givenUser('adam@acme.com', '', array(), 'Acme');
        $this->givenUser('chloe@example.com', '', array(), 'Bros');

        $request = new FindUsersRequest($superadmin->getId());

        $this->interactor->process($request, $this->presenter);

        $this->assertThat(count($this->presenter->getUsers()), $this->equalTo(3));
        $this->assertThat($this->presenter->getTotalUsers(), $this->equalTo(3));
    }

    /**
     * @test
     */
    public function whenFindingAsAdmin_thenReturnAllByCompanyButSuperadmins()
    {
        $admin = $this->givenAdmin('Acme');

        $this->givenSuperadmin();
        $this->givenUser('adam@acme.com', '', array(), 'Acme');
        $this->givenUser('chloe@bros.com', '', array(), 'Bros');

        $request = new FindUsersRequest($admin->getId());

        $this->interactor->process($request, $this->presenter);

        $this->assertThat(count($this->presenter->getUsers()), $this->equalTo(2)); // Admin + Adam
    }

    /**
     * @test
     */
    public function whenFindingAsManager_thenReturnAllActiveByCompany()
    {
        $admin = $this->givenManager('Acme');

        $this->givenUser('adam@acme.com', '', array(), 'Acme');
        $this->givenInactiveUser('Acme');

        $request = new FindUsersRequest($admin->getId());

        $this->interactor->process($request, $this->presenter);

        $this->assertThat(count($this->presenter->getUsers()), $this->equalTo(2)); // Manager + Adam
    }

    private function givenUsers($number, $company)
    {
        for ($i = 0; $i < $number; ++$i) {
            $this->givenUser($i . '@example.com', '', array(), $company);
        }
    }

    private function assertPage(User $user, $start, $end, $value, $total)
    {
        $request = new FindUsersRequest($user->getId(), $start, $end);

        $this->interactor->process($request, $this->presenter);

        $this->assertThat(count($this->presenter->getUsers()), $this->equalTo($value));
        $this->assertThat($this->presenter->getTotalUsers(), $this->equalTo($total));
    }

    protected function buildPresenter()
    {
        return new FindUsersPresenterMock();
    }
}

class FindUsersPresenterMock implements UsersPresenter
{
    private $total;
    private $errors;
    private $users;

    public function getUsers()
    {
        return $this->users;
    }

    public function setUsers(array $users)
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
    public function getTotalUsers()
    {
        return $this->total;
    }

    public function setTotalUsers($total)
    {
        $this->total = $total;
    }
}
