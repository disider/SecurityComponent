<?php

namespace Diside\SecurityComponent\Interactor\Interactor;

use Diside\SecurityComponent\Gateway\PageGateway;
use Diside\SecurityComponent\Gateway\UserGateway;
use Diside\SecurityComponent\Interactor\AbstractInteractor;
use Diside\SecurityComponent\Interactor\Presenter;
use Diside\SecurityComponent\Interactor\Presenter\PagesPresenter;
use Diside\SecurityComponent\Interactor\Request;
use Diside\SecurityComponent\Interactor\Request\FindPagesRequest;

class FindPagesInteractor extends AbstractInteractor
{
    public function process(Request $request, Presenter $presenter)
    {
        /** @var PageGateway $pageGateway */
        $pageGateway = $this->getGateway(PageGateway::NAME);

        /** @var UserGateway $userGateway */
        $userGateway = $this->getGateway(UserGateway::NAME);

        /** @var FindPagesRequest $request */
        /** @var PagesPresenter $presenter */

        $filters = array();

        $companies = $pageGateway->findAll($filters, $request->pageIndex, $request->pageSize);

        $total = $pageGateway->countAll();

        $presenter->setPages($companies);
        $presenter->setTotalPages($total);
    }
}