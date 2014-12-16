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
    protected $errors = array();

    public function process(Request $request, Presenter $presenter)
    {
        $userGateway = $this->getGateway('user_gateway');

        /** @var RegisterUserRequest $request */
        /** @var UserPresenter $presenter */

        if (!$this->validate($request, $presenter)) {
            $presenter->setErrors($this->errors);
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

        $user = $this->prePersist($request, $user);

        $user = $userGateway->save($user);

        $presenter->setUser($user);
    }

    protected function validate(Request $request, UserPresenter $presenter)
    {
        if ($request->email === null) {
            $this->errors[] = UserPresenter::EMPTY_EMAIL;
        }

        if ($request->password === null) {
            $this->errors[] = UserPresenter::EMPTY_PASSWORD;
        }

        return empty($this->errors);
    }

    protected function buildUser(Request $request)
    {
        return new User(null, $request->email, $request->password, $request->salt);
    }

    protected function prePersist($request, $user)
    {
        return $user;
    }
}