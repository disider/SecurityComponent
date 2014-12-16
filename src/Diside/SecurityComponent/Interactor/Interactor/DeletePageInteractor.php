<?php

namespace Diside\SecurityComponent\Interactor\Interactor;

use Diside\SecurityComponent\Gateway\PageGateway;
use Diside\SecurityComponent\Gateway\UserGateway;
use Diside\SecurityComponent\Interactor\AbstractInteractor;
use Diside\SecurityComponent\Interactor\Presenter;
use Diside\SecurityComponent\Interactor\Presenter\PagePresenter;
use Diside\SecurityComponent\Interactor\Presenter\DeletePagePresenter;
use Diside\SecurityComponent\Interactor\Request;
use Diside\SecurityComponent\Interactor\Request\DeletePageRequest;

class DeletePageInteractor extends AbstractInteractor
{
    public function process(Request $request, Presenter $presenter)
    {
        /** @var DeletePageRequest $request */
        /** @var PagePresenter $presenter */

        if (!$executor = $this->checkExecutor($request->executorId, $presenter)) {
            return;
        }

        if(!$executor->isAdmin()) {
            $presenter->setErrors(array(Presenter::FORBIDDEN));
            return;
        }

        /** @var PageGateway $pageGateway */
        $pageGateway = $this->getGateway(PageGateway::NAME);

        $page = $pageGateway->findOneById($request->id);

        if ($page == null) {
            $presenter->setErrors(array(PagePresenter::UNDEFINED_PAGE_ID));
            return;
        }

        $pageGateway->delete($page->getId());

        $presenter->setPage($page);
    }
}