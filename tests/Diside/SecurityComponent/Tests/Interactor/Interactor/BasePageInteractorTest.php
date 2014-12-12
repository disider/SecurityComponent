<?php

namespace Diside\SecurityComponent\Tests\Interactor\Interactor;

use Diside\SecurityComponent\Gateway\PageGateway;
use Diside\SecurityComponent\Interactor\Interactor\GetPageInteractor;
use Diside\SecurityComponent\Interactor\Presenter\PagePresenter;
use Diside\SecurityComponent\Interactor\Request\GetPageRequest;
use Diside\SecurityComponent\Model\Page;
use Diside\SecurityComponent\Model\PageTranslation;

abstract class BasePageInteractorTest extends BaseInteractorTest
{
    /**
     * @return Page
     */
    protected function givenPage()
    {
        $page = new Page(null);
        $page = $this->getGateway(PageGateway::NAME)->save($page);

        return $page;
    }

    protected function givenPageTranslation($language, $url)
    {
        return new PageTranslation(null, $language, $url, '', '');
    }
}
