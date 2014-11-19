<?php

namespace Diside\SecurityComponent\Interactor\Interactor;

use Diside\SecurityComponent\Gateway\CompanyGateway;
use Diside\SecurityComponent\Gateway\UserGateway;
use Diside\SecurityComponent\Interactor\AbstractInteractor;
use Diside\SecurityComponent\Interactor\Presenter;
use Diside\SecurityComponent\Interactor\Presenter\UserPresenter;
use Diside\SecurityComponent\Interactor\Request;
use Diside\SecurityComponent\Interactor\Request\ChangePasswordRequest;
use Diside\SecurityComponent\Interactor\Request\SaveUserRequest;
use Diside\SecurityComponent\Model\User;

class SaveUserInteractor extends AbstractInteractor
{
    public function process(Request $request, Presenter $presenter)
    {
        $companyGateway = $this->getGateway('company_gateway');
        $userGateway = $this->getGateway('user_gateway');

        /** @var SaveUserRequest $request */
        /** @var UserPresenter $presenter */

        if (!$this->validate($request, $presenter)) {
            return;
        }

        $executor = $userGateway->findOneById($request->userId);

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
                $user = $userGateway->findOneById($request->id);

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
                $user = $this->buildUser($request->id, $request->email, $request->password);
            }

            if ($executor->isSuperadmin()) {
                if ((in_array(User::ROLE_ADMIN, $request->roles) || in_array(User::ROLE_MANAGER, $request->roles)) && $request->companyId == null) {
                    $error = UserPresenter::UNDEFINED_COMPANY;
                    $presenter->setErrors(array($error));
                    return;
                }

                $company = $companyGateway->findOneById($request->companyId);
                $user->setCompany($company);
                $user->updateExtraFields($request->extraFields);
            }
            else if($executor->isAdmin()) {
                $user->setCompany($executor->getCompany());
            }

            $user->setActive($request->isActive);
            $user->setRoles($request->roles);

            $user = $this->prePersist($executor, $request, $user);
        }

        $user = $userGateway->save($user);

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

    protected function prePersist($executor, $request, $user)
    {
        return $user;
    }

    protected function buildUser($id, $email, $password)
    {
        return new User($id, $email, $password, '');
    }
}