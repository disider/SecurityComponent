<?php

namespace Diside\SecurityComponent\Tests\Interactor\Interactor;

use Diside\SecurityComponent\Gateway\PageGateway;
use Diside\SecurityComponent\Interactor\Interactor\GetPageInteractor;
use Diside\SecurityComponent\Interactor\Presenter\PagePresenter;
use Diside\SecurityComponent\Interactor\Request\GetPageRequest;
use Diside\SecurityComponent\Model\Page;
use Diside\SecurityComponent\Model\PageTranslation;

class GetPageInteractorTest extends BasePageInteractorTest
{
    /** @var GetPageInteractor */
    private $interactor;

    /**
     * @before
     */
    public function setUp()
    {
        parent::setUp();

        $this->interactor = new GetPageInteractor($this->gatewayRegistry, $this->logger);
    }

    /**
     * @test
     */
    public function testProcess()
    {
        $page = $this->givenPage();
        $page->addTranslation($this->givenPageTranslation('en', 'url'));

        $request = new GetPageRequest(null, 'en', 'url');
        $this->interactor->process($request, $this->presenter);

        /** @var Page $user */
        $page = $this->presenter->getPage();
        $this->assertTrue($page->hasTranslation('en'));
    }

    protected function buildPresenter()
    {
        return new PagePresenterMock();
    }

}

class PagePresenterMock implements PagePresenter
{
    private $errors;
    private $user;

    public function getPage()
    {
        return $this->user;
    }

    public function setPage(Page $user)
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