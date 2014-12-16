<?php

namespace Diside\SecurityComponent\Interactor\Interactor;

use Diside\SecurityComponent\Gateway\PageGateway;
use Diside\SecurityComponent\Interactor\AbstractInteractor;
use Diside\SecurityComponent\Interactor\Presenter\PagePresenter;
use Diside\SecurityComponent\Interactor\Presenter;
use Diside\SecurityComponent\Interactor\Request;
use Diside\SecurityComponent\Interactor\Request\GetPageByLanguageAndUrlRequest;

class GetPageInteractor extends AbstractInteractor
{

    public function process(Request $request, Presenter $presenter)
    {
        /** @var PageGateway $pageGateway */
        $pageGateway = $this->getGateway(PageGateway::NAME);

        /** @var PagePresenter $presenter */

        if($request instanceof GetPageByLanguageAndUrlRequest)
            $page = $pageGateway->findOneByLanguageAndUrl($request->language, $request->url);
        else
            $page = $pageGateway->findOneById($request->id);

        if($page == null) {
            $presenter->setErrors(array(PagePresenter::UNDEFINED_PAGE));
            return;
        }

        $presenter->setPage($page);
    }
}