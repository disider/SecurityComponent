<?php

namespace Diside\SecurityComponent\Interactor\Interactor;

use Diside\SecurityComponent\Gateway\UserGateway;
use Diside\SecurityComponent\Interactor\AbstractInteractor;
use Diside\SecurityComponent\Interactor\Presenter;
use Diside\SecurityComponent\Interactor\Presenter\UserPresenter;
use Diside\SecurityComponent\Interactor\Request;
use Diside\SecurityComponent\Interactor\Request\ResetPasswordRequest;
use Diside\SecurityComponent\Model\User;

class ResetPasswordInteractor extends AbstractInteractor
{
    public function process(Request $request, Presenter $presenter)
    {
        $userGateway = $this->getGateway('user_gateway');

        /** @var ResetPasswordRequest $request */
        /** @var UserPresenter $presenter */

        if (!$this->validate($request, $presenter)) {
            return;
        }

        $user = $userGateway->findOneById($request->userId);
        if ($user == null) {
            $presenter->setErrors(array(UserPresenter::UNDEFINED_USER));
            return;
        }

        $user->setPassword($request->password);
        $user->setResetPasswordToken(null);

        $user = $userGateway->save($user);

        $presenter->setUser($user);
    }

    private function validate(ResetPasswordRequest $request, UserPresenter $presenter)
    {
        if ($request->userId === null) {
            $error = UserPresenter::UNDEFINED_USER_ID;
            $presenter->setErrors(array($error));
            return false;
        }

        if ($request->password === null) {
            $error = UserPresenter::EMPTY_PASSWORD;
            $presenter->setErrors(array($error));
            return false;
        }

        return true;
    }
}