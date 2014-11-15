<?php

namespace SecurityComponent\Interactor;

use SecurityComponent\Gateway\UserGateway;
use SecurityComponent\Interactor\Presenter;
use SecurityComponent\Interactor\Presenter\UserPresenter;
use SecurityComponent\Interactor\Request;
use SecurityComponent\Interactor\Request\ResetPasswordRequest;
use SecurityComponent\Model\User;

class ResetPasswordInteractor implements Interactor
{
    /** @var UserGateway */
    private $userGateway;

    public function __construct(UserGateway $userGateway)
    {
        $this->userGateway = $userGateway;
    }

    public function process(Request $request, Presenter $presenter)
    {
        /** @var ResetPasswordRequest $request */
        /** @var UserPresenter $presenter */

        if (!$this->validate($request, $presenter)) {
            return;
        }

        $user = $this->userGateway->findOneById($request->userId);
        if ($user == null) {
            $presenter->setErrors(array(UserPresenter::UNDEFINED_USER));
            return;
        }

        $user->setPassword($request->password);
        $user->setResetPasswordToken(null);

        $user = $this->userGateway->save($user);

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