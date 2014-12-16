<?php

namespace Diside\SecurityComponent\Interactor\Interactor;

use Diside\SecurityComponent\Gateway\UserGateway;
use Diside\SecurityComponent\Helper\TokenGenerator;
use Diside\SecurityComponent\Interactor\AbstractInteractor;
use Diside\SecurityComponent\Interactor\Presenter;
use Diside\SecurityComponent\Interactor\Presenter\UserPresenter;
use Diside\SecurityComponent\Interactor\Request;
use Diside\SecurityComponent\Interactor\Request\RequestResetPasswordRequest;
use Diside\SecurityComponent\Model\User;

class RequestResetPasswordInteractor extends AbstractInteractor
{
    public function process(Request $request, Presenter $presenter)
    {
        $userGateway = $this->getGateway(UserGateway::NAME);

        /** @var RequestResetPasswordRequest $request */
        /** @var UserPresenter $presenter */

        if (!$this->validate($request, $presenter)) {
            return;
        }

        $user = $userGateway->findOneByEmail($request->email);
        if ($user == null) {
            $presenter->setErrors(array(UserPresenter::UNDEFINED_USER));
            return;
        }

        $user->setResetPasswordToken(TokenGenerator::generateToken());

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

        return true;
    }
}