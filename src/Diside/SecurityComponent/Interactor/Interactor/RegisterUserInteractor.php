<?php

namespace Diside\SecurityComponent\Interactor\Interactor;

use Diside\SecurityComponent\Gateway\UserGateway;
use Diside\SecurityComponent\Helper\TokenGenerator;
use Diside\SecurityComponent\Interactor\AbstractInteractor;
use Diside\SecurityComponent\Interactor\Presenter;
use Diside\SecurityComponent\Interactor\Presenter\UserPresenter;
use Diside\SecurityComponent\Interactor\Request;
use Diside\SecurityComponent\Interactor\Request\RegisterUserRequest;
use Diside\SecurityComponent\Model\User;

class RegisterUserInteractor extends AbstractInteractor
{
    public function process(Request $request, Presenter $presenter)
    {
        $userGateway = $this->getGateway('user_gateway');

        /** @var RegisterUserRequest $request */
        /** @var UserPresenter $presenter */

        if (!$this->validate($request, $presenter)) {
            return;
        }

        $user = $userGateway->findOneByEmail($request->email);
        if ($user != null) {
            $presenter->setErrors(array(UserPresenter::EMAIL_ALREADY_DEFINED));
            return;
        }

        $user = $this->buildUser($request);
        $user->setActive(false);
        $user->setRegistrationToken(TokenGenerator::generateToken());
        $user->addRole(User::ROLE_FREE_USER);

        $user = $userGateway->save($user);

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

    protected function buildUser(Request $request)
    {
        return new User(null, $request->email, $request->password, $request->salt);
    }
}