<?php

namespace Diside\SecurityComponent\Interactor\Interactor;

use Diside\SecurityComponent\Gateway\UserGateway;
use Diside\SecurityComponent\Interactor\AbstractInteractor;
use Diside\SecurityComponent\Interactor\Presenter;
use Diside\SecurityComponent\Interactor\Presenter\DeleteUserPresenter;
use Diside\SecurityComponent\Interactor\Presenter\UserPresenter;
use Diside\SecurityComponent\Interactor\Request;
use Diside\SecurityComponent\Interactor\Request\DeleteUserRequest;

class DeleteUserInteractor extends AbstractInteractor
{
    public function process(Request $request, Presenter $presenter)
    {
        $userGateway = $this->getGateway(UserGateway::NAME);

        /** @var DeleteUserRequest $request */
        /** @var UserPresenter $presenter */

        if ($request->userId === null) {
            $presenter->setErrors(array(UserPresenter::UNDEFINED_USER_ID));
            return;
        }

        $executor = $userGateway->findOneById($request->userId);

        $user = $userGateway->findOneById($request->id);

        if ($user == null) {
            $presenter->setErrors(array(UserPresenter::UNDEFINED_USER));
            return;
        }

        if (!($executor->isSuperadmin() || ($executor->isAdmin() && $executor->hasSameCompanyAs($user)))) {
            $presenter->setErrors(array(UserPresenter::FORBIDDEN));
            return;
        }

        $userGateway->delete($user->getId());

        $presenter->setUser($user);
    }
}