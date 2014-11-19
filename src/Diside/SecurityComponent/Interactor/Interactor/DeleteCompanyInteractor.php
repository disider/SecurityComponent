<?php

namespace Diside\SecurityComponent\Interactor\Interactor;

use Diside\SecurityComponent\Interactor\AbstractInteractor;
use Diside\SecurityComponent\Interactor\Presenter;
use Diside\SecurityComponent\Interactor\Presenter\CompanyPresenter;
use Diside\SecurityComponent\Interactor\Presenter\DeleteCompanyPresenter;
use Diside\SecurityComponent\Interactor\Request;
use Diside\SecurityComponent\Interactor\Request\DeleteCompanyRequest;

class DeleteCompanyInteractor extends AbstractInteractor
{
    public function process(Request $request, Presenter $presenter)
    {
        $companyGateway = $this->getGateway('company_gateway');
        $userGateway = $this->getGateway('user_gateway');

        /** @var DeleteCompanyRequest $request */
        /** @var CompanyPresenter $presenter */

        if ($request->superadminId === null) {
            $presenter->setErrors(array(CompanyPresenter::UNDEFINED_SUPERADMIN_ID));
            return;
        }

        $superadmin = $userGateway->findOneById($request->superadminId);
        if (!$superadmin->isSuperadmin()) {
            $presenter->setErrors(array(CompanyPresenter::FORBIDDEN));
            return;
        }

        $company = $companyGateway->findOneById($request->id);

        if ($company == null) {
            $presenter->setErrors(array(CompanyPresenter::UNDEFINED_COMPANY_ID));
            return;
        }

        $companyGateway->delete($company->getId());

        $presenter->setCompany($company);
    }
}