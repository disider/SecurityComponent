<?php

namespace Diside\SecurityComponent\Tests\Interactor\Interactor;

use Diside\SecurityComponent\Interactor\Interactor\GetUserInteractor;
use Diside\SecurityComponent\Interactor\Presenter\UserPresenter;
use Diside\SecurityComponent\Interactor\Request\GetUserByEmailRequest;
use Diside\SecurityComponent\Interactor\Request\GetUserByIdRequest;
use Diside\SecurityComponent\Interactor\Request\GetUserByRegistrationTokenRequest;
use Diside\SecurityComponent\Interactor\Request\GetUserByResetPasswordTokenRequest;
use Diside\SecurityComponent\Model\User;

class GetUserInteractorTest extends BaseUserInteractorTest
{
    /** @var GetUserInteractor */
    private $interactor;

    /**
     * @before
     */
    public function setUp()
    {
        parent::setUp();

        $this->interactor = new GetUserInteractor($this->gatewayRegistry, $this->logger);
    }

    /**
     * @test
     */
    public function testProcessingRequestByEmail()
    {
        $request = new GetUserByEmailRequest('adam@example.com');

        $this->interactor->process($request, $this->presenter);

        $this->assertTrue($this->presenter->hasErrors());
    }

    /**
     * @test
     */
    public function testProcessingRequestByResetPasswordToken()
    {
        $request = new GetUserByResetPasswordTokenRequest('123');

        $this->interactor->process($request, $this->presenter);

        $this->assertTrue($this->presenter->hasErrors());
    }

    /**
     * @test
     */
    public function testProcessingRequestById()
    {
        $request = new GetUserByIdRequest(1);
        $this->interactor->process($request, $this->presenter);

        $this->assertTrue($this->presenter->hasErrors());
    }

    /**
     * @test
     */
    public function whenUserIsAdmin_thenReturnAdminRole()
    {
        $user = new User(null, 'admin@example.com', '', '');
        $user->addRole(User::ROLE_ADMIN);
        $user = $this->userGateway->save($user);

        $request = new GetUserByIdRequest($user->getId());
        $this->interactor->process($request, $this->presenter);

        /** @var User $user */
        $user = $this->presenter->getUser();
        $this->assertTrue($user->isAdmin());
    }

    protected function buildPresenter()
    {
        return new UserPresenterMock();
    }
}

class UserPresenterMock implements UserPresenter
{
    private $errors;
    private $user;

    public function getUser()
    {
        return $this->user;
    }

    public function setUser(User $user)
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