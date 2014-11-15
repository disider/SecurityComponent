<?php

namespace SecurityComponent\Tests\Interactor;

use SecurityComponent\Interactor\Presenter\ResetPasswordPresenter;
use SecurityComponent\Interactor\Presenter\UserPresenter;
use SecurityComponent\Interactor\ResetPasswordInteractor;
use SecurityComponent\Interactor\Request\ResetPasswordRequest;
use SecurityComponent\Model\User;

class ResetPasswordInteractorTest extends BaseUserInteractorTest
{
    /** @var ResetPasswordInteractor */
    private $interactor;

    /**
     * @before
     */
    public function setUp()
    {
        parent::setUp();

        $this->interactor = new ResetPasswordInteractor($this->userGateway);
    }

    /**
     * @test
     */
    public function whenResettingWithValidPassword_thenReturnUser()
    {
        $user = new User(null, 'adam@example.com', 'adamsecret', '');
        $user = $this->userGateway->save($user);
        $request = new ResetPasswordRequest($user->getId(), 'newsecret');

        $this->interactor->process($request, $this->presenter);

        /** @var User $user */
        $user = $this->presenter->getUser();
        $this->assertNull($user->getResetPasswordToken());
        $this->assertThat($user->getPassword(), $this->equalTo('newsecret'));
    }

    /**
     * @test
     */
    public function whenResettingWithoutUser_thenReturnError()
    {
        $request = new ResetPasswordRequest(null, null);

        $this->interactor->process($request, $this->presenter);

        $this->assertNull($this->presenter->getUser());

        $this->assertError(0, UserPresenter::UNDEFINED_USER_ID);
    }

    /**
     * @test
     */
    public function whenResettingWithoutNewPassword_thenReturnError()
    {
        $request = new ResetPasswordRequest(-1, null);

        $this->interactor->process($request, $this->presenter);

        $this->assertNull($this->presenter->getUser());

        $this->assertError(0, UserPresenter::EMPTY_PASSWORD);
    }

    /**
     * @test
     */
    public function whenResettingForUnknownUser_thenReturnError()
    {
        $request = new ResetPasswordRequest('unknown@example.com', '123');

        $this->interactor->process($request, $this->presenter);

        $this->assertNull($this->presenter->getUser());
        $this->assertError(0, UserPresenter::UNDEFINED_USER);
    }

    protected function buildPresenter()
    {
        return new ResetPasswordPresenterSpy();
    }
}

class ResetPasswordPresenterSpy implements UserPresenter
{
    private $user;
    private $errors;

    public function getErrors()
    {
        return $this->errors;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setErrors(array $errors)
    {
        $this->errors = $errors;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function hasErrors()
    {
        return $this->errors != null;
    }
}