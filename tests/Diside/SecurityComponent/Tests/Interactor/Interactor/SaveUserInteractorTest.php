<?php

namespace Diside\SecurityComponent\Tests\Interactor\Interactor;

use Diside\SecurityComponent\Gateway\UserGateway;
use Diside\SecurityComponent\Interactor\Interactor\SaveUserInteractor;
use Diside\SecurityComponent\Interactor\Presenter\UserPresenter;
use Diside\SecurityComponent\Interactor\Request\ChangePasswordRequest;
use Diside\SecurityComponent\Interactor\Request\SaveUserRequest;
use Diside\SecurityComponent\Model\User;

class SaveUserInteractorTest extends BaseInteractorTest
{
    /** @var SaveUserInteractor */
    private $interactor;

    /**
     * @before
     */
    public function setUp()
    {
        parent::setUp();

        $this->interactor = new SaveUserInteractor($this->gatewayRegistry, $this->logger);
    }

    /**
     * @test
     */
    public function whenSavingUnauthorized_thenReturnUnauthorized()
    {
        $request = new SaveUserRequest(null, null, 'adam@example.com', null, null, true, array());

        $this->interactor->process($request, $this->presenter);

        $this->assertNull($this->presenter->getUser());
        $this->assertTrue($this->presenter->hasErrors());
        $this->assertThat($this->presenter->getErrors(), $this->equalTo(array(UserPresenter::UNDEFINED_USER_ID)));
    }

    /**
     * @test
     */
    public function whenSavingWithoutEmail_thenReturnEmptyEmail()
    {
        $user = $this->givenUser();

        $request = new SaveUserRequest($user->getId(), null, null, null, null, true, array());

        $this->interactor->process($request, $this->presenter);

        $this->assertNull($this->presenter->getUser());
        $this->assertTrue($this->presenter->hasErrors());
        $this->assertThat($this->presenter->getErrors(), $this->equalTo(array(UserPresenter::EMPTY_EMAIL)));
    }

    /**
     * @test
     */
    public function whenSavingNewWithoutPassword_thenReturnEmptyPassword()
    {
        $user = $this->givenUser();
        $request = new SaveUserRequest($user->getId(), null, 'adam@example.com', null, null, true, array());

        $this->interactor->process($request, $this->presenter);

        $this->assertNull($this->presenter->getUser());
        $this->assertTrue($this->presenter->hasErrors());
        $this->assertThat($this->presenter->getErrors(), $this->equalTo(array(UserPresenter::EMPTY_PASSWORD)));
    }

    /**
     * @test
     */
    public function whenSavingExistingWithoutPassword_thenPasswordIsNotUpdated()
    {
        $user = new User(null, 'adam@example.com', 'adamsecret', 'salt');

        $user = $this->getGateway(UserGateway::NAME)->save($user);

        $request = new SaveUserRequest($user->getId(), $user->getId(), 'adam@example.com', null, null, true, array());

        $this->interactor->process($request, $this->presenter);

        $savedUser = $this->presenter->getUser();

        $this->assertFalse($this->presenter->hasErrors());
        $this->assertThat($savedUser->getPassword(), $this->equalTo($user->getPassword()));
    }

    /**
     * @test
     */
    public function whenSavingNewUser_thenReturnSavedUser()
    {
        $admin = $this->givenUser();

        $request = new SaveUserRequest($admin->getId(), null, 'test@example.com', 'password', null, true, array());

        $this->interactor->process($request, $this->presenter);

        $user = $this->presenter->getUser();

        $this->assertFalse($this->presenter->hasErrors());
        $this->assertNotNull($user->getId());
        $this->assertUser($user, $request);
    }

    /**
     * @test
     */
    public function whenSavingByAdmin_thenNewUserHasSameCompany()
    {
        $admin = $this->givenAdmin('Acme');

        $request = new SaveUserRequest($admin->getId(), null, 'user@example.com', 'usersecret', null, true, array());

        $this->interactor->process($request, $this->presenter);

        $user = $this->presenter->getUser();

        $this->assertNotNull($user->getCompanyId());
        $this->assertThat($user->getCompanyId(), $this->equalTo($admin->getCompanyId()));
    }

    /**
     * @test
     */
    public function whenSavingBySuperadminWithCompany_thenNewUserHasCompany()
    {
        $company = $this->givenCompany('Acme');
        $superadmin = $this->givenSuperadmin();

        $request = new SaveUserRequest(
            $superadmin->getId(), null, 'user@example.com', 'usersecret', null, true, array()
        );
        $request->companyId = $company->getId();

        $this->interactor->process($request, $this->presenter);

        $user = $this->presenter->getUser();

        $this->assertNotNull($user->getCompanyId());
        $this->assertThat($user->getCompanyId(), $this->equalTo($company->getId()));
    }

    /**
     * @test
     */
    public function whenSavingNewPassword_thenReturnSavedPassword()
    {
        $user = $this->givenUser('adam@example.com', 'adamsecret');

        $request = new ChangePasswordRequest($user->getId(), $user->getId(), 'adamsecret', 'newsecret');

        $this->interactor->process($request, $this->presenter);

        $user = $this->presenter->getUser();

        $this->assertNotNull($user->getId());

        $this->assertThat($user->getPassword(), $this->equalTo('newsecret'));
    }

    /**
     * @test
     */
    public function whenSavingPasswordForAnotherUser_thenReturnError()
    {
        $user1 = $this->givenUser();
        $user2 = $this->givenUser('adam@example.com', 'adamsecret');

        $request = new ChangePasswordRequest($user1->getId(), $user2->getId(), 'adamsecret', 'newsecret');

        $this->interactor->process($request, $this->presenter);

        $this->assertError(0, UserPresenter::FORBIDDEN);
    }

    /**
     * @test
     */
    public function whenSavingWrongPassword_thenReturnError()
    {
        $user = $this->givenUser('adam@example.com', 'adamsecret');

        $request = new ChangePasswordRequest($user->getId(), $user->getId(), 'wrongsecret', 'newsecret');

        $this->interactor->process($request, $this->presenter);

        $this->assertError(0, UserPresenter::WRONG_PASSWORD);
    }

    /**
     * @test
     */
    public function whenSavingWithEmptyCurrentPassword_thenReturnError()
    {
        $user = $this->givenUser('adam@example.com', 'adamsecret');

        $request = new ChangePasswordRequest($user->getId(), $user->getId(), '', 'newsecret');

        $this->interactor->process($request, $this->presenter);

        $this->assertError(0, UserPresenter::EMPTY_CURRENT_PASSWORD);
    }

    /**
     * @test
     */
    public function whenSavingWithEmptyNewPassword_thenReturnError()
    {
        $user = $this->givenUser('adam@example.com', 'adamsecret');

        $request = new ChangePasswordRequest($user->getId(), $user->getId(), 'adamsecret', '');

        $this->interactor->process($request, $this->presenter);

        $this->assertError(0, UserPresenter::EMPTY_NEW_PASSWORD);
    }

    /**
     * @test
     */
    public function whenSavingManagerWithoutCompany_thenReturnError()
    {
        $superadmin = $this->givenSuperadmin();

        $request = new SaveUserRequest(
            $superadmin->getId(),
            null,
            'manager@example.com',
            'managersecret',
            null,
            true,
            array(User::ROLE_MANAGER)
        );

        $this->interactor->process($request, $this->presenter);

        $this->assertNull($this->presenter->getUser());
        $this->assertTrue($this->presenter->hasErrors());
        $this->assertThat($this->presenter->getErrors(), $this->equalTo(array(UserPresenter::UNDEFINED_COMPANY)));
    }

    /**
     * @test
     */
    public function whenSavingAdminWithoutCompany_thenReturnError()
    {
        $superadmin = $this->givenSuperadmin();

        $request = new SaveUserRequest(
            $superadmin->getId(),
            null,
            'admin@example.com',
            'adminsecret',
            null,
            true,
            array(User::ROLE_ADMIN)
        );

        $this->interactor->process($request, $this->presenter);

        $this->assertNull($this->presenter->getUser());
        $this->assertTrue($this->presenter->hasErrors());
        $this->assertThat($this->presenter->getErrors(), $this->equalTo(array(UserPresenter::UNDEFINED_COMPANY)));
    }

    /**
     * @test
     */
    public function whenSavingManagerByAdmin_thenReturnUser()
    {
        $admin = $this->givenAdmin('Acme');

        $request = new SaveUserRequest(
            $admin->getId(),
            null,
            'manager@example.com',
            'managersecret',
            null,
            true,
            array(User::ROLE_MANAGER)
        );

        $this->interactor->process($request, $this->presenter);

        $this->assertFalse($this->presenter->hasErrors());
        $this->assertNotNull($this->presenter->getUser());
    }

    /**
     * @test
     */
    public function whenSavingExistingUser_thenReturnSavedUser()
    {
        $admin = $this->givenAdmin('Acme');

        $user = new User(1, 'adam@example.com', 'adamsecret', '');
        $this->getGateway(UserGateway::NAME)->save($user);

        $request = new SaveUserRequest(
            $admin->getId(),
            1,
            'newemail@example.com',
            'newpassword',
            '',
            true,
            $user->getRoles()
        );

        $this->interactor->process($request, $this->presenter);

        $user = $this->presenter->getUser();

        $this->assertUser($user, $request);

        $this->assertThat(count($this->getGateway(UserGateway::NAME)->findAll()), $this->equalTo(1));
    }

    /**
     * @test
     */
    public function whenSavingExistingUserByAdminForSameCompany_thenReturnUser()
    {
        $admin = $this->givenAdmin('Acme');

        $user = $this->givenUser('user@example.com', 'password', array(), 'Acme');

        $request = new SaveUserRequest(
            $admin->getId(),
            $user->getId(),
            'newemail@example.com',
            'newpassword',
            '',
            true,
            array(User::ROLE_MANAGER)
        );
        $request->companyId = $user->getCompanyId();

        $this->interactor->process($request, $this->presenter);

        $user = $this->presenter->getUser();

        $this->assertUser($user, $request);
    }

    /**
     * @test
     */
    public function whenSavingExistingUserWithoutPassword_thenDoNotChangePassword()
    {
        $user = $this->givenUser('user@example.com', 'password', array());
        $user->setSalt('1234');

        $request = new SaveUserRequest(
            $user->getId(),
            $user->getId(),
            'newemail@example.com',
            null,
            null,
            true,
            $user->getRoles()
        );

        $this->interactor->process($request, $this->presenter);

        $user = $this->presenter->getUser();

        $this->assertUser($user, $request);
        $this->assertThat($user->getSalt(), $this->equalTo('1234'));
        $this->assertThat($user->getPassword(), $this->equalTo('password'));
    }

    /**
     * @test
     */
    public function whenSavingExistingUserWithoutPassword_thenChangePassword()
    {
        $user = $this->givenUser('user@example.com', 'password', array());
        $user->setSalt('1234');

        $request = new SaveUserRequest(
            $user->getId(),
            $user->getId(),
            'newemail@example.com',
            'newpassword',
            null,
            true,
            $user->getRoles()
        );

        $this->interactor->process($request, $this->presenter);

        $user = $this->presenter->getUser();

        $this->assertThat($user->getPassword(), $this->equalTo('newpassword'));
    }

    /**
     * @test
     */
    public function whenSavingExistingUserByAdminForDifferentCompany_thenReturnError()
    {
        $admin = $this->givenAdmin('Acme');

        $user = $this->givenUser('user@example.com', 'password', array(), 'Bros');

        $request = new SaveUserRequest(
            $admin->getId(),
            $user->getId(),
            'newemail@example.com',
            'newpassword',
            null,
            true,
            $user->getRoles()
        );
        $request->companyId = $user->getCompanyId();

        $this->interactor->process($request, $this->presenter);

        $this->assertError(0, UserPresenter::FORBIDDEN);
    }

    /**
     * @test
     */
    public function whenSavingExistingUserBySuperadmin_thenSaveExtraFields()
    {
        $superadmin = $this->givenSuperadmin();
        $company = $this->givenCompany('Acme');

        $user = $this->givenUser();

        $request = new SaveUserRequest(
            $superadmin->getId(),
            $user->getId(),
            'newemail@example.com',
            'newpassword',
            null,
            true,
            array(User::ROLE_MANAGER)
        );
        $request->companyId = $company->getId();
        $request->maximumChecklistTemplates = 10;

        $this->interactor->process($request, $this->presenter);

        $user = $this->presenter->getUser();

        $this->assertUser($user, $request);

        $this->assertThat(count($this->getGateway(UserGateway::NAME)->findAll()), $this->equalTo(1));
    }

    private function assertUser(User $user, SaveUserRequest $request)
    {
        $this->assertNotNull($user->getSalt());
        $this->assertThat($user->getEmail(), $this->equalTo($request->email));
        $this->assertThat(
            $user->getRoles(),
            $this->equalTo(array_unique(array_merge($request->roles, array(User::ROLE_USER))))
        );
        $this->assertThat($user->getCompanyId(), $this->equalTo($request->companyId));
    }

    protected function buildPresenter()
    {
        return new SaveUserPresenterSpy();
    }
}

class SaveUserPresenterSpy implements UserPresenter
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