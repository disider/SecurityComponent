<?php

namespace Diside\SecurityComponent\Interactor\Interactor;

use Diside\SecurityComponent\Gateway\UserGateway;
use Diside\SecurityComponent\Interactor\AbstractInteractor;
use Diside\SecurityComponent\Interactor\Presenter\UserPresenter;
use Diside\SecurityComponent\Interactor\Presenter;
use Diside\SecurityComponent\Interactor\Request;
use Diside\SecurityComponent\Interactor\Request\GetUserByEmailRequest;
use Diside\SecurityComponent\Interactor\Request\GetUserByIdRequest;
use Diside\SecurityComponent\Interactor\Request\GetUserByRegistrationTokenRequest;
use Diside\SecurityComponent\Interactor\Request\GetUserByResetPasswordTokenRequest;

class GetUserInteractor extends AbstractInteractor
{
    public function process(Request $request, Presenter $presenter)
    {
        $userGateway = $this->getGateway('user_gateway');

        /** @var GetUserByEmailRequest $request */
        /** @var UserPresenter $presenter */

        if($request instanceof GetUserByEmailRequest) {
            if ($request->email === null) {
                $presenter->setErrors(array(UserPresenter::EMPTY_EMAIL));
                return;
            }

            $user = $userGateway->findOneByEmail($request->email);
        }

        if($request instanceof GetUserByIdRequest) {
            if ($request->id === null) {
                $presenter->setErrors(array(UserPresenter::UNDEFINED_USER_ID));
                return;
            }

            $user = $userGateway->findOneById($request->id);
        }

        if($request instanceof GetUserByResetPasswordTokenRequest) {
            if ($request->token === null) {
                $presenter->setErrors(array(UserPresenter::EMPTY_RESET_PASSWORD_TOKEN));
                return;
            }

            $user = $userGateway->findOneByResetPasswordToken($request->token);
        }

        if($user == null) {
            $presenter->setErrors(array(UserPresenter::UNDEFINED_USER));
            return;
        }

        $presenter->setUser($user);
    }
}