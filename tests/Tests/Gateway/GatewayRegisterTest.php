<?php

namespace Diside\SecurityComponent\Tests\Interactor;

use Diside\SecurityComponent\Gateway\Gateway;
use Diside\SecurityComponent\Gateway\GatewayRegister;
use Diside\SecurityComponent\Interactor\Interactor\Presenter\ConfirmUserRegistrationPresenter;
use Diside\SecurityComponent\Interactor\Interactor\Presenter\UserPresenter;
use Diside\SecurityComponent\Interactor\Interactor\ConfirmUserRegistrationInteractor;
use Diside\SecurityComponent\Interactor\Interactor\Request\ConfirmUserRegistrationRequest;
use Diside\SecurityComponent\Model\User;

class GatewayRegisterTest extends \PHPUnit_Framework_TestCase
{
    /** @var GatewayRegister */
    private $registry;

    /**
     * @before
     */
    public function setUp()
    {
        $this->registry = new GatewayRegister();
    }

    /**
     * @test
     */
    public function testRegister()
    {
        $gateway = new DummyGateway();
        $this->registry->register($gateway);

        $this->assertThat($this->registry->get('dummy'), $this->equalTo($gateway));
    }

    /**
     * @test
     * @expectedException \Diside\SecurityComponent\Gateway\UndefinedGatewayException
     */
    public function whenRetrievingUndefinedInteractor_thenThrow()
    {
        $this->registry->get('unknown');

    }
}

class DummyGateway implements Gateway
{
    public function findAll(array $filters = array(), $pageIndex = 0, $pageSize = PHP_INT_MAX)
    {
    }

    public function countAll(array $filters = array())
    {
    }

    public function getName()
    {
        return 'dummy';
    }
}