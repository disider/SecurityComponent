<?php

namespace Diside\SecurityComponent\Tests\Interactor\Interactor;

use Diside\SecurityComponent\Interactor\Interactor\RequestResetPasswordInteractor;
use Diside\SecurityComponent\Interactor\Presenter\RequestResetPasswordPresenter;
use Diside\SecurityComponent\Interactor\Presenter\UserPresenter;
use Diside\SecurityComponent\Interactor\Request\RequestResetPasswordRequest;
use Diside\SecurityComponent\Model\User;

class RequestResetPasswordInteractorTest extends BaseUserInteractorTest
{
    /** @var RequestResetPasswordInteractor */
    private $interactor;

    /**
     * @before
     */
    public function setUp()
    {
        parent::setUp();

        $this->interactor = new RequestResetPasswordInteractor($this->gatewayRegistry, $this->logger);
    }

    /**
     * @test
     */
    public function whenRequesting_thenReturnUserWithResetPasswordToken()
    {
        $this->givenUser('adam@example.com');
        $request = new RequestResetPasswordRequest('adam@example.com');

        $this->interactor->process($request, $this->presenter);

        /** @var User $user */
        $user = $this->presenter->getUser();
        $this->assertNotNull($user->getResetPasswordToken());
    }

    /**
     * @test
     */
    public function whenRequestingWithoutEmail_thenReturnEmptyEmail()
    {
        $request = new RequestResetPasswordRequest(null);

        $this->interactor->process($request, $this->presenter);

        $this->assertNull($this->presenter->getUser());

        $this->assertError(0, UserPresenter::EMPTY_EMAIL);
    }

    /**
     * @test
     */
    public function whenRequestingForUnknownUser_thenReturnUndefinedUser()
    {
        $request = new RequestResetPasswordRequest('unknown@example.com');

        $this->interactor->process($request, $this->presenter);

        $this->assertNull($this->presenter->getUser());
        $this->assertError(0, UserPresenter::UNDEFINED_USER);
    }

    protected function buildPresenter()
    {
        return new RequestResetPasswordPresenterSpy();
    }
}

class RequestResetPasswordPresenterSpy implements UserPresenter
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