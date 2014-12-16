<?php

namespace Diside\SecurityComponent\Tests\Interactor\Interactor;

use Diside\SecurityComponent\Gateway\PageGateway;
use Diside\SecurityComponent\Gateway\InMemory\InMemoryPageGateway;
use Diside\SecurityComponent\Gateway\InMemory\InMemoryUserGateway;
use Diside\SecurityComponent\Gateway\UserGateway;
use Diside\SecurityComponent\Interactor\Presenter\PagePresenter;
use Diside\SecurityComponent\Interactor\Request\DeletePageRequest;
use Diside\SecurityComponent\Interactor\Interactor\DeletePageInteractor;
use Diside\SecurityComponent\Model\Page;
use Diside\SecurityComponent\Model\User;

class DeletePageInteractorTest extends BaseInteractorTest
{
    /** @var DeletePageInteractor */
    private $interactor;

    /**
     * @before
     */
    public function setUp()
    {
        parent::setUp();

        $this->interactor = new DeletePageInteractor($this->gatewayRegistry, $this->logger);
    }

    /**
     * @test
     */
    public function whenDeletingWithoutExecutor_thenReturnError()
    {
        $request = new DeletePageRequest(null, -1);

        $this->interactor->process($request, $this->presenter);

        $this->assertTrue($this->presenter->hasErrors());
        $this->assertThat($this->presenter->getErrors(), $this->equalTo(array(PagePresenter::UNDEFINED_USER_ID)));
    }

    /**
     * @test
     */
    public function whenDeletingByUnauthorized_thenReturnError()
    {
        $user = $this->givenUser();

        $request = new DeletePageRequest($user->getId(), -1);

        $this->interactor->process($request, $this->presenter);

        $this->assertTrue($this->presenter->hasErrors());
        $this->assertThat($this->presenter->getErrors(), $this->equalTo(array(PagePresenter::FORBIDDEN)));
    }
    
    /**
     * @test
     */
    public function whenDeletingUndefinedPage_thenReturnError()
    {
        $admin = $this->givenAdmin();

        $request = new DeletePageRequest($admin->getId(), -1);

        $this->interactor->process($request, $this->presenter);

        $this->assertTrue($this->presenter->hasErrors());
        $this->assertThat($this->presenter->getErrors(), $this->equalTo(array(PagePresenter::UNDEFINED_PAGE_ID)));
    }

    protected function buildPresenter()
    {
        return new DeletePagePresenterSpy();
    }
}

class DeletePagePresenterSpy implements PagePresenter
{
    private $errors;

    public function getErrors()
    {
        return $this->errors;
    }

    public function setErrors(array $errors)
    {
        $this->errors = $errors;
    }

    public function hasErrors()
    {
        return $this->errors != null;
    }

    public function getPage()
    {
    }

    public function setPage(Page $company)
    {
    }
}