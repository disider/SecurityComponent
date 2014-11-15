<?php

namespace SecurityComponent\Tests\Interactor;

use SecurityComponent\Interactor\DeleteUserInteractor;
use SecurityComponent\Interactor\Presenter\DeleteUserPresenter;
use SecurityComponent\Interactor\Presenter\UserPresenter;
use SecurityComponent\Interactor\Request\DeleteUserRequest;
use SecurityComponent\Model\User;

class DeleteUserInteractorTest extends BaseUserInteractorTest
{
    /** @var DeleteUserInteractor */
    private $interactor;

    /**
     * @before
     */
    public function setUp()
    {
        parent::setUp();

        $this->interactor = new DeleteUserInteractor($this->userGateway);
    }

    /**
     * @test
     */
    public function whenDeletingWithUnknownOwner_thenReturnError()
    {
        $request = new DeleteUserRequest(null, -1);

        $this->interactor->process($request, $this->presenter);

        $this->assertTrue($this->presenter->hasErrors());
        $this->assertThat($this->presenter->getErrors(), $this->equalTo(array(UserPresenter::UNDEFINED_USER_ID)));
    }

    /**
     * @test
     */
    public function whenDeletingUndefinedUser_thenReturnError()
    {
        $admin = $this->givenAdmin();

        $request = new DeleteUserRequest($admin->getId(), -1);

        $this->interactor->process($request, $this->presenter);

        $this->assertTrue($this->presenter->hasErrors());
        $this->assertThat($this->presenter->getErrors(), $this->equalTo(array(UserPresenter::UNDEFINED_USER)));
    }

    /**
     * @test
     */
    public function whenDeletingAndNotAuthorized_thenReturnError()
    {
        $user1 = $this->givenUser();
        $user2 = $this->givenUser();

        $request = new DeleteUserRequest($user1->getId(), $user2->getId());

        $this->interactor->process($request, $this->presenter);

        $this->assertTrue($this->presenter->hasErrors());
        $this->assertThat($this->presenter->getErrors(), $this->equalTo(array(UserPresenter::FORBIDDEN)));
    }

    /**
     * @test
     */
    public function whenDeletingAsAdminForSameCompany_thenReturnUser()
    {
        $this->givenCompany('Acme');
        $admin = $this->givenAdmin('Acme');
        $user = $this->givenUser('user@example.com', 'password', array(), 'Acme');
        $request = new DeleteUserRequest($admin->getId(), $user->getId());

        $this->interactor->process($request, $this->presenter);

        $user = $this->presenter->getUser();
        $this->assertInstanceOf('SecurityComponent\Model\User', $user);
    }

    /**
     * @test
     */
    public function whenDeletingAsAdminForAnotherCompany_thenReturnError()
    {
        $this->givenCompany('Acme');
        $admin = $this->givenAdmin('Acme');
        $user = $this->givenUser('user@example.com', 'password', array(), 'Bros');
        $request = new DeleteUserRequest($admin->getId(), $user->getId());

        $this->interactor->process($request, $this->presenter);

        $this->assertError(0, UserPresenter::FORBIDDEN);
    }

    /**
     * @test
     */
    public function whenDeletingAsSuperadmin_thenReturnUser()
    {
        $this->givenCompany('Bros');
        $superadmin = $this->givenSuperadmin();
        $user = $this->givenUser('user@example.com', 'password', array(), 'Bros');
        $request = new DeleteUserRequest($superadmin->getId(), $user->getId());

        $this->interactor->process($request, $this->presenter);

        $user = $this->presenter->getUser();
        $this->assertInstanceOf('SecurityComponent\Model\User', $user);
    }

    protected function buildPresenter()
    {
        return new DeleteUserPresenterSpy();
    }
}

class DeleteUserPresenterSpy implements UserPresenter
{
    private $errors;
    private $user;

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

    public function getUser()
    {
        return $this->user;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }
}