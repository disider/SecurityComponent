<?php

namespace Diside\SecurityComponent\Interactor\Interactor;

use Diside\SecurityComponent\Gateway\LogGateway;
use Diside\SecurityComponent\Interactor\AbstractInteractor;
use Diside\SecurityComponent\Interactor\Presenter;
use Diside\SecurityComponent\Interactor\Presenter\LogsPresenter;
use Diside\SecurityComponent\Interactor\Request;
use Diside\SecurityComponent\Interactor\Request\FindLogsRequest;

class FindLogsInteractor extends AbstractInteractor
{
    public function process(Request $request, Presenter $presenter)
    {
        $logGateway = $this->getGateway('log_gateway');
        $userGateway = $this->getGateway('user_gateway');

        /** @var FindLogsRequest $request */
        /** @var LogsPresenter $presenter */

        $user = $userGateway->findOneById($request->userId);

        $filters = $request->filters;

        if ($user->isSuperadmin()) {
            // return all logs
        } else if ($user->isAdmin()) {
            $filters[LogGateway::FILTER_BY_COMPANY_ID] = $user->getCompanyId();
        } else {
            $presenter->setErrors(array(Presenter::FORBIDDEN));
            return;
        }

        $logs = $logGateway->findAll($filters, $request->pageIndex, $request->pageSize);

        $total = $logGateway->countAll($filters);

        $presenter->setLogs($logs);
        $presenter->setTotalLogs($total);
    }
}