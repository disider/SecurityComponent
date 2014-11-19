<?php

namespace Diside\SecurityComponent\Tests\Interactor\Interactor;

use Diside\SecurityComponent\Interactor\Presenter\ConfirmUserRegistrationPresenter;
use Diside\SecurityComponent\Interactor\Presenter\UserPresenter;
use Diside\SecurityComponent\Interactor\Interactor\ConfirmUserRegistrationInteractor;
use Diside\SecurityComponent\Interactor\Request\ConfirmUserRegistrationRequest;
use Diside\SecurityComponent\Model\User;

class ConfirmUserRegistrationInteractorTest extends BaseUserInteractorTest
{
    /** @var ConfirmUserRegistrationInteractor */
    private $interactor;

    /**
     * @before
     */
    public function setUp()
    {
        parent::setUp();

        $this->interactor = new ConfirmUserRegistrationInteractor($this->gatewayRegistry, $this->logger);
    }

    /**
     * @test
     */
    public function whenConfirmingValidToken_thenReturnUser()
    {
        $user = $this->givenUser();
        $user->setRegistrationToken('12345678');

        $this->userGateway->save($user);

        $request = new ConfirmUserRegistrationRequest('12345678');

        $this->interactor->process($request, $this->presenter);

        $user = $this->presenter->getUser();
        $this->assertNull($user->getRegistrationToken());
        $this->assertTrue($user->isActive());
    }

    /**
     * @test
     */
    public function whenConfirmingUndefinedToken_thenReturnError()
    {
        $request = new ConfirmUserRegistrationRequest(null);

        $this->interactor->process($request, $this->presenter);

        $this->assertNull($this->presenter->getUser());

        $this->assertError(0, UserPresenter::EMPTY_REGISTRATION_TOKEN);
    }

    /**
     * @test
     */
    public function whenConfirmingUnknownToken_thenReturnError()
    {
        $request = new ConfirmUserRegistrationRequest('12345678');

        $this->interactor->process($request, $this->presenter);

        $this->assertNull($this->presenter->getUser());

        $this->assertError(0, UserPresenter::UNDEFINED_USER);
    }

    protected function buildPresenter()
    {
        return new ConfirmUserRegistrationPresenterSpy();
    }
}

class ConfirmUserRegistrationPresenterSpy implements UserPresenter
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