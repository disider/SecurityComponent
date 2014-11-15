<?php

namespace SecurityComponent\Tests\Interactor;

use SecurityComponent\Gateway\CompanyGateway;
use SecurityComponent\Gateway\InMemory\InMemoryCompanyGateway;
use SecurityComponent\Gateway\InMemory\InMemoryLogGateway;
use SecurityComponent\Gateway\InMemory\InMemoryUserGateway;
use SecurityComponent\Gateway\LogGateway;
use SecurityComponent\Gateway\UserGateway;
use SecurityComponent\Interactor\Presenter;
use SecurityComponent\Logger\Logger;
use SecurityComponent\Model\Company;
use SecurityComponent\Model\ExtendedUser;
use SecurityComponent\Model\User;

abstract class BaseUserInteractorTest extends \PHPUnit_Framework_TestCase
{
    /** @var UserGateway */
    protected $userGateway;

    /** @var CompanyGateway */
    protected $companyGateway;

    /** @var Presenter */
    protected $presenter;

    /**
     * @before
     */
    public function setUp()
    {
        $this->companyGateway = new InMemoryCompanyGateway();
        $this->userGateway = new InMemoryUserGateway();

        $this->presenter = $this->buildPresenter();
    }

    abstract protected function buildPresenter();

    protected function givenSuperadmin($email = 'superadmin@example.com')
    {
        return $this->givenUser($email, 'password', array(User::ROLE_SUPERADMIN, User::ROLE_ADMIN, User::ROLE_MANAGER));
    }

    protected function givenAdmin($companyName = 'Acme', $email = 'admin@example.com')
    {
        return $this->givenUser($email, 'password', array(User::ROLE_ADMIN, User::ROLE_MANAGER), $companyName);
    }

    protected function givenManager($companyName = 'Acme', $email = 'manager@example.com')
    {
        return $this->givenUser($email, 'password', array(User::ROLE_MANAGER), $companyName);
    }

    protected function givenUser($email = 'user@example.com', $password = 'password', $roles = array(User::ROLE_USER), $companyName = null)
    {
        $user = $this->buildUser($email, $password, $roles, $companyName);
        $user->setActive(true);

        return $this->userGateway->save($user);
    }

    protected function givenInactiveUser($email = 'user@example.com', $password = 'password', $roles = array(User::ROLE_USER), $companyName = null)
    {
        $user = $this->buildUser($email, $password, $roles, $companyName);

        return $this->userGateway->save($user);
    }

    protected function givenCompany($companyName)
    {
        $company = $this->companyGateway->findOneByName($companyName);

        if ($company == null) {
            $company = new Company(null, $companyName);

            $company = $this->companyGateway->save($company);
        }

        return $company;
    }


    protected function assertError($i, $error)
    {
        $this->assertTrue($this->presenter->hasErrors());

        $errors = $this->presenter->getErrors();

        $this->assertThat($errors[$i], $this->equalTo($error));
    }

    protected function buildUser($email, $password, $roles, $companyName)
    {
        $user = new ExtendedUser(null, $email, $password, '');

        $user->setRoles($roles);

        if ($companyName != null) {
            $company = $this->givenCompany($companyName);

            $user->setCompany($company);
        }

        return $user;
    }

    protected function assertLog($action)
    {
        $logs = $this->logGateway->findAll();
        $this->assertThat($logs[0]->getAction(), $this->equalTo($action));
    }

}
