<?php

namespace Diside\SecurityComponent\Interactor\Interactor;

use Diside\SecurityComponent\Gateway\UserGateway;
use Diside\SecurityComponent\Interactor\AbstractInteractor;
use Diside\SecurityComponent\Interactor\Presenter;
use Diside\SecurityComponent\Interactor\Presenter\UsersPresenter;
use Diside\SecurityComponent\Interactor\Request;
use Diside\SecurityComponent\Interactor\Request\FindUsersRequest;

class FindUsersInteractor extends AbstractInteractor
{
    public function process(Request $request, Presenter $presenter)
    {
        $userGateway = $this->getGateway('user_gateway');

        /** @var FindUsersRequest $request */
        /** @var UsersPresenter $presenter */

        $user = $userGateway->findOneById($request->userId);

        $filters = array();

        if ($user->isSuperadmin()) {
            $filters[UserGateway::FILTER_SUPERADMIN] = true;
        } else if ($user->isAdmin()) {
            $filters[UserGateway::FILTER_BY_COMPANY_ID] = $user->getCompanyId();
        } else if ($user->isManager()) {
            $filters[UserGateway::FILTER_ACTIVE] = true;
            $filters[UserGateway::FILTER_BY_COMPANY_ID] = $user->getCompanyId();
        }

        $users = $userGateway->findAll($filters, $request->pageIndex, $request->pageSize);

        $total = $userGateway->countAll($filters);

        $presenter->setUsers($users);
        $presenter->setTotalUsers($total);
    }
}