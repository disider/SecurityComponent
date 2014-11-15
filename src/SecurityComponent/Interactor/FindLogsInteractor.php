<?php

namespace SecurityComponent\Interactor;


use SecurityComponent\Gateway\LogGateway;
use SecurityComponent\Gateway\UserGateway;
use SecurityComponent\Interactor\Presenter\FindLogsPresenter;
use SecurityComponent\Interactor\Request\FindLogsRequest;

class FindLogsInteractor implements Interactor
{

    /** @var LogGateway */
    private $logGateway;

    /** @var UserGateway */
    private $userGateway;

    public function __construct(LogGateway $logGateway, UserGateway $userGateway)
    {
        $this->logGateway = $logGateway;
        $this->userGateway = $userGateway;
    }

    public function process(Request $request, Presenter $presenter)
    {
        /** @var FindLogsRequest $request */
        /** @var FindLogsPresenter $presenter */

        $user = $this->userGateway->findOneById($request->userId);

        $filters = $request->filters;

        if($user->isSuperadmin()) {
            // return all logs
        }
        else if($user->isAdmin()) {
            $filters[LogGateway::FILTER_BY_COMPANY_ID] = $user->getCompanyId();
        }
        else {
            $presenter->setErrors(array(Presenter::FORBIDDEN));
            return;
        }

        $logs = $this->logGateway->findAll($filters, $request->pageIndex, $request->pageSize);

        $total = $this->logGateway->countAll($filters);

        $presenter->setLogs($logs);
        $presenter->setTotalLogs($total);
    }
}