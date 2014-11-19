<?php

namespace Diside\SecurityComponent\Interactor\Interactor;

use Diside\SecurityComponent\Interactor\AbstractInteractor;
use Diside\SecurityComponent\Interactor\Request;
use Diside\SecurityComponent\Interactor\Request\ConfirmUserRegistrationRequest;
use Diside\SecurityComponent\Interactor\Presenter;
use Diside\SecurityComponent\Interactor\Presenter\UserPresenter;

class ConfirmUserRegistrationInteractor extends AbstractInteractor
{

    public function process(Request $request, Presenter $presenter)
    {
        $userGateway = $this->getGateway('user_gateway');

        /** @var ConfirmUserRegistrationRequest $request */
        /** @var UserPresenter $presenter */

        if (!$this->validate($request, $presenter)) {
            return;
        }

        $user = $userGateway->findOneByRegistrationToken($request->token);

        if ($user == null) {
            $presenter->setErrors(array(UserPresenter::UNDEFINED_USER));
            return;
        }

        $user->setRegistrationToken(null);
        $user->setActive(true);

        $user = $userGateway->save($user);

        $presenter->setUser($user);
    }

    private function validate(Request $request, UserPresenter $presenter)
    {
        if ($request->token === null) {
            $error = UserPresenter::EMPTY_REGISTRATION_TOKEN;
            $presenter->setErrors(array($error));
            return false;
        }

        return true;
    }
}