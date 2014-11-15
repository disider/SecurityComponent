<?php

namespace SecurityComponent\Interactor;

use SecurityComponent\Gateway\UserGateway;
use SecurityComponent\Interactor\Presenter;
use SecurityComponent\Interactor\Presenter\DeleteUserPresenter;
use SecurityComponent\Interactor\Presenter\UserPresenter;
use SecurityComponent\Interactor\Request;
use SecurityComponent\Interactor\Request\DeleteUserRequest;

class DeleteUserInteractor implements Interactor
{
    private $userGateway;

    public function __construct(UserGateway $userGateway)
    {
        $this->userGateway = $userGateway;
    }

    public function process(Request $request, Presenter $presenter)
    {
        /** @var DeleteUserRequest $request */
        /** @var UserPresenter $presenter */

        if ($request->userId === null) {
            $presenter->setErrors(array(UserPresenter::UNDEFINED_USER_ID));
            return;
        }

        $executor = $this->userGateway->findOneById($request->userId);

        $user = $this->userGateway->findOneById($request->id);

        if ($user == null) {
            $presenter->setErrors(array(UserPresenter::UNDEFINED_USER));
            return;
        }

        if (!($executor->isSuperadmin() || ($executor->isAdmin() && $executor->hasSameCompanyAs($user)))) {
            $presenter->setErrors(array(UserPresenter::FORBIDDEN));
            return;
        }

        $this->userGateway->delete($user->getId());

        $presenter->setUser($user);
    }
}