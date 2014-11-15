<?php

namespace SecurityComponent\Interactor;

use SecurityComponent\Gateway\UserGateway;
use SecurityComponent\Interactor\Presenter;
use SecurityComponent\Interactor\Presenter\UserPresenter;
use SecurityComponent\Interactor\Request;
use SecurityComponent\Interactor\Request\ConfirmUserRegistrationRequest;
use SecurityComponent\Model\User;

class ConfirmUserRegistrationInteractor implements Interactor
{
    /** @var UserGateway */
    private $userGateway;

    public function __construct(UserGateway $userGateway)
    {
        $this->userGateway = $userGateway;
    }

    public function process(Request $request, Presenter $presenter)
    {
        /** @var ConfirmUserRegistrationRequest $request */
        /** @var UserPresenter $presenter */

        if (!$this->validate($request, $presenter)) {
            return;
        }

        $user = $this->userGateway->findOneByRegistrationToken($request->token);

        if ($user == null) {
            $presenter->setErrors(array(UserPresenter::UNDEFINED_USER));
            return;
        }

        $user->setRegistrationToken(null);
        $user->setActive(true);

        $user = $this->userGateway->save($user);

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