<?php

namespace SecurityComponent\Interactor;


use SecurityComponent\Gateway\UserGateway;
use SecurityComponent\Interactor\Presenter\FindUsersPresenter;
use SecurityComponent\Interactor\Request\FindUsersRequest;

class FindUsersInteractor implements Interactor
{
    /** @var UserGateway */
    private $userGateway;

    public function __construct(UserGateway $userGateway)
    {
        $this->userGateway = $userGateway;
    }

    public function process(Request $request, Presenter $presenter)
    {
        /** @var FindUsersRequest $request */
        /** @var FindUsersPresenter $presenter */

        $user = $this->userGateway->findOneById($request->userId);

        $filters = array();

        if($user->isSuperadmin()) {
            $filters[UserGateway::FILTER_SUPERADMIN] = true;
        }
        else if($user->isAdmin()) {
            $filters[UserGateway::FILTER_BY_COMPANY_ID] = $user->getCompanyId();
        }
        else if($user->isManager()) {
            $filters[UserGateway::FILTER_ACTIVE] = true;
            $filters[UserGateway::FILTER_BY_COMPANY_ID] = $user->getCompanyId();
        }

        $users = $this->userGateway->findAll($filters, $request->pageIndex, $request->pageSize);

        $total = $this->userGateway->countAll($filters);

        $presenter->setUsers($users);
        $presenter->setTotalUsers($total);
    }
}