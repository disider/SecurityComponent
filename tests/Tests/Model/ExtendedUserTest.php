<?php

namespace SecurityComponent\Tests\Model;

use SecurityComponent\Model\ExtendedUser;
use SecurityComponent\Model\User;

class ExtendedUserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function testConstructor()
    {
        $user = new ExtendedUser(null, 'email@example.com', 'password', 'salt');

        $this->assertThat($user->countChecklistTemplates(), $this->equalTo(0));
        $this->assertThat($user->countRunningChecklists(), $this->equalTo(0));
        $this->assertThat($user->getMaxChecklistTemplates(), $this->equalTo(ExtendedUser::DEFAULT_MAXIMUM_CHECKLIST_TEMPLATES));
        $this->assertThat($user->countShareRequests(), $this->equalTo(0));
        $this->assertFalse($user->hasShareRequests());
    }

    /**
     * @test
     */
    public function testFreeUserRole()
    {
        $user = new User(null, 'email@example.com', 'password', 'salt');
        $user->addRole(User::ROLE_FREE_USER);

        $this->assertTrue($user->hasRole(User::ROLE_FREE_USER));
        $this->assertTrue($user->isFreeUser());
    }

    /**
     * @test
     */
    public function testCountChecklistTemplates()
    {
        $user = new ExtendedUser(null, 'email@example.com', 'password', 'salt');
        $user->setCountChecklistTemplates(1);

        $this->assertThat($user->countChecklistTemplates(), $this->equalTo(1));
    }

    /**
     * @test
     */
    public function testCountRunningChecklists()
    {
        $user = new ExtendedUser(null, 'email@example.com', 'password', 'salt');
        $user->setCountRunningChecklists(1);

        $this->assertThat($user->countRunningChecklists(), $this->equalTo(1));
    }

    /**
     * @test
     */
    public function testMaximumChecklistTemplates()
    {
        $user = new ExtendedUser(null, 'email@example.com', 'password', 'salt');
        $user->setMaxChecklistTemplates(10);

        $this->assertThat($user->getMaxChecklistTemplates(), $this->equalTo(10));
    }

    /** @test */
    public function testCountShareRequests()
    {
        $user = new ExtendedUser(null, 'email@example.com', 'password', 'salt');
        $user->setCountShareRequests(10);

        $this->assertThat($user->countShareRequests(), $this->equalTo(10));
        $this->assertTrue($user->hasShareRequests());
    }
}