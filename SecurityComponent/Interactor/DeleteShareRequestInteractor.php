<?php

namespace SecurityComponent\Interactor;

use SecurityComponent\Gateway\ShareRequestGateway;
use SecurityComponent\Gateway\UserGateway;
use SecurityComponent\Interactor\Presenter;
use SecurityComponent\Interactor\Presenter\DeleteShareRequestPresenter;
use SecurityComponent\Interactor\Presenter\ShareRequestPresenter;
use SecurityComponent\Interactor\Request;
use SecurityComponent\Interactor\Request\DeleteShareRequestRequest;

class DeleteShareRequestInteractor implements Interactor
{
    /** @var UserGateway */
    private $userGateway;

    /** @var ShareRequestGateway  */
    private $shareRequestGateway;

    public function __construct(ShareRequestGateway $shareRequestGateway, UserGateway $userGateway)
    {
        $this->userGateway = $userGateway;
        $this->shareRequestGateway = $shareRequestGateway;
    }

    public function process(Request $request, Presenter $presenter)
    {
        /** @var DeleteShareRequestRequest $request */
        /** @var ShareRequestPresenter $presenter */

//        if ($request->superadminId === null) {
//            $presenter->setErrors(array(ShareRequestPresenter::UNDEFINED_SUPERADMIN_ID));
//            return;
//        }
//
//        $superadmin = $this->userGateway->findOneById($request->superadminId);
//        if (!$superadmin->isSuperadmin()) {
//            $presenter->setErrors(array(ShareRequestPresenter::FORBIDDEN));
//            return;
//        }

        $shareRequest = $this->shareRequestGateway->findOneByToken($request->token);

        if ($shareRequest == null) {
            $presenter->setErrors(array(ShareRequestPresenter::NOT_FOUND));
            return;
        }

        $this->shareRequestGateway->delete($shareRequest->getId());

        $presenter->setShareRequest($shareRequest);
    }
}