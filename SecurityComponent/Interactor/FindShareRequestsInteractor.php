<?php

namespace SecurityComponent\Interactor;


use SecurityComponent\Gateway\ShareRequestGateway;
use SecurityComponent\Gateway\UserGateway;
use SecurityComponent\Interactor\Presenter\FindShareRequestsPresenter;
use SecurityComponent\Interactor\Request\FindShareRequestsRequest;

class FindShareRequestsInteractor implements Interactor
{
    /** @var ShareRequestGateway */
    private $shareRequestGateway;

    /** @var UserGateway */
    private $userGateway;

    public function __construct(ShareRequestGateway $shareRequestGateway, UserGateway $userGateway)
    {
        $this->shareRequestGateway = $shareRequestGateway;
        $this->userGateway = $userGateway;
    }

    public function process(Request $request, Presenter $presenter)
    {
        /** @var FindShareRequestsRequest $request */
        /** @var FindShareRequestsPresenter $presenter */

        $user = $this->userGateway->findOneById($request->userId);
        if($user == null) {
            $presenter->setErrors(array(Presenter::NOT_FOUND));
            return;
        }

        $filters = array();
        if(!$user->isSuperadmin()) {
            $filters[ShareRequestGateway::FILTER_BY_EMAIL] = $user->getEmail();
        }

        $shareRequests = $this->shareRequestGateway->findAll($filters, $request->pageIndex, $request->pageSize);

        $total = $this->shareRequestGateway->countAll($filters);

        $presenter->setShareRequests($shareRequests);
        $presenter->setTotalShareRequests($total);
    }
}