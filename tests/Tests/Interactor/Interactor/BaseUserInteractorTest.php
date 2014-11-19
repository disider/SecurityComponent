<?php

namespace Diside\SecurityComponent\Tests\Interactor\Interactor;

use Diside\SecurityComponent\Gateway\CompanyGateway;
use Diside\SecurityComponent\Gateway\GatewayRegister;
use Diside\SecurityComponent\Gateway\InMemory\InMemoryCompanyGateway;
use Diside\SecurityComponent\Gateway\InMemory\InMemoryLogGateway;
use Diside\SecurityComponent\Gateway\InMemory\InMemoryUserGateway;
use Diside\SecurityComponent\Gateway\LogGateway;
use Diside\SecurityComponent\Gateway\UserGateway;
use Diside\SecurityComponent\Interactor\Presenter;
use Diside\SecurityComponent\Logger\Logger;
use Diside\SecurityComponent\Model\Company;
use Diside\SecurityComponent\Model\User;

abstract class BaseUserInteractorTest extends \PHPUnit_Framework_TestCase
{
    /** @var UserGateway */
    protected $userGateway;

    /** @var CompanyGateway */
    protected $companyGateway;

    /** @var LogGateway */
    protected $logGateway;

    /** @var Presenter */
    protected $presenter;

    /** @var GatewayRegister */
    protected $gatewayRegistry;

    /** @var Logger */
    protected $logger;

    /**
     * @before
     */
    public function setUp()
    {
        $this->companyGateway = new InMemoryCompanyGateway();
        $this->userGateway = new InMemoryUserGateway();
        $this->logGateway = new InMemoryLogGateway();

        $this->gatewayRegistry = new GatewayRegister();
        $this->gatewayRegistry->register($this->userGateway);
        $this->gatewayRegistry->register($this->companyGateway);
        $this->gatewayRegistry->register($this->logGateway);

        $this->logger = new Logger($this->logGateway);

        $this->presenter = $this->buildPresenter();
    }

    abstract protected function buildPresenter();

    protected function givenCompany($companyName)
    {
        $company = $this->companyGateway->findOneByName($companyName);

        if ($company == null) {
            $company = new Company(null, $companyName);

            $company = $this->companyGateway->save($company);
        }

        return $company;
    }

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

    protected function buildUser($email, $password, $roles, $companyName)
    {
        $user = new User(null, $email, $password, '');

        $user->setRoles($roles);

        if ($companyName != null) {
            $company = $this->givenCompany($companyName);

            $user->setCompany($company);
        }

        return $user;
    }

    protected function assertError($i, $error)
    {
        $this->assertTrue($this->presenter->hasErrors());

        $errors = $this->presenter->getErrors();

        $this->assertThat($errors[$i], $this->equalTo($error));
    }

    protected function assertLog($action)
    {
        $logs = $this->logGateway->findAll();
        $this->assertThat($logs[0]->getAction(), $this->equalTo($action));
    }

}
