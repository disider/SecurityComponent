<?php

namespace Diside\SecurityComponent\Tests\Interactor\Interactor;

use Diside\SecurityComponent\Interactor\Presenter\RegisterUserPresenter;
use Diside\SecurityComponent\Interactor\Presenter\UserPresenter;
use Diside\SecurityComponent\Interactor\Interactor\RegisterUserInteractor;
use Diside\SecurityComponent\Interactor\Request\RegisterUserRequest;
use Diside\SecurityComponent\Model\User;

class RegisterInteractorTest extends BaseInteractorTest
{
    /** @var RegisterUserInteractor */
    private $interactor;

    /**
     * @before
     */
    public function setUp()
    {
        parent::setUp();

        $this->interactor = new RegisterUserInteractor($this->gatewayRegistry, $this->logger);
    }

    /**
     * @test
     */
    public function whenSaving_thenReturnUserWithRegistrationToken()
    {
        $request = new RegisterUserRequest('adam@example.com', 'adamsecret', 'salt');

        $this->interactor->process($request, $this->presenter);

        $user = $this->presenter->getUser();
        $this->assertNotNull($user->getRegistrationToken());
        $this->assertThat($user->getSalt(), $this->equalTo('salt'));
    }

    /**
     * @test
     */
    public function whenSavingWithoutEmail_thenReturnEmptyEmail()
    {
        $request = new RegisterUserRequest(null, null, null);

        $this->interactor->process($request, $this->presenter);

        $this->assertNull($this->presenter->getUser());

        $this->assertError(0, UserPresenter::EMPTY_EMAIL);
    }

    /**
     * @test
     */
    public function whenSavingNewWithoutPassword_thenReturnEmptyPassword()
    {
        $request = new RegisterUserRequest('adam@example.com', null, null);

        $this->interactor->process($request, $this->presenter);

        $this->assertNull($this->presenter->getUser());
        $this->assertError(0, UserPresenter::EMPTY_PASSWORD);
    }

    /**
     * @test
     */
    public function whenSavingExisting_thenReturnError()
    {
        $this->givenUser('adam@example.com');
        $request = new RegisterUserRequest('adam@example.com', '', '');

        $this->interactor->process($request, $this->presenter);

        $this->assertError(0, UserPresenter::EMAIL_ALREADY_DEFINED);
    }

    protected function buildPresenter()
    {
        return new RegisterUserPresenterSpy();
    }
}

class RegisterUserPresenterSpy implements UserPresenter
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