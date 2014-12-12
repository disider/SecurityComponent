<?php

namespace Diside\SecurityComponent\Interactor\Interactor;

use Diside\SecurityComponent\Gateway\PageGateway;
use Diside\SecurityComponent\Gateway\UserGateway;
use Diside\SecurityComponent\Interactor\AbstractInteractor;
use Diside\SecurityComponent\Interactor\Presenter;
use Diside\SecurityComponent\Interactor\Presenter\PagePresenter;
use Diside\SecurityComponent\Interactor\Request;
use Diside\SecurityComponent\Interactor\Request\SavePageRequest;
use Diside\SecurityComponent\Model\Page;
use Diside\SecurityComponent\Model\PageTranslation;

class SavePageInteractor extends AbstractInteractor
{
    public function process(Request $request, Presenter $presenter)
    {
        /** @var SavePageRequest $request */
        /** @var PagePresenter $presenter */

        if (!$this->checkExecutor($request->executorId, $presenter)) {
            return;
        }

        /** @var PageGateway $pageGateway */
        $pageGateway = $this->getGateway(PageGateway::NAME);

        $page = new Page($request->id);
        foreach($request->translations as $translation)
            $page->addTranslation($this->buildTranslation($translation));

        $page = $pageGateway->save($page);

        $presenter->setPage($page);
    }

    private function buildTranslation($request)
    {
        return new PageTranslation($request->id, $request->language, $request->url, $request->title, $request->content);
    }
}