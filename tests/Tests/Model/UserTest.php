<?php

namespace Diside\SecurityComponent\Tests\Model;

use Diside\SecurityComponent\Model\Company;
use Diside\SecurityComponent\Model\User;

class UserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function testConstructor()
    {
        $user = new User(null, 'email@example.com', 'password', 'salt');

        $this->assertThat($user->getEmail(), $this->equalTo('email@example.com'));
        $this->assertThat($user->getPassword(), $this->equalTo('password'));
        $this->assertThat($user->getSalt(), $this->equalTo('salt'));
        $this->assertThat($user->getRoles(), $this->equalTo(array('ROLE_USER')));
        $this->assertFalse($user->isActive());
        $this->assertNull($user->getCompany());
        $this->assertNull($user->getCompanyId());
    }

    /**
     * @test
     */
    public function whenSettingActive_thenIsActive()
    {
        $user = new User(null, 'email@example.com', 'password', 'salt');
        $user->setActive(true);
        $this->assertTrue($user->isActive());
    }

    /**
     * @test
     */
    public function testSuperadminRole()
    {
        $user = new User(null, 'email@example.com', 'password', 'salt');
        $user->addRole(User::ROLE_SUPERADMIN);

        $this->assertTrue($user->hasRole(User::ROLE_SUPERADMIN));
        $this->assertTrue($user->isSuperadmin());
    }

    /**
     * @test
     */
    public function testAdminRole()
    {
        $user = new User(null, 'email@example.com', 'password', 'salt');
        $user->addRole(User::ROLE_ADMIN);

        $this->assertTrue($user->hasRole(User::ROLE_ADMIN));
        $this->assertTrue($user->isAdmin());
    }

    /**
     * @test
     */
    public function testManagerRole()
    {
        $user = new User(null, 'email@example.com', 'password', 'salt');
        $user->addRole(User::ROLE_MANAGER);

        $this->assertTrue($user->hasRole(User::ROLE_MANAGER));
        $this->assertTrue($user->isManager());
    }

    /**
     * @test
     */
    public function testAddRole()
    {
        $user = new User(null, 'email@example.com', 'password', 'salt');
        $user->addRole(User::ROLE_USER);

        $this->assertThat($user->getRoles(), $this->equalTo(array('ROLE_USER')));
    }

    /**
     * @test
     */
    public function testSetRoles()
    {
        $user = new User(null, 'email@example.com', 'password', 'salt');
        $user->setRoles(array(User::ROLE_MANAGER, User::ROLE_MANAGER));

        $this->assertThat($user->getRoles(), $this->equalTo(array('ROLE_MANAGER', 'ROLE_USER')));
    }

    /**
     * @test
     */
    public function testSetCompany()
    {
        $company = new Company(1, 'Acme');
        $user = new User(null, 'email@example.com', 'password', 'salt');
        $user->setCompany($company);

        $this->assertThat($user->getCompany(), $this->equalTo($company));
        $this->assertThat($user->getCompanyId(), $this->equalTo(1));
    }

}