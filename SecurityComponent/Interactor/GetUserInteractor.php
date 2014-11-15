<?php

namespace SecurityComponent\Interactor;

use SecurityComponent\Gateway\UserGateway;
use SecurityComponent\Interactor\Presenter\UserPresenter;
use SecurityComponent\Interactor\Presenter;
use SecurityComponent\Interactor\Request;
use SecurityComponent\Interactor\Request\GetUserByEmailRequest;
use SecurityComponent\Interactor\Request\GetUserByIdRequest;
use SecurityComponent\Interactor\Request\GetUserByRegistrationTokenRequest;
use SecurityComponent\Interactor\Request\GetUserByResetPasswordTokenRequest;

class GetUserInteractor implements Interactor
{
    private $userGateway;

    public function __construct(UserGateway $userGateway)
    {
        $this->userGateway = $userGateway;
    }

    public function process(Request $request, Presenter $presenter)
    {
        /** @var GetUserByEmailRequest $request */
        /** @var UserPresenter $presenter */

        if($request instanceof GetUserByEmailRequest) {
            if ($request->email === null) {
                $presenter->setErrors(array(UserPresenter::EMPTY_EMAIL));
                return;
            }

            $user = $this->userGateway->findOneByEmail($request->email);
        }

        if($request instanceof GetUserByIdRequest) {
            if ($request->id === null) {
                $presenter->setErrors(array(UserPresenter::UNDEFINED_USER_ID));
                return;
            }

            $user = $this->userGateway->findOneById($request->id);
        }

        if($request instanceof GetUserByResetPasswordTokenRequest) {
            if ($request->token === null) {
                $presenter->setErrors(array(UserPresenter::EMPTY_RESET_PASSWORD_TOKEN));
                return;
            }

            $user = $this->userGateway->findOneByResetPasswordToken($request->token);
        }

        if($user == null) {
            $presenter->setErrors(array(UserPresenter::UNDEFINED_USER));
            return;
        }

        $presenter->setUser($user);
    }
}