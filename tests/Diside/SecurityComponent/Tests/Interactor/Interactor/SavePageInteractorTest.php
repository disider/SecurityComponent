<?php

namespace Diside\SecurityComponent\Tests\Interactor\Interactor;

use Diside\SecurityComponent\Gateway\PageGateway;
use Diside\SecurityComponent\Interactor\Interactor\SavePageInteractor;
use Diside\SecurityComponent\Interactor\Presenter\PagePresenter;
use Diside\SecurityComponent\Interactor\Request\ChangePasswordRequest;
use Diside\SecurityComponent\Interactor\Request\SavePageRequest;
use Diside\SecurityComponent\Model\Page;

class SavePageInteractorTest extends BasePageInteractorTest
{
    /** @var SavePageInteractor */
    private $interactor;

    /**
     * @before
     */
    public function setUp()
    {
        parent::setUp();

        $this->interactor = new SavePageInteractor($this->gatewayRegistry, $this->logger);
    }

    /**
     * @test
     */
    public function whenSavingUnauthorized_thenReturnUnauthorized()
    {
        $request = new SavePageRequest(null, null, '', '', '', '');

        $this->interactor->process($request, $this->presenter);

        $this->assertNull($this->presenter->getPage());
        $this->assertTrue($this->presenter->hasErrors());
        $this->assertThat($this->presenter->getErrors(), $this->equalTo(array(PagePresenter::UNDEFINED_USER_ID)));
    }

    /**
     * @test
     */
    public function testSuccess()
    {
        $user = $this->givenUser();
        $request = new SavePageRequest($user->getId(), null, 'en', 'url', 'title', 'content');
        $request->addTranslation(null, 'it', 'it/url', 'titolo', 'contenuto');

        $this->interactor->process($request, $this->presenter);

        $page = $this->presenter->getPage();

        $this->assertNotNull($page);
        $this->assertThat($page->countTranslations(), $this->equalTo(1));
    }

    protected function buildPresenter()
    {
        return new SavePagePresenterSpy();
    }
}

class SavePagePresenterSpy implements PagePresenter
{
    private $user;
    private $errors;

    public function getErrors()
    {
        return $this->errors;
    }

    public function getPage()
    {
        return $this->user;
    }

    public function setErrors(array $errors)
    {
        $this->errors = $errors;
    }

    public function setPage(Page $user)
    {
        $this->user = $user;
    }

    public function hasErrors()
    {
        return $this->errors != null;
    }
}