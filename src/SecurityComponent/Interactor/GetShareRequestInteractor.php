<?php

namespace SecurityComponent\Interactor;

use SecurityComponent\Gateway\ShareRequestGateway;
use SecurityComponent\Gateway\UserGateway;
use SecurityComponent\Interactor\Presenter;
use SecurityComponent\Interactor\Presenter\ShareRequestPresenter;
use SecurityComponent\Interactor\Request;
use SecurityComponent\Interactor\Request\GetShareRequestByEmailRequest;
use SecurityComponent\Interactor\Request\GetShareRequestByIdRequest;
use SecurityComponent\Interactor\Request\GetShareRequestRequest;

class GetShareRequestInteractor implements Interactor
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
        /** @var GetShareRequestRequest $request */
        /** @var ShareRequestPresenter $presenter */

        $user = $this->userGateway->findOneById($request->userId);

        if ($user == null) {
            $presenter->setErrors(array(ShareRequestPresenter::NOT_FOUND));
            return;
        }

        if ($request->token === null) {
            $presenter->setErrors(array(ShareRequestPresenter::UNDEFINED_SHARE_REQUEST_TOKEN));
            return;
        }

        $shareRequest = $this->shareRequestGateway->findOneByToken($request->token);

        if ($shareRequest == null) {
            $presenter->setErrors(array(ShareRequestPresenter::NOT_FOUND));
            return;
        }

        if (($shareRequest->getEmail() != $user->getEmail()) || $user->isSameAs($shareRequest->getChecklistTemplate()->getOwner())) {
            $presenter->setErrors(array(ShareRequestPresenter::FORBIDDEN));
            return;
        }

        $presenter->setShareRequest($shareRequest);
    }
}