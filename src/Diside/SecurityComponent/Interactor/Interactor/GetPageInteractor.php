<?php

namespace Diside\SecurityComponent\Interactor\Interactor;

use Diside\SecurityComponent\Gateway\PageGateway;
use Diside\SecurityComponent\Interactor\AbstractInteractor;
use Diside\SecurityComponent\Interactor\Presenter\PagePresenter;
use Diside\SecurityComponent\Interactor\Presenter;
use Diside\SecurityComponent\Interactor\Request;
use Diside\SecurityComponent\Interactor\Request\GetPageRequest;

class GetPageInteractor extends AbstractInteractor
{

    public function process(Request $request, Presenter $presenter)
    {
        /** @var PageGateway $pageGateway */
        $pageGateway = $this->getGateway(PageGateway::NAME);

        /** @var GetPageRequest $request */
        /** @var PagePresenter $presenter */

        if ($request->id === null) {
            $presenter->setErrors(array(PagePresenter::UNDEFINED_PAGE_ID));
            return;
        }

        $page = $pageGateway->findOneById($request->id);

        if($page == null) {
            $presenter->setErrors(array(PagePresenter::UNDEFINED_PAGE));
            return;
        }

        $presenter->setPage($page);
    }
}