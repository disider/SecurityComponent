<?php

namespace SecurityComponent\Interactor;

use SecurityComponent\Gateway\UserGateway;
use SecurityComponent\Helper\TokenGenerator;
use SecurityComponent\Interactor\Presenter;
use SecurityComponent\Interactor\Presenter\UserPresenter;
use SecurityComponent\Interactor\Request;
use SecurityComponent\Interactor\Request\RegisterUserRequest;
use SecurityComponent\Model\User;

class RegisterUserInteractor implements Interactor
{
    /** @var UserGateway */
    private $userGateway;

    public function __construct(UserGateway $userGateway)
    {
        $this->userGateway = $userGateway;
    }

    public function process(Request $request, Presenter $presenter)
    {
        /** @var RegisterUserRequest $request */
        /** @var UserPresenter $presenter */

        if (!$this->validate($request, $presenter)) {
            return;
        }

        $user = $this->userGateway->findOneByEmail($request->email);
        if ($user != null) {
            $presenter->setErrors(array(UserPresenter::EMAIL_ALREADY_DEFINED));
            return;
        }

        $user = new User(null, $request->email, $request->password, $request->salt);
        $user->setActive(false);
        $user->setRegistrationToken(TokenGenerator::generateToken());
        $user->addRole(User::ROLE_FREE_USER);

        $user = $this->userGateway->save($user);

        $presenter->setUser($user);
    }

    private function validate(Request $request, UserPresenter $presenter)
    {
        if ($request->email === null) {
            $error = UserPresenter::EMPTY_EMAIL;
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