<?php

namespace SecurityComponent\Interactor;

use SecurityComponent\Gateway\RunningChecklistGateway;
use SecurityComponent\Gateway\UserGateway;
use SecurityComponent\Interactor\Presenter;
use SecurityComponent\Interactor\Presenter\FindRunningChecklistsPresenter;
use SecurityComponent\Interactor\Request;
use SecurityComponent\Interactor\Request\FindRunningChecklistsRequest;

class FindRunningChecklistsInteractor implements Interactor
{
    /** @var RunningChecklistGateway */
    private $runningChecklistGateway;

    /** @var UserGateway */
    private $userGateway;

    public function __construct(RunningChecklistGateway $runningChecklistGateway, UserGateway $userGateway)
    {
        $this->runningChecklistGateway = $runningChecklistGateway;
        $this->userGateway = $userGateway;
    }

    public function process(Request $request, Presenter $presenter)
    {
        /** @var FindRunningChecklistsRequest $request */
        /** @var FindRunningChecklistsPresenter $presenter */

        if ($request->userId === null) {
            $presenter->setErrors(array(FindRunningChecklistsPresenter::UNDEFINED_USER_ID));
            return;
        }

        $user = $this->userGateway->findOneById($request->userId);

        $filters = $request->filters;

        if ($user->isSuperadmin()) {
            if(array_key_exists(RunningChecklistGateway::FILTER_ONLY_MINE, $filters) && $filters[RunningChecklistGateway::FILTER_ONLY_MINE])
                $filters[RunningChecklistGateway::FILTER_BY_USER_ID] = $request->userId;
            // Do not add any other filters
        } else if($user->isAdmin()) {
            $filters[RunningChecklistGateway::FILTER_BY_COMPANY_ID] = $user->getCompanyId();
        }
        else {
            $filters[RunningChecklistGateway::FILTER_BY_USER_ID] = $request->userId;
        }

        $models = $this->runningChecklistGateway->findAll($filters, $request->pageIndex, $request->pageSize);
        $total = $this->runningChecklistGateway->countAll($filters);

        $presenter->setRunningChecklists($models);
        $presenter->setTotalRunningChecklists($total);
    }
}