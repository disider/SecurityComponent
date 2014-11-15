<?php

namespace SecurityComponent\Interactor;

use SecurityComponent\Gateway\CompanyGateway;
use SecurityComponent\Gateway\UserGateway;
use SecurityComponent\Interactor\Presenter;
use SecurityComponent\Interactor\Presenter\UserPresenter;
use SecurityComponent\Interactor\Request;
use SecurityComponent\Interactor\Request\ChangePasswordRequest;
use SecurityComponent\Interactor\Request\SaveUserRequest;
use SecurityComponent\Model\User;

class SaveUserInteractor implements Interactor
{
    /** @var UserGateway */
    private $userGateway;

    /** @var CompanyGateway */
    private $companyGateway;

    public function __construct(UserGateway $userGateway, CompanyGateway $companyGateway)
    {
        $this->userGateway = $userGateway;
        $this->companyGateway = $companyGateway;
    }

    public function process(Request $request, Presenter $presenter)
    {
        /** @var SaveUserRequest $request */
        /** @var UserPresenter $presenter */

        if (!$this->validate($request, $presenter)) {
            return;
        }

        $executor = $this->userGateway->findOneById($request->userId);

        if($request instanceof ChangePasswordRequest) {
            if ($request->id != $executor->getId()) {
                $presenter->setErrors(array(UserPresenter::FORBIDDEN));
                return;
            }

            $user = $executor;

            if($user->getPassword() != $request->currentPassword) {
                $presenter->setErrors(array(UserPresenter::WRONG_PASSWORD));
                return;
            }

            $user->setPassword($request->newPassword);
        }

        if($request instanceof SaveUserRequest) {
            if($request->id !== null) {
                $user = $this->userGateway->findOneById($request->id);

                if(!($executor->isSuperadmin() || ($executor->isAdmin() && $user->hasSameCompanyAs($executor)) || $user->isSameAs($executor))) {
                    $presenter->setErrors(array(Presenter::FORBIDDEN));
                    return;
                }

                $user->setEmail($request->email);

                if($request->password !== null) {
                    $user->setPassword($request->password);
                }
            }
            else {
                $user = new User($request->id, $request->email, $request->password, '');
            }

            if ($executor->isSuperadmin()) {
                if ((in_array(User::ROLE_ADMIN, $request->roles) || in_array(User::ROLE_MANAGER, $request->roles)) && $request->companyId == null) {
                    $error = UserPresenter::UNDEFINED_COMPANY;
                    $presenter->setErrors(array($error));
                    return;
                }

                $company = $this->companyGateway->findOneById($request->companyId);
                $user->setCompany($company);
                $user->updateExtraFields($request->extraFields);
            }
            else if($executor->isAdmin()) {
                $user->setCompany($executor->getCompany());
            }

            $user->setActive($request->isActive);
            $user->setRoles($request->roles);
        }

        $user = $this->userGateway->save($user);

        $presenter->setUser($user);
    }

    private function validate(Request $request, UserPresenter $presenter)
    {
        if($request instanceof SaveUserRequest) {
            if ($request->userId === null) {
                $error = UserPresenter::UNDEFINED_USER_ID;
                $presenter->setErrors(array($error));
                return false;
            }

            if ($request->email === null) {
                $error = UserPresenter::EMPTY_EMAIL;
                $presenter->setErrors(array($error));
                return false;
            }

            if ($request->id === null && $request->password == null) {
                $error = UserPresenter::EMPTY_PASSWORD;
                $presenter->setErrors(array($error));
                return false;
            }
        }

        if($request instanceof ChangePasswordRequest) {
            if ($request->currentPassword == null) {
                $error = UserPresenter::EMPTY_CURRENT_PASSWORD;
                $presenter->setErrors(array($error));
                return false;
            }

            if ($request->newPassword == null) {
                $error = UserPresenter::EMPTY_NEW_PASSWORD;
                $presenter->setErrors(array($error));
                return false;
            }
        }

        return true;
    }
}