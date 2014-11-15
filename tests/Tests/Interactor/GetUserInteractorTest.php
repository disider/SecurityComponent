<?php

namespace SecurityComponent\Interactor;

use SecurityComponent\Gateway\InMemory\InMemoryUserGateway;
use SecurityComponent\Interactor\Presenter\UserPresenter;
use SecurityComponent\Interactor\Request\GetUserByEmailRequest;
use SecurityComponent\Interactor\Request\GetUserByIdRequest;
use SecurityComponent\Interactor\Request\GetUserByRegistrationTokenRequest;
use SecurityComponent\Interactor\Request\GetUserByResetPasswordTokenRequest;
use SecurityComponent\Model\User;
use SecurityComponent\Tests\Interactor\BaseUserInteractorTest;

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

        $this->interactor = new GetUserInteractor($this->userGateway);
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